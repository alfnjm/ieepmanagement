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

    public function certificates()
    {
        $organizerId = session()->get('id');
        $db = \Config\Database::connect();
        $eventModel = new \App\Models\EventModel(); 

        // 1. Get organizer's events
        $organizerEvents = $eventModel->where('organizer_id', $organizerId)->findAll();
        $eventIds = array_column($organizerEvents, 'id');

        if (empty($eventIds)) {
            $data['certificates_issued'] = [];
            return view('organizer/certificates', $data);
        }

        // 2. Get registrations for these events WHERE attendance was marked
        $certificates_issued = $db->table('registrations')
            // Select the registration ID for the link
            ->select('registrations.id as reg_id, users.name as user_name, users.student_id, events.title as event_name')
            ->join('users', 'users.id = registrations.user_id')
            ->join('events', 'events.id = registrations.event_id')
            ->whereIn('registrations.event_id', $eventIds)
            ->where('registrations.certificate_ready', 1) // <-- The key filter
            ->get()
            ->getResultArray();

        $data['certificates_issued'] = $certificates_issued;
        return view('organizer/certificates', $data);
    }

    // NEW FUNCTION: Allows organizer to view a specific student's cert
    public function viewCertificate($registrationId)
    {
        $organizerId = session()->get('id');
        if (!$organizerId) {
            return redirect()->to('/auth/login')->with('error', 'Please log in.');
        }

        $db = \Config\Database::connect();

        // 1. Get registration details and verify organizer ownership
        $registration = $db->table('registrations')
            ->select('registrations.*, events.organizer_id')
            ->join('events', 'events.id = registrations.event_id')
            ->where('registrations.id', $registrationId)
            ->get()
            ->getRowArray();

        // 2. Security Check:
        //    - Does registration exist?
        //    - Does this event belong to this organizer?
        //    - Is the certificate actually ready?
        if (empty($registration) || $registration['organizer_id'] != $organizerId) {
            return redirect()->to('organizer/certificates')->with('error', 'You do not have permission to view this certificate.');
        }
        if ($registration['certificate_ready'] != 1) {
            return redirect()->to('organizer/certificates')->with('error', 'This certificate is not yet ready.');
        }

        // 3. Fetch User and Event Details
        $userModel = new \App\Models\UserModel();
        $eventModel = new \App\Models\EventModel();
        
        $user = $userModel->find($registration['user_id']);
        $event = $eventModel->find($registration['event_id']);

        if (empty($user) || empty($event)) {
            return redirect()->back()->with('error', 'User or Event data missing.');
        }

        // 4. Render the *same* certificate view as the student
        $data = [
            'userName' => $user['name'],
            'userId' => $user['student_id'] ?? $user['id'], 
            'eventTitle' => $event['title'],
            'eventDate' => $event['date'],
            // Assumes 'cert.png' is in 'public/images/'
            'certImagePath' => base_url('images/cert.png') 
        ];
        
        // We reuse the student's certificate template
        return view('user/certificate_view', $data);
    }

    public function attendance()
    {
    // ... (omitted security/login check)
    $organizerId = session()->get('id'); 
    $db = \Config\Database::connect();
    $registrationModel = new \App\Models\RegistrationModel();
    $eventModel = new \App\Models\EventModel(); 
    
    // Handle POST request to update attendance
    if ($this->request->getMethod() === 'post') {
        $formData = $this->request->getPost();
        $updates = $formData['updates'] ?? []; // Array of 'user_id_event_id' values checked

        // Fetch all registrations for this organizer's events to find those to unset.
        $organizerEvents = $eventModel->where('organizer_id', $organizerId)->findAll();
        $eventIds = array_column($organizerEvents, 'id');
        
        if (!empty($eventIds)) {
            $allRegs = $registrationModel->whereIn('event_id', $eventIds)->findAll();

            foreach ($allRegs as $reg) {
                $uniqueKey = $reg['user_id'] . '_' . $reg['event_id'];
                $isAttended = in_array($uniqueKey, $updates) ? 1 : 0;
                
                // Only update if status has changed
                if ((int)$reg['certificate_ready'] !== $isAttended) {
                    $registrationModel->update($reg['id'], ['certificate_ready' => $isAttended]);
                }
            }
        }
        
        return redirect()->to('organizer/attendance')->with('success', 'Attendance updated successfully.');
    }

    // Fetch the organizer's *approved* events first
    $organizerEvents = $eventModel->where('organizer_id', $organizerId)->findAll();
    $eventIds = array_column($organizerEvents, 'id');
    
    if (empty($eventIds)) {
        $data['participants'] = [];
        return view('organizer/attendance', $data);
    }

    // Fetch registrations for these events, joining with user data
    $participants = $db->table('registrations')
                       ->select('registrations.id, registrations.user_id, registrations.event_id, registrations.certificate_ready, users.name as user_name, users.student_id, events.title as event_name')
                       ->join('users', 'users.id = registrations.user_id')
                       ->join('events', 'events.id = registrations.event_id')
                       ->whereIn('registrations.event_id', $eventIds)
                       ->get()
                       ->getResultArray();

    $data['participants'] = $participants;
    return view('organizer/attendance', $data);
    }

    public function participants()
    {
        $organizerId = session()->get('id'); 
        $db = \Config\Database::connect();
        $eventModel = new \App\Models\EventModel(); 

        // 1. Fetch the organizer's *approved* events first
        $organizerEvents = $eventModel->where('organizer_id', $organizerId)->findAll();
        $eventIds = array_column($organizerEvents, 'id');
        
        if (empty($eventIds)) {
            // If the organizer has no events, send an empty array
            $data['participants'] = [];
            return view('organizer/participants', $data);
        }

        // 2. Fetch registrations for these events, joining with user and event data
        $participants = $db->table('registrations')
                           ->select('registrations.id, users.name as user_name, users.student_id, users.email, events.title as event_name')
                           ->join('users', 'users.id = registrations.user_id')
                           ->join('events', 'events.id = registrations.event_id')
                           ->whereIn('registrations.event_id', $eventIds)
                           ->get()
                           ->getResultArray();

        // 3. Pass the data to the view
        $data['participants'] = $participants;
        return view('organizer/participants', $data);
    }
}
