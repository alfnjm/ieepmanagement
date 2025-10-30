<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\PendingProposalModel;
use App\Models\EventRegistrationModel; // --- ADDED THIS LINE BACK ---
use App\Models\UserModel;

class Organizer extends BaseController
{
    // ... dashboard() and createEvent() methods are unchanged ...
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

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'date' => 'required|valid_date',
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

        $posterFile->move(ROOTPATH . 'public/uploads/posters', $posterName);
        $proposalFile->move(ROOTPATH . 'public/uploads/proposals', $proposalName);

        $model = new PendingProposalModel();
        $model->save([
            'organizer_id' => $organizerId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'date' => $this->request->getPost('date'),
            'poster_path' => 'uploads/posters/' . $posterName,
            'proposal_path' => 'uploads/proposals/' . $proposalName,
        ]);

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

