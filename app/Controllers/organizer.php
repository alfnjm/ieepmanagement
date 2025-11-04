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
        $userModel = new UserModel();
        
        $organizerId = session()->get('id');
        
        $data['selected_event'] = null;
        $data['participants'] = [];

        // Get all approved events for this organizer
        $data['events'] = $eventModel->where('organizer_id', $organizerId)
                                    ->where('status', 'approved')
                                    ->findAll();

        // Get selected event from query string
        $selectedEventId = $this->request->getGet('event_id');

        if ($selectedEventId) {
            $data['selected_event'] = $selectedEventId; 
            
            // Get all participants for this event with JOIN
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
}