<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table        = 'events';
    protected $primaryKey = 'id';

    // ⭐ FIX: Added 'organizer_id', 'status', 'created_at', and 'updated_at' 
    // to match all expected fields from the PendingProposalModel and timestamps.
    protected $allowedFields = [
        'title', 
        'description', 
        'thumbnail', 
        'date', 
        'location',
        'organizer_id', 
        'status', 
        'time', 
        'program_start',      
        'program_end',        
        'eligible_semesters', 
        'created_at', 
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Utility function to get live events for the main page
    public function getApprovedEvents()
    {
        return $this->where('status', 'approved')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }
}
