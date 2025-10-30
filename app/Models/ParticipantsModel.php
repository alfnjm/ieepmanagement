<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipantsModel extends Model
{
    protected $table = 'participants';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'event_id', 'certificate_ready', 'created_at', 'updated_at'];

    // Get all participants for a specific organizer’s events
    public function getParticipantsForOrganizer($organizerId)
    {
        return $this->select('participants.*, events.title as event_title, users.name, users.student_id')
                    ->join('events', 'events.id = participants.event_id')
                    ->join('users', 'users.id = participants.user_id')
                    ->where('events.organizer_id', $organizerId)
                    ->findAll();
    }

    // Update attendance (certificate_ready)
    public function updateAttendance($attendanceData)
    {
        foreach ($attendanceData as $id => $attended) {
            $this->update($id, [
                'certificate_ready' => $attended ? 1 : 0
            ]);
        }
    }
}
