<?php

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

    /**
     * Admin Dashboard
     */
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

    /**
     * Users Page - Manage Users
     */
    public function users()
    {
        $data = [
            'title' => 'Manage Users',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
            'userCounts' => $this->userModel->getUserCountByRole()
        ];

        return view('admin/users', $data);
    }

    /**
     * Ongoing Events Page
     */
    public function events()
{
    $eventModel = new \App\Models\EventModel();
    $today = date('Y-m-d'); // Current date is 2025-10-30
    $data['events'] = $eventModel->where('date >=', $today)
                                 ->orderBy('date', 'ASC')
                                 ->findAll();
    $data['title'] = 'Ongoing & Upcoming Events';
    return view('admin/events', $data);
}

    /**
     * Create new user
     */
    public function createUser()
    {
        if ($this->request->getMethod() === 'POST') {
            $role = $this->request->getPost('role');

            // ✅ Validation Rules
            $rules = [
                'name'  => 'required|min_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role'  => 'required',
            ];

            if ($role === 'user') {
                $rules['student_id'] = 'required';
                $rules['class'] = 'required';
            } elseif (in_array($role, ['coordinator', 'organizer'])) {
                $rules['staff_id'] = 'required';
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userData = [
                'name'        => $this->request->getPost('name'),
                'email'       => $this->request->getPost('email'),
                'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'        => $role,
                'class'       => $this->request->getPost('class'),
                'student_id'  => $this->request->getPost('student_id'),
                'phone'       => $this->request->getPost('phone'),
                'ic_number'   => $this->request->getPost('ic_number'),
                'staff_id'    => $this->request->getPost('staff_id'),
            ];

            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/users')->with('success', 'User created successfully!');
            }

            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }

        return redirect()->to('/admin/users');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $currentUserId = session()->get('user_id');
        if ($id == $currentUserId) {
            return redirect()->to('admin/users')->with('error', 'You cannot delete your own account!');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found!');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('admin/users')->with('success', 'User deleted successfully!');
        }

        return redirect()->to('admin/users')->with('error', 'Failed to delete user.');
    }

    /**
     * ✅ Edit user (GET + POST)
     */
    public function editUser($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found!');
        }

        // --- If POST request, update user ---
        if ($this->request->getMethod() === 'POST') {
            $role = $this->request->getPost('role');

            // ✅ Validation Rules
            $rules = [
                'name'  => 'required|min_length[3]',
                'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
                'role'  => 'required',
            ];

            if ($role === 'user') {
                $rules['student_id'] = 'required';
                $rules['class'] = 'required';
            } elseif (in_array($role, ['coordinator', 'organizer'])) {
                $rules['staff_id'] = 'required';
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userData = [
                'name'        => $this->request->getPost('name'),
                'email'       => $this->request->getPost('email'),
                'role'        => $role,
                'class'       => $this->request->getPost('class'),
                'student_id'  => $this->request->getPost('student_id'),
                'phone'       => $this->request->getPost('phone'),
                'ic_number'   => $this->request->getPost('ic_number'),
                'staff_id'    => $this->request->getPost('staff_id'),
            ];

            // Only update password if provided
            if ($this->request->getPost('password')) {
                $userData['password'] = $this->request->getPost('password');
            }

            if ($this->userModel->update($id, $userData)) {
                return redirect()->to('admin/users')->with('success', 'User updated successfully!');
            }

            return redirect()->back()->withInput()->with('error', 'Failed to update user.');
        }

        // --- If GET request, show form ---
        $data = [
            'title' => "Edit User",
            'user' => $user,
            'userCounts' => $this->userModel->getUserCountByRole()
        ];

        return view('admin/edit_user', $data);
    }
}
