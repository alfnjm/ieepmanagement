<?php

namespace App\Controllers;

use App\Models\PendingProposalModel;
use CodeIgniter\Controller;

class Organizer extends BaseController
{
    // 1️⃣ Organizer Dashboard
    public function dashboard()
    {
        $data['stats'] = [
            'events'       => 3,
            'proposals'    => 2,
            'participants' => 120,
            'certificates' => 15
        ];

        return view('organizer/dashboard', $data);
    }

    // 2️⃣ Create Event Page
    public function createEvent()
    {
        return view('organizer/create_event');
    }

    public function submitProposal()
    {
        $proposalModel = new \App\Models\PendingProposalModel();

        $poster = $this->request->getFile('poster_image');
        $proposal = $this->request->getFile('proposal_file');

        $posterName = null;
        $proposalName = null;

        // Upload poster
        if ($poster && $poster->isValid() && !$poster->hasMoved()) {
            $posterName = $poster->getRandomName();
            $poster->move('uploads/posters', $posterName);
        }

        // Upload proposal document
        if ($proposal && $proposal->isValid() && !$proposal->hasMoved()) {
            $proposalName = $proposal->getRandomName();
            $proposal->move('uploads/proposals', $proposalName);
        }

        // Combine semesters
        $eligibleSemesters = implode(', ', $this->request->getPost('eligible_semesters') ?? []);

        // Save proposal into PendingProposal table
        $proposalModel->insert([
            'event_name'        => $this->request->getPost('event_name'),
            'event_date'        => $this->request->getPost('event_date'),
            'event_time'        => $this->request->getPost('event_time'),
            'event_location'    => $this->request->getPost('event_location'),
            'eligible_age'      => $this->request->getPost('eligible_age'),
            'eligible_semesters'=> $eligibleSemesters,
            'event_description' => $this->request->getPost('event_description'),
            'poster_image'      => $posterName,
            'proposal_file'     => $proposalName,
            'status'            => 'Pending',
        ]);

        // Redirect to coordinator approvals
        return redirect()->to(base_url('coordinator/proposals'))
                        ->with('success', 'Event proposal submitted and sent for coordinator approval.');
    }

    public function myProposals()
    {
        $proposalModel = new \App\Models\PendingProposalModel();

        // Example: You can filter by logged-in organizer (if session used)
        //$organizerId = session()->get('user_id');
        //$data['myProposals'] = $proposalModel->where('staff_id', $organizerId)->findAll();

        // For now, show all proposals
        $data['myProposals'] = $proposalModel->findAll();

        return view('organizer/my_proposals', $data);
    }

    // 5️⃣ Participants / Registrations
    public function participants()
    {
        return view('organizer/participants');
    }

    // 6️⃣ Certificates Page
    public function certificates()
    {
        return view('organizer/certificates');
    }

    // 7️⃣ Attendance Page
    public function attendance()
    {
        return view('organizer/attendance');
    }
}
