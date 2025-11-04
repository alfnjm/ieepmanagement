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
        'staff_id', // added staff_id
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Automatically hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }

    /**
     * Validate data when creating a user
     */
    public function validateCreate($data)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required'
        ];

        // Add extra rules for role type
        if (isset($data['role']) && $data['role'] === 'student') {
            $rules['student_id'] = 'required';
        } elseif (isset($data['role']) && $data['role'] === 'staff') {
            $rules['staff_id'] = 'required';
        }

        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return [
                'success' => false,
                'errors' => $validation->getErrors()
            ];
        }

        return ['success' => true];
    }

    /**
     * Validate extra fields for students (restore old function)
     */
    public function validateStudentFields($data)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'student_id' => 'required',
            'class'      => 'required'
        ];

        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return [
                'success' => false,
                'errors' => $validation->getErrors()
            ];
        }

        return ['success' => true];
    }

    /**
     * Get total users
     */
    public function getTotalUsers()
    {
        return $this->countAllResults();
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

    public function registerEvent($eventId)
    {
        $session = session();
        $userId = $session->get('id');

        // Check if user is logged in
        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        // --- THIS IS THE FIX ---
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel();

        // 1. Fetch the event
        $event = $eventModel->find($eventId);

        if (!$event) {
            return redirect()->to('user/dashboard')->with('error', 'Event not found.');
        }

        // 2. Get today's date
        $today = date('Y-m-d'); 

        // 3. Block registration if event date is in the past
        if ($event['date'] < $today) {
            return redirect()->to('user/dashboard')->with('error', 'Registration is closed. This event has already passed.');
        }
        // --- END OF FIX ---


        // Prevent duplicate registrations
        $existing = $registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if ($existing) {
            return redirect()->to('user/dashboard')
                             ->with('info', 'You are already registered for this event.');
        }

        // Insert registration
        $registrationModel->insert([
            'user_id'    => $userId,
            'event_id'   => $eventId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('user/dashboard')
                         ->with('success', 'Successfully registered for the event.');
    }
}
