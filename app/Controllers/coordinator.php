<?php

namespace App\Controllers;

use App\Models\PendingOrganizerModel;
use App\Models\UserModel;
use App\Models\PendingProposalModel;
use App\Models\EventModel; // <<< ADDED: Import the live Event Model

class Coordinator extends BaseController
{
    public function dashboard()
    {
        $pendingModel = new PendingOrganizerModel();
        $data['pendingOrganizers'] = $pendingModel->findAll();

        $data['stats'] = [
            'ongoing'   => 3,
            'upcoming'  => 2,
            'organizer' => 5,
            'attendance'=> 120
        ];

        return view('coordinator/dashboard', $data);
    }

    // ✅ Added this method
    public function approvals()
    {
        $pendingModel = new PendingOrganizerModel();
        $data['pendingOrganizers'] = $pendingModel->findAll();

        return view('coordinator/approvals', $data);
    }

    public function approve($id)
    {
        $pendingModel = new PendingOrganizerModel();
        $userModel = new UserModel();

        $pending = $pendingModel->find($id);
        if (!$pending) {
            return redirect()->back()->with('error', 'Organizer not found.');
        }

        $userModel->insert([
            'name'     => $pending['name'],
            'email'    => $pending['email'],
            'password' => $pending['password'], // already hashed
            'role'     => 'organizer',
            'staff_id' => $pending['staff_id'],
        ]);

        $pendingModel->delete($id);

        return redirect()->back()->with('success', 'Organizer approved successfully.');
    }

    public function reject($id)
    {
        $pendingModel = new PendingOrganizerModel();

        $pending = $pendingModel->find($id);
        if (!$pending) {
            return redirect()->back()->with('error', 'Organizer not found.');
        }

        $pendingModel->delete($id);

        return redirect()->back()->with('success', 'Organizer registration rejected and removed.');
    }

    public function proposals()
    {
        $pendingModel = new PendingProposalModel();
        $data['pendingProposals'] = $pendingModel->findAll();
        return view('coordinator/proposals', $data);
    }

    // ⭐ FIX: This method now MOVES the event to the live EventModel table.
    public function approveProposal($id)
    {
        $proposalModel = new PendingProposalModel();
        $eventModel = new EventModel(); // Instantiate the live event model

        $proposal = $proposalModel->find($id);

        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        // 1. Prepare data for the live events table (MAPPING FIX)
        $eventData = [
            // Mapped Fields
            'title'              => $proposal['event_name'], 
            'description'        => $proposal['event_description'], 
            'thumbnail'          => $proposal['poster_image'], 
            'date'               => $proposal['event_date'],
            'location'           => $proposal['event_location'],
            
            // Directly Transferred Fields
            'organizer_id'       => $proposal['organizer_id'],
            'status'             => 'approved',
            'time'               => $proposal['event_time'],
            'program_start'      => $proposal['program_start'],
            'program_end'        => $proposal['program_end'],
            'eligible_semesters' => $proposal['eligible_semesters'],
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        try {
            // 2. Insert the mapped data into the main events table (EventModel)
            $eventModel->insert($eventData);
            
            // 3. Delete the record from the pending proposals table
            $proposalModel->delete($id);

            return redirect()->back()->with('success', 'Event approved and published to the main page successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Event Approval Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve event due to a database error. Check logs and model fields.');
        }
    }

    public function rejectProposal($id)
    {
        $model = new PendingProposalModel();
        $proposal = $model->find($id);

        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        // Simply deleting the pending record
        $model->delete($id); 
        return redirect()->back()->with('success', 'Proposal rejected and removed.');
    }

    public function registrationControl()
    {
        // Now fetches from the live events table for approved events
        $eventModel = new EventModel();
        $data['approvedEvents'] = $eventModel->getApprovedEvents();

        return view('coordinator/registration_control', $data);
    }

    public function upcomingEvents()
    {
        // 1. Fetch upcoming events (approved)
        $eventModel = new \App\Models\EventModel();

        // --- FIX ---
        // Replaced getApprovedEvents() with a direct query for 'approved' status
        $data['events'] = $eventModel
            ->where('status', 'approved')
            ->orderBy('date', 'ASC') // Show soonest events first
            ->findAll();
        // --- End Fix ---

        // 2. Pass to the view
        return view('coordinator/upcoming_events', $data);
    }
}
