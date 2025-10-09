<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        helper(['form']);
        
        if ($this->request->getMethod() === 'POST') {
            // 1. Define Validation Rules
            $rules = [
                'name'     => 'required|max_length[100]',
                'email'    => 'required|valid_email|is_unique[users.email]|max_length[150]',
                'password' => 'required|min_length[8]',
                'student_id' => 'permit_empty|is_unique[users.student_id]', 
                'ic_number' => 'permit_empty|is_unique[users.ic_number]',
                'class' => 'permit_empty',
                'phone' => 'permit_empty',
                'role' => 'required|in_list[user,organizer,coordinator]', // Add role validation
            ];

            if (!$this->validate($rules)) {
                return view('auth/register', ['validation' => $this->validator]);
            }

            // 2. Prepare data
            $data = [
                'name'       => $this->request->getPost('name'),
                'email'      => $this->request->getPost('email'),
                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'class'      => $this->request->getPost('class'),
                'student_id' => $this->request->getPost('student_id'),
                'phone'      => $this->request->getPost('phone'),
                'ic_number'  => $this->request->getPost('ic_number'),
                'role'       => $this->request->getPost('role'), // Get role from form
            ];

            $userModel = new UserModel();

            try {
                if (!$userModel->save($data)) {
                    session()->setFlashdata('error', $userModel->errors());
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Database error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }

            return redirect()->to(base_url('auth/login'))->with('success', 'Registration successful, please login.');
        }

        return view('auth/register');
    }

    public function login()
    {
        helper(['form']);
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required'
            ];

            if ($this->validate($rules)) {
                $userModel = new UserModel();
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                $user = $userModel->where('email', $email)->first();

                if ($user) {
                    if (password_verify($password, $user['password'])) {
                        // Check if role exists, default to 'user' if not
                        $role = isset($user['role']) ? $user['role'] : 'user';
                        
                        $userData = [
                            'id'         => $user['id'],
                            'name'       => $user['name'],
                            'email'      => $user['email'],
                            'role'       => $role,
                            'isLoggedIn' => TRUE
                        ];
                        session()->set($userData);

                        return $this->redirectToDashboard($role);
                    } else {
                        session()->setFlashdata('error', 'Invalid credentials. Password incorrect.');
                        return redirect()->back()->withInput();
                    }
                } else {
                    session()->setFlashdata('error', 'Invalid credentials. Email not found.');
                    return redirect()->back()->withInput();
                }
            } else {
                return view('auth/login', ['validation' => $this->validator]);
            }
        }
        
        return view('auth/login');
    }

    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to(base_url('admin/dashboard'));
            case 'coordinator':
                return redirect()->to(base_url('coordinator/dashboard'));
            case 'organizer':
                return redirect()->to(base_url('organizer/dashboard'));
            case 'user':
            default:
                return redirect()->to(base_url('user/dashboard'));
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}