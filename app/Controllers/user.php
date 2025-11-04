<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventRegistrationModel;
use App\Models\UserModel;

class User extends BaseController
{
    public function dashboard()
    {
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel();
        $userId = session()->get('id');

        $events = $eventModel
            ->where('status', 'approved') // Only show approved events
            ->orderBy('date', 'DESC')
            ->findAll();

        $registeredEvents = [];
        if ($userId) {
            $userRegs = $registrationModel->where('user_id', $userId)->findAll();
            foreach ($userRegs as $reg) {
                $registeredEvents[$reg['event_id']] = $reg;
            }
        }

        return view('user/dashboard', [
            'title' => 'Event Dashboard',
            'events' => $events,
            'registeredEvents' => $registeredEvents
        ]);
    }

    public function registerEvent($eventId)
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $registrationModel = new EventRegistrationModel();

        // Prevent duplicate registrations
        $existing = $registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if ($existing) {
            return redirect()->to('user/dashboard')
                             ->with('info', 'You are already registered for this event.');
        }

        // Insert registration
        $registrationModel->insert([
            'user_id'    => $userId,
            'event_id'   => $eventId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('user/dashboard')
                         ->with('success', 'Successfully registered for the event.');
    }
    
    /**
     * THIS IS THE ONLY FUNCTION YOU NEED FOR THE CERTIFICATE PAGE.
     * It correctly finds certificates that are BOTH attended AND published.
     */
    public function certificates()
    {
        $registrationModel = new EventRegistrationModel();
        $userId = session()->get('id');

        $data['certificates'] = $registrationModel
            ->join('events', 'events.id = event_registrations.event_id')
            ->where('event_registrations.user_id', $userId)
            ->where('event_registrations.is_attended', 1) // <-- MUST be attended
            ->where('event_registrations.certificate_published', 1) // <-- MUST be published
            ->select('events.title as event_name, event_registrations.certificate_path')
            ->findAll();

        $data['title'] = 'My Certificates';
        return view('user/certificate', $data);
    }
    
    public function downloadCertificate($registrationId)
    {
        $session = session();
        if (!$session->has('id')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $userId = $session->get('id');
        $registrationModel = new \App\Models\EventRegistrationModel();

        // 1. Find the registration
        $registration = $registrationModel
            ->where('id', $registrationId)
            ->where('user_id', $userId) // Security: Make sure this user owns this cert
            ->first();

        // 2. Check if it's valid and published
        if (empty($registration) || $registration['certificate_published'] != 1 || empty($registration['certificate_path'])) {
            return redirect()
                ->to('user/certificates')
                ->with('error', 'Certificate not available or not found.');
        }

        // 3. Get the file path
        // Use FCPATH to get the public path, or ROOTPATH if it's in writable
        // We moved it to FCPATH (public/certificates)
        $filePath = FCPATH . $registration['certificate_path'];

        // 4. Check if the file actually exists
        if (!file_exists($filePath)) {
            log_message('error', 'Certificate file not found at path: ' . $filePath);
            return redirect()->back()->with('error', 'Certificate file not found. Please contact the coordinator.');
        }

        // 5. Trigger a force download
        return $this->response->download($filePath, null);
    }

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

        $rules = [
            'email' => "required|max_length[255]|valid_email|is_unique[users.email,id,{$userId}]",
            'phone' => 'required|max_length[15]',
            'class' => 'required|max_length[50]',
        ];

        if ($this->request->getPost('password') !== '') {
            $rules['password'] = 'required|min_length[8]';
            $rules['password_confirm'] = 'required_with[password]|matches[password]';
        }

        if (!$this->validate($rules)) {
            $user = $userModel->find($userId);
            return view('user/profile', [
                'validation' => $this->validator,
                'user' => $user
            ]);
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'class' => $this->request->getPost('class'),
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $data['password'] = $newPassword; // Model will hash it
        }

        if ($userModel->update($userId, $data)) {
            $session->set('userEmail', $data['email']);
            return redirect()->to('user/profile')->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->to('user/profile')->with('error', 'Failed to update profile.');
        }
    }
}