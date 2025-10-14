<?php

namespace App\Controllers;

<<<<<<< HEAD
use App\Models\PendingOrganizerModel;
use App\Models\UserModel;
use App\Models\PendingProposalModel;
use App\Models\EventModel; // <<< ADDED: Import the live Event Model

=======
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
class Coordinator extends BaseController
{
    public function dashboard()
    {
<<<<<<< HEAD
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

        // 1. Prepare data for the live events table
        // CRITICAL: Unset the ID so the EventModel inserts a NEW record.
        unset($proposal['id']); 
        
        // Ensure status is set to 'approved' for the live table.
        $proposal['status'] = 'approved'; 

        try {
            // 2. Insert the proposal into the main events table (EventModel)
            $eventModel->insert($proposal);
            
            // 3. Delete the record from the pending proposals table
            $proposalModel->delete($id);

            return redirect()->back()->with('success', 'Event approved and published to the main page successfully!');
        } catch (\Exception $e) {
            // This catches the 'There is no data to insert' error if fields are mismatched.
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

    // 📅 View Upcoming Events
    public function upcomingEvents()
    {
        // Now fetches from the live events table
        $eventModel = new EventModel();
        $data['upcomingEvents'] = $eventModel->getApprovedEvents();

        return view('coordinator/upcoming_events', $data);
    }

=======
        $data['title'] = "IEEP Coordinator Dashboard";
        return view('coordinator/dashboard', $data);
    }
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
}
