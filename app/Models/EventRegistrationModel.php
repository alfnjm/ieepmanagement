<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRegistrationModel extends Model
{
    protected $table = 'event_registrations';
    protected $primaryKey = 'id';

    // Enable automatic timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // --- THIS IS THE FIX ---
    // Add the two new certificate fields to this array.
    protected $allowedFields = [
        'event_id',
        'user_id',
        'is_attended',
        'created_at',
        'updated_at',
        'certificate_published',  // <-- ADD THIS LINE
        'certificate_path'        // <-- ADD THIS LINE
    ];
    // --- END OF FIX ---


    // Validation Rules
    protected $validationRules = [
        'event_id' => 'required|integer',
        'user_id'  => 'required|integer',
        'is_attended' => 'permit_empty|in_list[0,1]',
    ];

    
    /**
     * Helper method to get detailed participant list for an event
     */
    public function getParticipantsByEvent($eventId)
    {
        return $this
            ->select('event_registrations.*, users.name, users.email, users.student_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->where('event_registrations.event_id', $eventId)
            ->findAll();
    }
}