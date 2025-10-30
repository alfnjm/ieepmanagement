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
        $userId = session()->get('id');
        $data['events'] = $eventModel->where('organizer_id', $userId)->findAll();
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
        $eventModel = new EventModel();
        $userId = session()->get('id');
        
        // Get events for this organizer
        $data['events'] = $eventModel->where('organizer_id', $userId)->findAll();
        $data['title'] = 'Event Participants';

        return view('organizer/participants', $data);
    }
    
    public function attendance()
    {
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel(); // This line caused the error
        $userModel = new UserModel();
        
        $organizerId = session()->get('id');
        $data['events'] = $eventModel->where('organizer_id', $organizerId)->findAll();
        $data['participants'] = [];
        $data['selected_event'] = null;

        if ($this->request->getPost('event_id')) {
            $eventId = $this->request->getPost('event_id');
            $data['selected_event'] = $eventId;
            
            // On POST: Update attendance
            if ($this->request->getPost('participants')) {
                $participants = $this->request->getPost('participants'); // Array of user_ids that attended
                
                // Get all participants for this event
                $allRegistrations = $registrationModel->where('event_id', $eventId)->findAll();
                
                foreach ($allRegistrations as $reg) {
                    $attended = in_array($reg['user_id'], $participants) ? 1 : 0;
                    // Ensure you're using the correct primary key for update, assuming 'id'
                    $registrationModel->update($reg['id'], ['is_attended' => $attended]);
                }
                
                session()->setFlashdata('success', 'Attendance updated successfully.');
            }

            // Get participants for the selected event
            $data['participants'] = $registrationModel
                ->where('event_id', $eventId)
                ->join('users', 'users.id = event_registrations.user_id')
                ->select('users.id, users.username, users.email, event_registrations.is_attended')
                ->findAll();
        }

        $data['title'] = 'Mark Attendance';
        return view('organizer/attendance', $data);
    }

}