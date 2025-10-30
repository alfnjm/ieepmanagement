<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table = 'registrations';
    protected $primaryKey = 'id';

    // ✅ Only include columns that actually exist in your table
    protected $allowedFields = [
        'user_id',
        'event_id',
        'certificate_ready', // <-- ADDED
        'created_at',
        'updated_at',
        'certificate_path'
    ];

    // ✅ Disable automatic timestamps since we handle created_at manually
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
