<?php

namespace App\Controllers;

// Make sure to import the EventModel where the approved events live
use App\Models\EventModel; 
use App\Models\RegistrationModel; // Assuming you have a RegistrationModel for user data

class Home extends BaseController
{
    public function index()
    {
        // 1. Setup models and get today's date
        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();
        $today = date('Y-m-d');

        // --- MODIFICATION ---
        // 2. Fetch all approved UPCOMING events
        $data['upcoming_events'] = $eventModel
            ->where('status', 'approved')
            ->where('date >=', $today) // Events on or after today
            ->orderBy('date', 'ASC')   // Show soonest events first
            ->findAll();

        // 3. Fetch all approved PAST events
        $data['past_events'] = $eventModel
            ->where('status', 'approved')
            ->where('date <', $today)  // Events before today
            ->orderBy('date', 'DESC') // Show most recent past events first
            ->findAll();
        // --- End Modification ---


        // 4. Fetch user registration data if logged in
        $registeredEvents = [];
        if (session()->get('isLoggedIn')) {
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

        // 5. Pass all data to the view.
        return view('home', $data); 
    }
}