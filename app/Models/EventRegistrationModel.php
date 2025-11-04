<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRegistrationModel extends Model
{
    protected $table = 'event_registrations';
    protected $primaryKey = 'id';

    // All fields that can be mass-assigned
    protected $allowedFields = [
        'user_id',
        'event_id',
        'is_attended',
        'certificate_published',
        'certificate_path',
        'certificate_ready',
        'created_at',
        'updated_at'
    ];

    // Enable timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Data types
    protected $returnType = 'array';
    
    // Validation rules (optional but recommended)
    protected $validationRules = [
        'user_id'  => 'required|integer',
        'event_id' => 'required|integer',
    ];

    /**
     * Get all participants for a specific event
     */
    public function getEventParticipants($eventId)
    {
        return $this->select('event_registrations.*, users.name, users.student_id, users.email')
                    ->join('users', 'users.id = event_registrations.user_id')
                    ->where('event_registrations.event_id', $eventId)
                    ->findAll();
    }

    /**
     * Get all attended participants for an event
     */
    public function getAttendedParticipants($eventId)
    {
        return $this->select('event_registrations.*, users.name, users.student_id, users.email')
                    ->join('users', 'users.id = event_registrations.user_id')
                    ->where('event_registrations.event_id', $eventId)
                    ->where('event_registrations.is_attended', 1)
                    ->findAll();
    }

    /**
     * Update attendance status
     */
    public function updateAttendanceStatus($registrationId, $isAttended)
    {
        return $this->update($registrationId, [
            'is_attended' => $isAttended ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if user is registered for event
     */
    public function isUserRegistered($userId, $eventId)
    {
        return $this->where('user_id', $userId)
                    ->where('event_id', $eventId)
                    ->first() !== null;
    }
}