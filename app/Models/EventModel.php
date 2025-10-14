<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
<<<<<<< HEAD
    protected $table        = 'events';
    protected $primaryKey = 'id';

    // ⭐ FIX: Added 'organizer_id', 'status', 'created_at', and 'updated_at' 
    // to match all expected fields from the PendingProposalModel and timestamps.
    protected $allowedFields = [
        'title', 
        'description', 
        'thumbnail', 
        'date', 
        'time', // Assuming 'time' might also be a field in the proposal
        'location',
        'organizer_id', // CRITICAL: Missing field from the proposal
        'status', // CRITICAL: To save the 'approved' status
        'created_at', // CRITICAL: Needed because $useTimestamps is true
        'updated_at', // CRITICAL: Needed because $useTimestamps is true
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
=======
    protected $table      = 'events';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title', 'description', 'thumbnail', 'date', 'location'
    ];

    protected $useTimestamps = true;
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
}
