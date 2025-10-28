<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\RegistrationModel;
use App\Models\UserModel; 

class User extends BaseController
{
    public function dashboard()
    {
        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        $userId = session()->get('id');

        // Fetch all events
        $events = $eventModel->findAll(); 
        
        $registeredEvents = [];
        if ($userId) {
             // Fetch all registration records, including certificate_ready status
            $userRegs = $registrationModel->where('user_id', $userId)->findAll();
            foreach ($userRegs as $reg) {
                // Store the whole registration record for certificate check on dashboard
                $registeredEvents[$reg['event_id']] = $reg;
            }
        }

        return view('user/dashboard', [
            'events' => $events,
            'registeredEvents' => $registeredEvents
        ]);
    }

    public function registerEvent($eventId)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->has('id')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $userId = $session->get('id'); 
        $registrationModel = new RegistrationModel();

        // Prevent duplicate registrations
        $existing = $registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if ($existing) {
            return redirect()
                ->to('user/dashboard')
                ->with('info', 'You are already registered for this event.');
        }

        // Insert registration
        $registrationModel->insert([
            'user_id'    => $userId,
            'event_id'   => $eventId,
            // 'certificate_ready' defaults to 0 in the database
            'created_at' => date('Y-m-d H:i:s'), 
        ]);

        return redirect()
            ->to('user/dashboard')
            ->with('success', 'Successfully registered for the event.');
    }
    
    // NEW: User views list of completed events for certificate download
    public function certificates()
    {
        $session = session();
        if (!$session->has('id')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $userId = $session->get('id');
        $db = \Config\Database::connect();

        // Join registrations with events to list only events the user is registered for AND attended
        $registrations = $db->table('registrations')
            ->select('registrations.event_id, registrations.certificate_ready, events.title, events.date')
            ->join('events', 'events.id = registrations.event_id')
            ->where('registrations.user_id', $userId)
            // Filter for only events where attendance (certificate_ready) is marked as 1 (true)
            ->where('registrations.certificate_ready', 1) 
            ->get()
            ->getResultArray();

        return view('user/certificates', [
            'title' => 'My Certificates',
            'certificates' => $registrations,
        ]);
    }

    // COMPLETE FIX: Print certificate logic (replaces the stub)
    public function printCertificate($eventId)
    {
        $session = session();
        if (!$session->has('id')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $userId = $session->get('id');
        $userModel = new UserModel();
        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        // 1. Check Registration and Attendance
        $registration = $registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if (empty($registration) || $registration['certificate_ready'] != 1) {
            return redirect()
                ->to('user/certificates')
                ->with('error', 'Certificate not available. Attendance must be marked by the organizer.');
        }

        // 2. Fetch User and Event Details
        $user = $userModel->find($userId);
        $event = $eventModel->find($eventId);

        if (empty($user) || empty($event)) {
             return redirect()->back()->with('error', 'User or Event data missing for certificate.');
        }

        // 3. Render Certificate View
        $data = [
            'userName' => $user['name'],
            // Use 'student_id' as the custom ID for the certificate, falling back to database ID
            'userId' => $user['student_id'] ?? $user['id'], 
            'eventTitle' => $event['title'],
            'eventDate' => $event['date'],
            // Assuming 'cert.png' is in 'public/images/' as suggested by file list
            'certImagePath' => base_url('images/cert.png')
        ];

        // Renders the HTML template which is styled to look like a certificate and can be printed to PDF by the browser
        return view('user/certificate_view', $data);
    }
    
    // ** Unchanged methods below **
    public function editProfile() 
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userModel = new UserModel(); 
        $userId = $session->get('id'); 
        $user = $userModel->find($userId);

        return view('user/profile', [ 
            'title' => 'Edit Profile',
            'user' => $user, 
        ]);
    }

    public function updateProfile()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = $session->get('id');
        $userModel = new UserModel(); 

        // 1. Define Validation Rules
        $rules = [
            'email' => "required|max_length[255]|valid_email|is_unique[users.email,id,{$userId}]",
            'phone' => 'required|max_length[15]',
            'class' => 'required|max_length[50]',
        ];

        // Check if the user entered a new password
        if ($this->request->getPost('password') !== '') {
            $rules['password'] = 'required|min_length[8]';
            $rules['password_confirm'] = 'required_with[password]|matches[password]';
        }

        // 2. Validate the Request
        if (!$this->validate($rules)) {
            $user = $userModel->find($userId); 
            return view('user/profile', [
                'validation' => $this->validator,
                'user' => $user 
            ]);
        }

        // 3. Prepare Data for Update
        $data = [
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'class' => $this->request->getPost('class'),
        ];

        // Handle Password Update (only if a new password was provided)
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $data['password'] = $newPassword; 
        }

        // 4. Update the User Record
        if ($userModel->update($userId, $data)) {
            $session->set('userEmail', $data['email']);

            return redirect()->to('user/profile')->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->to('user/profile')->with('error', 'Failed to update profile due to a database error.');
        }
    }
}