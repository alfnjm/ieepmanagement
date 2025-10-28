<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\RegistrationModel;
use App\Models\UserModel; // <--- ADDED THIS LINE

class User extends BaseController
{
    public function dashboard()
    {

        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        $userId = session()->get('id');

        // Note: Ideally, this should use EventModel::getApprovedEvents()
        $events = $eventModel->findAll(); 
        $userRegs = $registrationModel->where('user_id', $userId)->findAll();

        $registeredEvents = [];
        foreach ($userRegs as $reg) {
            $registeredEvents[$reg['event_id']] = $reg;
        }

        return view('user/dashboard', [
            'events' => $events,
            'registeredEvents' => $registeredEvents
        ]);
    }

    public function registerEvent($eventId)
    {
        $session = session();

        // ✅ Check if user is logged in
        if (!$session->has('id')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $userId = $session->get('id'); // now guaranteed to exist
        $registrationModel = new RegistrationModel();

        // ✅ Optional: prevent duplicate registrations
        $existing = $registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if ($existing) {
            return redirect()
                ->to('user/dashboard')
                ->with('info', 'You are already registered for this event.');
        }

        // ✅ Insert registration
        $registrationModel->insert([
            'user_id'    => $userId,
            'event_id'   => $eventId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('user/dashboard')
            ->with('success', 'Successfully registered for the event.');
    }

    public function printCertificate($eventId)
    {
        return "Certificate printing for event ID: " . $eventId;
    }

    // Change index to editProfile for clarity, or leave as index() if the route maps to it
    public function editProfile() 
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        // You MUST pass the $user data to the view for it to work.
        // Assuming you have a UserModel to fetch user data:
        // Changed instantiation to use the 'use App\Models\UserModel' statement added above
        $userModel = new UserModel(); 
        $userId = $session->get('id'); // Assuming you store id in session
        $user = $userModel->find($userId);

        return view('user/profile', [ // Adjusted view path
            'title' => 'Edit Profile',
            'user' => $user, // Pass the user data array
            // 'validation' => \Config\Services::validation() // Might be needed for validation
        ]);
    }

    public function updateProfile()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = $session->get('id');
        $userModel = new UserModel(); // <--- This now correctly resolves due to the added 'use' statement

        // 1. Define Validation Rules
        $rules = [
            // 'name' validation rule removed to prevent user changing their name
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
            // Failed validation: reload the form with errors and old input
            $user = $userModel->find($userId); // Fetch current data again to populate the form
            return view('user/profile', [
                'validation' => $this->validator,
                'user' => $user // Pass user data back
            ]);
        }

        // 3. Prepare Data for Update
        $data = [
            // 'name' is intentionally excluded from $data
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'class' => $this->request->getPost('class'),
            // 'updated_at' is handled automatically by the model if $useTimestamps is true
        ];

        // Handle Password Update (only if a new password was provided)
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            // FIX: Pass the RAW password. The UserModel's 'hashPassword' callback will handle hashing it once.
            $data['password'] = $newPassword; 
        }

        // 4. Update the User Record
        if ($userModel->update($userId, $data)) {
            // Update session data (name session update removed)
            $session->set('userEmail', $data['email']);

            return redirect()->to('user/profile')->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->to('user/profile')->with('error', 'Failed to update profile due to a database error.');
        }
    }
}