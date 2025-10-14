<?php
// app/Controllers/Admin.php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $userCounts = $this->userModel->getUserCountByRole();
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => "Admin Dashboard",
            'userCounts' => $userCounts,
            'users' => $users,
            'totalUsers' => $this->userModel->getTotalUsers()
        ];

        return view('admin/dashboard', $data);
    }

    public function createUser()
    {
        if ($this->request->getMethod() === 'POST') {
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role' => $this->request->getPost('role'),
                'class' => $this->request->getPost('class') ?? '',
                'student_id' => $this->request->getPost('student_id') ?? '',
                'phone' => $this->request->getPost('phone') ?? '',
                'ic_number' => $this->request->getPost('ic_number') ?? ''
            ];

<<<<<<< HEAD
            // Use custom validation for create
            if (!$this->userModel->validateCreate($userData)) {
                return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
            }

            // Perform custom validation for student fields
            if (!$this->userModel->validateStudentFields($userData)) {
                return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
            }

=======
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/dashboard')->with('success', 'User created successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
            }
        }

        return redirect()->to('/admin/dashboard');
    }

    public function deleteUser($id)
    {
        // Prevent admin from deleting themselves
        $currentUserId = session()->get('user_id');
        if ($id == $currentUserId) {
            return redirect()->to('/admin/dashboard')
                ->with('error', 'You cannot delete your own account!');
        }

        // Check if user exists
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/dashboard')
                ->with('error', 'User not found!');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/dashboard')
                ->with('success', 'User deleted successfully!');
        } else {
            return redirect()->to('/admin/dashboard')
                ->with('error', 'Failed to delete user!');
        }
    }

    /**
     * Edit user
     */
    public function editUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/dashboard')
                ->with('error', 'User not found!');
        }

        if ($this->request->getMethod() === 'POST') {
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'role' => $this->request->getPost('role'),
                'class' => $this->request->getPost('class') ?? '',
                'student_id' => $this->request->getPost('student_id') ?? '',
                'phone' => $this->request->getPost('phone') ?? '',
                'ic_number' => $this->request->getPost('ic_number') ?? ''
            ];

            // Only update password if provided
            if ($this->request->getPost('password')) {
                $userData['password'] = $this->request->getPost('password');
            }

            // Use custom validation for update (excludes current user's email)
            if (!$this->userModel->validateUpdate($userData, $id)) {
                return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
            }

            // Perform custom validation for student fields
            if (!$this->userModel->validateStudentFields($userData)) {
                return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
            }

            // Try to update the user - use skipValidation(true) to ensure no automatic validation
            if ($this->userModel->update($id, $userData)) {
                return redirect()->to('/admin/dashboard')
                    ->with('success', 'User updated successfully!');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to update user. Please try again.');
            }
        }

        // For GET requests, show edit form
        $data = [
            'title' => "Edit User",
            'user' => $user,
            'userCounts' => $this->userModel->getUserCountByRole()
        ];

        return view('admin/edit_user', $data);
    }
}