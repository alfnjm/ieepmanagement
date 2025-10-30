<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\PendingProposalModel;
use App\Models\EventRegistrationModel; // --- ADDED THIS LINE BACK ---
use App\Models\UserModel;

class Organizer extends BaseController
{
    public function dashboard()
    {
        $eventModel = new EventModel();
        $proposalModel = new PendingProposalModel();
        $registrationModel = new EventRegistrationModel();
        
        $organizerId = session()->get('id');

        // 1. Get all event IDs for this organizer that are approved
        $eventIds = $eventModel->where('organizer_id', $organizerId)
                             ->where('status', 'approved')
                             ->findColumn('id'); // Gets just the IDs, e.g., [4, 6]

        $stats = [
            'my_events'           => 0,
            'proposals_submitted' => 0,
            'total_participants'  => 0,
            'certificates_issued' => 0
        ];

        // 2. Calculate stats
        $stats['my_events'] = count($eventIds);
        $stats['proposals_submitted'] = $proposalModel->where('organizer_id', $organizerId)->countAllResults();

        // 3. Calculate stats only if the organizer has events
        if (!empty($eventIds)) {
            $stats['total_participants'] = $registrationModel
                ->whereIn('event_id', $eventIds)
                ->where('is_attended', 1)
                ->countAllResults();
            
            $stats['certificates_issued'] = $registrationModel
                ->whereIn('event_id', $eventIds)
                ->where('certificate_published', 1)
                ->countAllResults();
        }

        $data['stats'] = $stats;
        $data['title'] = 'Organizer Dashboard';
        return view('organizer/dashboard', $data);
    }

    public function createEvent()
    {
        $data['title'] = 'Create Event Proposal';
        return view('organizer/create_event', $data);
    }

    public function submitProposal()
    {
        $session = session();
        $organizerId = $session->get('id');

        // --- FIX 1: Added validation rules for ALL form fields ---
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'date' => 'required|valid_date',
            'event_time' => 'required',
            'event_location' => 'required',
            'program_start' => 'required',
            'program_end' => 'required',
            'poster' => 'uploaded[poster]|max_size[poster,10240]|is_image[poster]',
            'proposal' => 'uploaded[proposal]|max_size[proposal,10240]|ext_in[proposal,pdf]',
        ];

        if (!$this->validate($rules)) {
            // Re-load the view with validation errors
            return view('organizer/create_event', [
                'validation' => $this->validator,
                'title' => 'Create Event Proposal'
            ]);
        }

        // --- FIX 2: Correctly handle file uploads ---
        $posterFile = $this->request->getFile('poster');
        $proposalFile = $this->request->getFile('proposal');

        $posterName = $posterFile->getRandomName();
        $proposalName = $proposalFile->getRandomName();

        // Use the correct public-facing path for file assets
        $posterFile->move(FCPATH . 'uploads/posters', $posterName);
        $proposalFile->move(FCPATH . 'uploads/proposals', $proposalName);

        // --- FIX 3: Handle the checkbox array ---
        $semesters = $this->request->getPost('eligible_semesters');
        $eligible_semesters = is_array($semesters) ? implode(',', $semesters) : null;

        $model = new PendingProposalModel();
        
        // --- FIX 4: Map form names (e.g., 'title') to DB column names (e.g., 'event_name') ---
        // This is the main reason your data was NULL.
        $dataToSave = [
            'organizer_id' => $organizerId,
            'event_name' => $this->request->getPost('title'),
            'event_description' => $this->request->getPost('description'),
            'event_date' => $this->request->getPost('date'),
            'event_time' => $this->request->getPost('event_time'),
            'event_location' => $this->request->getPost('event_location'),
            'program_start' => $this->request->getPost('program_start'),
            'program_end' => $this->request->getPost('program_end'),
            'eligible_semesters' => $eligible_semesters,
            'poster_image' => $posterName, // Map to 'poster_image'
            'proposal_file' => $proposalName, // Map to 'proposal_file'
            'status' => 'Pending' // Explicitly set status
        ];
        
        $model->save($dataToSave);

        return redirect()->to('organizer/my-proposals')->with('success', 'Proposal submitted successfully.');
    }

    public function myProposals()
    {
        $proposalModel = new PendingProposalModel();
        $userId = session()->get('id');
        $data['proposals'] = $proposalModel->where('organizer_id', $userId)->findAll();
        $data['title'] = 'My Proposals';
        return view('organizer/my_proposals', $data);
    }

    public function participants()
    {
        // Load necessary models
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel(); 
        $userId = session()->get('id');

        // --- FIX: Initialize $selected_event to null ---
        $data['selected_event'] = null;
        $data['participants'] = []; // Default to empty array

        // Get events for the dropdown
        $data['events'] = $eventModel->where('organizer_id', $userId)
                                     ->where('status', 'approved')
                                     ->findAll();

        // Check for a selected event from the URL
        $selectedEventId = $this->request->getGet('event_id');

        if ($selectedEventId) {
            $data['selected_event'] = $selectedEventId; // Set the selected event
            
            // Get participants ONLY for the selected event
            $data['participants'] = $registrationModel
                ->join('users', 'users.id = event_registrations.user_id')
                ->join('events', 'events.id = event_registrations.event_id')
                ->where('event_registrations.event_id', $selectedEventId) // Only for this event
                ->where('event_registrations.is_attended', 1) // Only attended participants
                ->select('
                    events.title as event_title, 
                    events.date as event_date, 
                    users.name as participant_name, 
                    users.student_id as student_id, 
                    users.email as email
                ')
                ->findAll();
        }

        // Pass data to the view
        $data['title'] = 'Event Participants';
        return view('organizer/participants', $data);
    }
    
    public function attendance()
    {
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel();
        $userModel = new UserModel();
        
        $organizerId = session()->get('id');
        
        // --- FIX: Initialize $selected_event to null ---
        $data['selected_event'] = null;
        $data['participants'] = [];

        // --- Handle POST request for SAVING attendance ---
        if ($this->request->getMethod() === 'post') {
            
            $participants = $this->request->getPost('participants'); 
            if (is_null($participants)) {
                $participants = []; // Treat no checks as an empty array
            }
            
            $eventId = $this->request->getPost('event_id'); 

            if (!empty($eventId)) {
                $allRegistrations = $registrationModel->where('event_id', $eventId)->findAll();

                foreach ($allRegistrations as $reg) {
                    $attended = in_array($reg['user_id'], $participants) ? 1 : 0;
                    $registrationModel->update($reg['id'], ['is_attended' => $attended]);
                }
                
                session()->setFlashdata('success', 'Attendance updated successfully.');
            } else {
                session()->setFlashdata('error', 'Could not save attendance. Event ID was missing.');
            }
            
            // Redirect back to the same page, preserving the selected event
            return redirect()->to('organizer/attendance?event_id=' . $eventId);
        }
        
        // --- Handle GET request for DISPLAYING participants ---
        
        // Get only 'approved' events for the dropdown
        $data['events'] = $eventModel->where('organizer_id', $organizerId)
                                    ->where('status', 'approved')
                                    ->findAll();

        // Check if an event_id is provided in the URL (from the dropdown)
        $selectedEventId = $this->request->getGet('event_id');

        if ($selectedEventId) {
            $data['selected_event'] = $selectedEventId; // Set the selected event
            
            // Get participants for the selected event
            $data['participants'] = $registrationModel
                ->where('event_id', $selectedEventId)
                ->join('users', 'users.id = event_registrations.user_id')
                ->select('
                    users.id as user_id, 
                    users.name, 
                    users.student_id, 
                    users.email, 
                    event_registrations.is_attended, 
                    event_registrations.id as reg_id
                ')
                ->findAll();
        }

        $data['title'] = 'Mark Attendance';
        return view('organizer/attendance', $data);
    }
}