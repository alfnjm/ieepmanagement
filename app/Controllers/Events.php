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

    public function index()
    {
        $userId = session()->get('user_id'); // assume user login stored here
        $events = $this->eventModel->findAll();

        // registrations by user
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

    public function register($eventId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must be logged in.');
        }

        $exists = $this->registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if (!$exists) {
            $this->registrationModel->insert([
                'user_id' => $userId,
                'event_id' => $eventId
            ]);
        }

        return redirect()->to('/events')->with('success', 'Registered successfully');
    }

    public function cancel($eventId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must be logged in.');
        }

        $this->registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->delete();

        return redirect()->to('/events')->with('success', 'Registration cancelled');
    }

    public function detail($eventId)
    {
        $event = $this->eventModel->find($eventId);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event not found");
        }

        return view('events/detail', ['event' => $event]);
    }
}
