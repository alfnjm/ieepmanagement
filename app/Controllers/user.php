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
        $registrationModel = new RegistrationModel();
        
        // ⭐ CRITICAL FIX APPLIED: Set createdField to false temporarily.
        // This is a necessary trick when the CodeIgniter query builder adds a 
        // trailing comma (which generates the SQL syntax error) due to internal 
        // conflicts with $useTimestamps = true.
        $registrationModel->setCreatedField(false);
        $registrationModel->setUpdatedField(false);

        $registrationModel->insert([
            'user_id' => session()->get('id'),
            'event_id' => $eventId,
            'certificate_ready' => 0,
            // Re-introducing manual timestamps to ensure they are inserted correctly 
            // now that the model's auto-logic is temporarily disabled for this query.
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('user/dashboard')->with('success', 'Successfully registered for the event.');
    }

    public function printCertificate($eventId)
    {
        return "Certificate printing for event ID: " . $eventId;
    }

    
}
