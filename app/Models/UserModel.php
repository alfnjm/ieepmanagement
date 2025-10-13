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
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Disable automatic validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = true;

    /**
     * Custom validation for user registration
     */
    public function validateRegistration($data)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'class' => 'required|max_length[50]',
            'student_id' => 'required|max_length[50]|is_unique[users.student_id]',
            'phone' => 'required|max_length[20]',
            'ic_number' => 'required|max_length[20]'
        ], [
            'email' => [
                'is_unique' => 'This email is already registered.'
            ],
            'student_id' => [
                'is_unique' => 'This matric number is already registered.'
            ]
        ]);

        return $validation->run($data);
    }

    /**
     * Custom validation for user update
     */
    public function validateUpdate($data, $id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'class' => 'max_length[50]',
            'student_id' => "max_length[50]|is_unique[users.student_id,id,{$id}]",
            'phone' => 'max_length[20]',
            'ic_number' => 'max_length[20]'
        ], [
            'email' => [
                'is_unique' => 'This email is already registered.'
            ],
            'student_id' => [
                'is_unique' => 'This matric number is already registered.'
            ]
        ]);

        return $validation->run($data);
    }

    /**
     * Create user with hashed password
     */
    public function createUser($data)
    {
        // Hash password if it's provided and not already hashed
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Ensure role is set to 'user' if not specified
        if (!isset($data['role'])) {
            $data['role'] = 'user';
        }
        
        return $this->insert($data);
    }

    /**
     * Save user data with automatic password hashing
     */
    public function save($data = null): bool
    {
        // Hash password if it's provided and not already hashed
        if (isset($data['password']) && !empty($data['password'])) {
            // Check if password is already hashed (basic check)
            if (!password_get_info($data['password'])['algo']) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        }
        
        return parent::save($data);
    }

    /**
     * Update user with automatic password hashing
     */
    public function update($id = null, $data = null): bool
    {
        // Hash password if it's provided and not already hashed
        if (isset($data['password']) && !empty($data['password'])) {
            // Check if password is already hashed (basic check)
            if (!password_get_info($data['password'])['algo']) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        } elseif (isset($data['password']) && empty($data['password'])) {
            // Remove password if empty
            unset($data['password']);
        }
        
        return parent::update($id, $data);
    }

    /**
     * Get user counts by role
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

    /**
     * Get users by specific role
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Find user by student_id
     */
    public function findByStudentId($student_id)
    {
        return $this->where('student_id', $student_id)->first();
    }

    /**
     * Get all users with pagination
     */
    public function getPaginatedUsers($perPage = 10)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    /**
     * Search users by name or email
     */
    public function searchUsers($searchTerm)
    {
        return $this->like('name', $searchTerm)
                    ->orLike('email', $searchTerm)
                    ->orLike('student_id', $searchTerm)
                    ->findAll();
    }

    /**
     * Update user profile (without requiring password)
     */
    public function updateUser($id, $data)
    {
        // Remove password if empty
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        return $this->update($id, $data);
    }

    /**
     * Check if email exists (for update validation)
     */
    public function isEmailUnique($email, $exceptId = null)
    {
        $builder = $this->where('email', $email);
        
        if ($exceptId) {
            $builder->where('id !=', $exceptId);
        }
        
        return $builder->countAllResults() === 0;
    }

    /**
     * Check if student_id exists (for update validation)
     */
    public function isStudentIdUnique($student_id, $exceptId = null)
    {
        $builder = $this->where('student_id', $student_id);
        
        if ($exceptId) {
            $builder->where('id !=', $exceptId);
        }
        
        return $builder->countAllResults() === 0;
    }

    /**
     * Validate student-specific fields
     */
    public function validateStudentFields($data)
    {
        $validation = \Config\Services::validation();
        
        // Only validate student fields if role is 'user'
        if (isset($data['role']) && $data['role'] === 'user') {
            $validation->setRules([
                'class' => 'required|max_length[50]',
                'student_id' => 'required|max_length[50]',
                'phone' => 'required|max_length[20]',
                'ic_number' => 'required|max_length[20]'
            ]);
        } else {
            // For non-student roles, no validation needed for these fields
            return true;
        }

        return $validation->run($data);
    }

    /**
     * Get total user count
     */
    public function getTotalUsers()
    {
        return $this->countAll();
    }

    /**
     * Get recent users
     */
    public function getRecentUsers($limit = 5)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Verify user credentials for login
     */
    public function verifyCredentials($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * Fix existing plain text passwords (run this once)
     */
    public function fixExistingPasswords()
    {
        $users = $this->findAll();
        $fixed = 0;
        
        foreach ($users as $user) {
            // Check if password is not hashed (basic check - if it doesn't look like a hash)
            $passwordInfo = password_get_info($user['password']);
            if ($passwordInfo['algo'] === 0 && strlen($user['password']) < 60) {
                $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
                $this->update($user['id'], ['password' => $hashedPassword]);
                $fixed++;
            }
        }
        
        return $fixed;
    }
}