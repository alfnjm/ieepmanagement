<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\RegistrationModel;

class Events extends BaseController
{
    protected $eventModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->registrationModel = new RegistrationModel();
    }

    // 🗓️ Show all upcoming events
    public function index()
    {
        $userId = session()->get('id'); // ✅ Correct session key
        $events = $this->eventModel->findAll();

        // Find which events this user has registered for
        $registrations = [];
        if ($userId) {
            $userRegs = $this->registrationModel->where('user_id', $userId)->findAll();
            foreach ($userRegs as $reg) {
                $registrations[$reg['event_id']] = true;
            }
        }

        return view('events/index', [
            'events' => $events,
            'registrations' => $registrations
        ]);
    }

    // ✅ Register for an event
    public function register($eventId)
    {
        $userId = session()->get('id');

        // 🧩 Step 2: If not logged in, redirect
        if (!$userId) {
            return redirect()->to('/auth/login')
                ->with('error', 'You must be logged in to register for an event.');
        }

        // 🧠 Step 3: Prevent duplicate registrations
        $exists = $this->registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if ($exists) {
            return redirect()->to('/events')
                ->with('info', 'You are already registered for this event.');
        }

        // 📝 Step 4: Insert registration record
        $this->registrationModel->insert([
            'user_id'    => $userId,
            'event_id'   => $eventId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // ✅ Step 5: Redirect back with success message
        return redirect()->to('/events')
            ->with('success', 'You have successfully registered for the event.');
    }

    // ❌ Cancel registration
    public function cancel($eventId)
    {
        $userId = session()->get('id'); // ✅ Correct session key
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must be logged in.');
        }

        $this->registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->delete();

        return redirect()->to('/events')->with('success', 'Registration cancelled.');
    }

    public function detail($id)
    {
        $eventModel = new \App\Models\EventModel();
        $event = $eventModel->find($id);

        if (empty($event) || $event['status'] !== 'approved') {
            // Don't show pending or non-existent events
            return redirect()->to('/')->with('error', 'Event not found.');
        }

        $data['event'] = $event;

        // We also check if the user is registered for it
        $data['isRegistered'] = false;
        if (session()->get('isLoggedIn')) {
            $registrationModel = new \App\Models\RegistrationModel();
            $userId = session()->get('id');            
            $registration = $registrationModel
                ->where('user_id', $userId)
                ->where('event_id', $id)
                ->first();
            
            if ($registration) {
                $data['isRegistered'] = true;
            }
        }
        
        return view('events/detail', $data);
    }

    public function view($id)
    {
        $eventModel = new \App\Models\EventModel(); // Make sure you have this model
        $event = $eventModel->find($id);

        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event not found");
        }

        return view('event_detail', ['event' => $event]);
    }
    
}
