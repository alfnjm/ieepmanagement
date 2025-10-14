<?php

namespace App\Models;

use CodeIgniter\Model;

class PendingOrganizerModel extends Model
{
    protected $table = 'pending_organizers'; // 👈 match your database table name
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'staff_id',
        'created_at'
    ];

    // Automatically handle created_at timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
}
