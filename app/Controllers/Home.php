<?php

namespace App\Controllers;

// Make sure to import the EventModel where the approved events live
use App\Models\EventModel; 
use App\Models\RegistrationModel; // Assuming you have a RegistrationModel for user data

class Home extends BaseController
{
    public function index()
    {
        // 1. Fetch all approved events from the live table
        $eventModel = new EventModel();

        // --- FIX ---
        // The method 'getApprovedEvents()' does not exist in your EventModel.
        // We will use a direct query to get approved events instead.
        $data['events'] = $eventModel
            ->where('status', 'approved')
            ->orderBy('date', 'DESC') // Optional: Show newest events first
            ->findAll();
        // --- End Fix ---


        // 2. Fetch user registration data if logged in
        $registeredEvents = [];
        if (session()->get('isLoggedIn')) {
            $registrationModel = new RegistrationModel();
            $userId = session()->get('id');
            
            // This part is great! It checks all registrations for the current user.
            $userRegistrations = $registrationModel->where('user_id', $userId)->findAll();
            
            // Map registrations to event IDs for quick lookup in the view
            foreach ($userRegistrations as $reg) {
                // We'll store the *whole registration* in case we need the certificate status
                $registeredEvents[$reg['event_id']] = $reg;
            }
        }
        $data['registeredEvents'] = $registeredEvents;

        // 3. Pass data to the view.
        return view('home', $data); 
    }
}