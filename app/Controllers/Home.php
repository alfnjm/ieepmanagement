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
        $data['events'] = $eventModel->getApprovedEvents();

        // 2. Fetch user registration data if logged in
        $registeredEvents = [];
        if (session()->get('isLoggedIn')) {
            $registrationModel = new RegistrationModel();
            $userId = session()->get('id');
            
            // Assuming RegistrationModel has a method to get events registered by a user
            $userRegistrations = $registrationModel->where('user_id', $userId)->findAll();
            
            // Map registrations to event IDs for quick lookup in the view
            foreach ($userRegistrations as $reg) {
                $registeredEvents[$reg['event_id']] = true;
            }
        }
        $data['registeredEvents'] = $registeredEvents;

        // 3. Pass data to the view.
        // Replace 'main_page_view' with the actual name of the view file you provided.
        // If your view is 'home_view.php', use 'home_view'.
        return view('main_page_view', $data); 
    }
}
