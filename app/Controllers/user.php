<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\RegistrationModel;

class User extends BaseController
{
    public function dashboard()
    {

        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        $userId = session()->get('id');

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
        $registrationModel = new RegistrationModel();
        $registrationModel->insert([
            'user_id' => session()->get('id'),
            'event_id' => $eventId,
            'certificate_ready' => 0
        ]);

        return redirect()->to('user/dashboard')->with('success', 'Successfully registered for the event.');
    }

    public function printCertificate($eventId)
    {
        return "Certificate printing for event ID: " . $eventId;
    }
}
