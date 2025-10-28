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

    // 3️⃣ Submit Proposal (FULLY FIXED)
    public function submitProposal()
    {
        // ✅ Create model before using it
        $proposalModel = new PendingProposalModel();

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

        // Get logged-in organizer's ID
        $organizerId = session()->get('id');

        // Insert into database
        $proposalModel->insert([
            'event_name'         => $this->request->getPost('event_name'),
            'event_date'         => $this->request->getPost('event_date'),
            'event_time'         => $this->request->getPost('event_time'),
            'event_location'     => $this->request->getPost('event_location'),
            'program_start'      => $this->request->getPost('program_start'),
            'program_end'        => $this->request->getPost('program_end'),
            'eligible_semesters' => $eligibleSemesters,
            'event_description'  => $this->request->getPost('event_description'),
            'poster_image'       => $posterName,
            'proposal_file'      => $proposalName,
            'status'             => 'Pending',
            'organizer_id'       => $organizerId
        ]);

        return redirect()->to(base_url('organizer/myProposals'))
                 ->with('success', 'Event proposal submitted successfully and pending approval.');
    }

    // 4️⃣ My Proposals (unchanged)
    public function myProposals()
    {
        $proposalModel = new \App\Models\PendingProposalModel();
        $eventModel    = new \App\Models\EventModel();

        $organizerId = session()->get('id');
        if (!$organizerId) {
            return view('organizer/my_proposals', ['myProposals' => []]);
        }

        // Get Pending or Rejected Proposals
        $otherProposals = $proposalModel
            ->select('id, event_name, created_at, status, proposal_file')
            ->where('organizer_id', $organizerId)
            ->findAll();

        // Get Approved Events from the main events table
        $approvedEvents = $eventModel
            ->select('id, title as event_name, created_at, status, "N/A" as proposal_file')
            ->where('organizer_id', $organizerId)
            ->findAll();

        // Combine both arrays
        $allProposals = array_merge($otherProposals, $approvedEvents);

        // Sort by date (newest first)
        usort($allProposals, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $data['myProposals'] = $allProposals;

        return view('organizer/my_proposals', $data);
    }

    public function participants()
    {
        return view('organizer/participants');
    }

    public function certificates()
    {
        return view('organizer/certificates');
    }

    public function attendance()
    {
        return view('organizer/attendance');
    }
}
