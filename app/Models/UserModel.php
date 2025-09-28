<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'email', 'password', 'class', 'student_id', 'phone', 'ic_number'
    ];
    protected $useTimestamps = true;
}
