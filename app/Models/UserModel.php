<?php
// app/Models/UserModel.php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'email', 'password', 'role', 
        'class', 'student_id', 'phone', 'ic_number',
        'staff_id', // <--- ADDED STAFF_ID
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Disable automatic validation (using controller validation instead)
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = true; 

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // ... (hashPassword function remains the same) ...
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }


    // ----------------------------------------------------------------------
    // REMOVE all custom validation functions (validateCreate, validateUpdate,
    // validateStudentFields) and handle all validation in the Controller.
    // The controller is where you have access to the $this->request->getPost('role') 
    // for dynamic validation.
    // ----------------------------------------------------------------------

    /**
     * Get user counts by role (Keep this)
     */
    public function getUserCountByRole()
    {
        $builder = $this->db->table('users');
        $builder->select('role, COUNT(*) as count');
        $builder->groupBy('role');
        $result = $builder->get()->getResultArray();

        $counts = [
            'user' => 0,
            'admin' => 0,
            'coordinator' => 0,
            'organizer' => 0
        ];

        foreach ($result as $row) {
            $counts[$row['role']] = (int)$row['count'];
        }

        return $counts;
    }
    
    // ... (Keep all other utility functions: getUsersByRole, findByEmail, verifyPassword,
    //      getPaginatedUsers, searchUsers, updateUser, isEmailUnique, getTotalUsers, 
    //      getRecentUsers) ...
    
    // ... (Note: You should remove the redundant/unused custom validation functions
    //      like validateCreate, validateUpdate, validateStudentFields from the Model 
    //      to prevent confusion, as they don't seem to be used by your Controller.) ...
}