<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRegistrationModel extends Model
{
    protected $table = 'event_registrations';
    protected $primaryKey = 'id';

    // --- FIX 1: ADD 'updated_at' TO ALLOWED FIELDS ---
    protected $allowedFields = [
        'user_id',
        'event_id',
        'is_attended',
        'certificate_published',
        'certificate_path',
        'created_at', // Added for safety
        'updated_at'  // This is required for updates
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    
    // --- FIX 2: TELL THE MODEL THE CORRECT COLUMN NAME ---
    // This must be 'updated_at', not ''
    protected $updatedField  = 'updated_at';
}

