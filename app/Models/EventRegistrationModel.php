<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRegistrationModel extends Model
{
    protected $table = 'event_registrations';
    protected $primaryKey = 'id';

    // FIX 1: Added 'is_attended', 'created_at', and 'updated_at' to allowedFields
    // This allows the model to write to these columns during updates.
    protected $allowedFields = [
        'event_id',
        'user_id',
        'is_attended',
        'created_at',
        'updated_at'
    ];

    // Enable automatic timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    
    // FIX 2: Explicitly set $updatedField to 'updated_at'
    // This tells the model to automatically update this column on save/update.
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'event_id' => 'required|integer',
        'user_id'  => 'required|integer',
        'is_attended' => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Helper method to find registrations by event
     */
    public function getRegistrationsByEvent($eventId)
    {
        return $this->where('event_id', $eventId)->findAll();
    }

    /**
     * Helper method to find registrations by user
     */
    public function getRegistrationsByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }
    
    /**
     * Helper method to get detailed participant list for an event
     */
    public function getParticipantsByEvent($eventId)
    {
        return $this
            ->select('event_registrations.*, users.full_name, users.email, users.student_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->where('event_registrations.event_id', $eventId)
            ->findAll();
    }
}