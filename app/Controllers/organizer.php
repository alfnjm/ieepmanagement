<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventRegistrationModel;
use App\Models\UserModel;

class Organizer extends BaseController
{
    // Dashboard method
    public function dashboard()
    {
        $eventModel = new EventModel();
        $proposalModel = new \App\Models\PendingProposalModel();
        $registrationModel = new EventRegistrationModel();
        
        $organizerId = session()->get('id');
        $eventIds = $eventModel->where('organizer_id', $organizerId)
                             ->where('status', 'approved')
                             ->findColumn('id');
        $stats = [
            'my_events'           => 0,
            'proposals_submitted' => 0,
            'total_participants'  => 0,
            'certificates_issued' => 0
        ];
        $stats['my_events'] = count($eventIds);
        $stats['proposals_submitted'] = $proposalModel->where('organizer_id', $organizerId)->countAllResults();
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

    // Create Event method
    public function createEvent()
    {
        $data['title'] = 'Create Event Proposal';
        return view('organizer/create_event', $data);
    }

    // Submit Proposal method
    public function submitProposal()
    {
        $session = session();
        $organizerId = $session->get('id');
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
            return view('organizer/create_event', [
                'validation' => $this->validator,
                'title' => 'Create Event Proposal'
            ]);
        }
        $posterFile = $this->request->getFile('poster');
        $proposalFile = $this->request->getFile('proposal');
        $posterName = $posterFile->getRandomName();
        $proposalName = $proposalFile->getRandomName();
        $posterFile->move(FCPATH . 'uploads/posters', $posterName);
        $proposalFile->move(FCPATH . 'uploads/proposals', $proposalName);
        $semesters = $this->request->getPost('eligible_semesters');
        $eligible_semesters = is_array($semesters) ? implode(',', $semesters) : null;
        $model = new \App\Models\PendingProposalModel();
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
            'poster_image' => $posterName, 
            'proposal_file' => $proposalName, 
            'status' => 'Pending' 
        ];
        $model->save($dataToSave);
        return redirect()->to('organizer/my-proposals')->with('success', 'Proposal submitted successfully.');
    }

    // My Proposals method
    public function myProposals()
    {
        $proposalModel = new \App\Models\PendingProposalModel();
        $userId = session()->get('id');
        $data['proposals'] = $proposalModel->where('organizer_id', $userId)->findAll();
        $data['title'] = 'My Proposals';
        return view('organizer/my_proposals', $data);
    }
    
    /**
     * Display participants page (GET request)
     */
    public function participants()
    {
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel();
        
        $organizerId = session()->get('id');
        
        $data['selectedEventInfo'] = null; 
        $data['participants'] = [];

        // Get all approved events for this organizer
        // --- FIX 1: Use 'date' which matches your SQL file ---
        $data['events'] = $eventModel->where('organizer_id', $organizerId)
                                     ->where('status', 'approved')
                                     ->orderBy('date', 'DESC') // <-- THIS IS THE FIX
                                     ->findAll();

        // Get selected event from query string
        $selectedEventId = $this->request->getGet('event_id');
        $data['selected_event_id'] = $selectedEventId; 

        if ($selectedEventId) {
            
            $eventInfo = $eventModel->where('id', $selectedEventId)
                                    ->where('organizer_id', $organizerId)
                                    ->first();
            
            if ($eventInfo) {
                $data['selectedEventInfo'] = $eventInfo; 
                
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
        }

        $data['title'] = 'Event Participants & Attendance';
        return view('organizer/participants', $data); 
    }

    
    /**
     * Update attendance via AJAX (POST request)
     * This is called when organizer checks/unchecks attendance checkbox
     */
    public function updateAttendance()
    {
        // Verify this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)
                                  ->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $registrationModel = new EventRegistrationModel();
        
        // Get POST data
        $eventId = $this->request->getPost('event_id');
        $userId = $this->request->getPost('user_id');
        $isAttended = $this->request->getPost('is_attended') ? 1 : 0;

        // Validate input
        if (empty($eventId) || empty($userId)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Missing event_id or user_id',
                'csrf_hash' => csrf_hash()
            ]);
        }

        try {
            // Find the registration record
            $registration = $registrationModel
                ->where('event_id', $eventId)
                ->where('user_id', $userId)
                ->first();

            if (!$registration) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Registration record not found',
                    'csrf_hash' => csrf_hash()
                ]);
            }

            // Update the attendance status
            $updateData = [
                'is_attended' => $isAttended,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $registrationModel->update($registration['id'], $updateData);

            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Attendance updated successfully',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                // Log the error for debugging
                log_message('error', 'Failed to update attendance for registration ID: ' . $registration['id']);
                
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update database',
                    'csrf_hash' => csrf_hash()
                ]);
            }

        } catch (\Exception $e) {
            // Log the exception
            log_message('error', 'Exception in updateAttendance: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    /**
     * Save all attendance data from the form (POST request)
     */
    public function saveAttendance()
    {
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel();
        $organizerId = session()->get('id');

        // Get the data from the form
        $eventId = $this->request->getPost('event_id');
        $all_attendance = $this->request->getPost('attendance');

        // --- Validation and Security ---
        if (empty($eventId) || empty($all_attendance)) {
            return redirect()->back()->with('error', 'No event or attendance data submitted.');
        }

        // Security Check: Verify the organizer owns this event
        $event = $eventModel->where('id', $eventId)
                            ->where('organizer_id', $organizerId)
                            ->first();

        if (!$event) {
            return redirect()->back()->with('error', 'You do not have permission to modify this event.');
        }

        // --- Process Updates in a Transaction ---
        $db = \Config\Database::connect();
        $db->transStart(); // Start a transaction

        try {
            // $all_attendance is an array like [ 'user_id' => 'is_attended' ]
            // Example: [ '4' => '1', '10' => '0' ]
            foreach ($all_attendance as $user_id => $is_attended) {
                
                // We send 1 (for checked) or 0 (for unchecked)
                $status = ($is_attended == '1') ? 1 : 0; 

                // Update the record
                $registrationModel
                    ->where('event_id', $eventId)
                    ->where('user_id', $user_id)
                    ->set('is_attended', $status)
                    ->update();
            }
            
            $db->transComplete(); // Commit the transaction

        } catch (\Exception $e) {
            // Something went wrong, roll back
            log_message('error', '[saveAttendance] Error: ' . $e->getMessage());
            return redirect()->to(site_url('organizer/participants') . '?event_id=' . $eventId)
                             ->with('error', 'An error occurred while saving.');
        }

        // Success! Redirect back to the participants page
        return redirect()->to(site_url('organizer/participants') . '?event_id=' . $eventId)
                         ->with('success', 'Attendance saved successfully!');
    }
}