<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\PendingProposalModel;
use App\Models\EventRegistrationModel;
use App\Models\UserModel;

class Organizer extends BaseController
{
    // ... (dashboard, createEvent, submitProposal, myProposals methods are unchanged) ...
    public function dashboard()
    {
        $eventModel = new EventModel();
        $proposalModel = new PendingProposalModel();
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

    public function createEvent()
    {
        $data['title'] = 'Create Event Proposal';
        return view('organizer/create_event', $data);
    }

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
        $model = new PendingProposalModel();
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

    public function myProposals()
    {
        $proposalModel = new PendingProposalModel();
        $userId = session()->get('id');
        $data['proposals'] = $proposalModel->where('organizer_id', $userId)->findAll();
        $data['title'] = 'My Proposals';
        return view('organizer/my_proposals', $data);
    }
    
    /**
     * This function just loads the page (GET request)
     */
    public function participants()
    {
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel();
        $userModel = new UserModel();
        
        $organizerId = session()->get('id');
        
        $data['selected_event'] = null;
        $data['participants'] = [];

        $data['events'] = $eventModel->where('organizer_id', $organizerId)
                                    ->where('status', 'approved')
                                    ->findAll();

        $selectedEventId = $this->request->getGet('event_id');

        if ($selectedEventId) {
            $data['selected_event'] = $selectedEventId; 
            
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
     * THIS IS THE FUNCTION YOUR VIEW NEEDS.
     * It handles the JavaScript 'fetch' request.
     */
    public function updateAttendance()
    {
        // Check if it's an AJAX request for security
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $registrationModel = new EventRegistrationModel();
        
        $eventId = $this->request->getPost('event_id');
        $userId = $this->request->getPost('user_id');
        $isAttended = $this->request->getPost('is_attended') ? 1 : 0;

        // A quick check to make sure we have the data we need
        if (empty($eventId) || !is_numeric($userId)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Missing data.']);
        }

        // Find the specific registration record to update
        $registration = $registrationModel
            ->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if ($registration) {
            // Use the primary key ('id') to update
            $registrationModel->update($registration['id'], ['is_attended' => $isAttended]);
            
            // Send a success response back to the JavaScript
            return $this->response->setJSON([
                'status' => 'success',
                'csrf_hash' => csrf_hash() 
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Participant not found.',
            'csrf_hash' => csrf_hash()
        ]);
    }
}

