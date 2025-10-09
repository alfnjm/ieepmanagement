<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'email', 'password', 'class', 'student_id', 'phone', 'ic_number', 'role',
        'created_at', 'updated_at' 
    ];
    
    // --- NEW: ADD VALIDATION RULES ---
    protected $validationRules = [
        'name'     => 'required|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email]|max_length[150]',
        'password' => 'required|min_length[8]',
        'student_id' => 'permit_empty|is_unique[users.student_id]',
        'ic_number'  => 'permit_empty|is_unique[users.ic_number]',
        'role' => 'required|in_list[user,organizer,coordinator]', // Add role validation
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    // --- END NEW VALIDATION ---

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
}