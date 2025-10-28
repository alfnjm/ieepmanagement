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
        // 🔍 Step 1: Dump session for debugging (remove after testing)
        // dd(session()->get());

        // ✅ Use the correct key (based on your Auth controller)
        // In your Auth.php → session()->set(['id' => $user['id'], ...])
        // So we should use 'id' — not 'user_id' or 'student_id'
        $userId = session()->get('user_id');

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

    // 📄 Show event details
    public function detail($eventId)
    {
        $event = $this->eventModel->find($eventId);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event not found");
        }

        return view('events/detail', ['event' => $event]);
    }
}
