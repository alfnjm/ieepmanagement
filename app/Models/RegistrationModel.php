<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table      = 'registrations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id', 'event_id', 'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null; // table tak ada updated_at
}
