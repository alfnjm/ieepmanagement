<?php

namespace App\Models;

use CodeIgniter\Model;

// --- FIX ---
// The class name is now 'EventRegistrationModel' to match the file name
// and what the 'organizer.php' controller is looking for.
class EventRegistrationModel extends Model
{
    // --- FIX ---
    // Pointing to the correct 'event_registrations' table
    protected $table = 'event_registrations';
    protected $primaryKey = 'id';

    // --- FIX ---
    // Added all fields from this table
    protected $allowedFields = [
        'user_id',
        'event_id',
        'is_attended',
        'certificate_published',
        'certificate_path'
        // 'created_at' is handled by $useTimestamps
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at field in the migration
}
