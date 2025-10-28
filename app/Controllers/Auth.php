<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $role = $this->request->getPost('role');

            // --- 1. Define Conditional Validation Rules ---
            $rules = [
                'name'      => 'required|max_length[100]',
                'email'     => 'required|valid_email|is_unique[users.email]|max_length[150]',
                'password'  => 'required|min_length[8]',
                'role'      => 'required|in_list[user,organizer]',
                'terms'     => 'required|in_list[1]',
            ];

            // Add custom error messages for the terms checkbox
            $messages = [
                'terms' => [
                    'required' => 'You must agree to the Terms & Services to register.',
                    'in_list'  => 'You must agree to the Terms & Services to register.'
                ],
                'email' => [
                    'is_unique' => 'This email is already registered.'
                ]
            ];

            // Conditional Rules for Student ('user')
            if ($role === 'user') {
                $rules['class']       = 'required';
                $rules['student_id']  = 'required|is_unique[users.student_id]';
                $rules['phone']       = 'required|numeric|min_length[10]';
                $rules['ic_number']   = 'required|min_length[12]|is_unique[users.ic_number]';

                // Add custom messages for student uniqueness
                $messages['student_id']['is_unique'] = 'This Matric Number is already registered.';
                $messages['ic_number']['is_unique']  = 'This IC Number is already registered.';

            } elseif ($role === 'organizer') {
                // Conditional Rules for Organizer
                $rules['staff_id'] = 'required|is_unique[users.staff_id]';

                // Add custom message for staff uniqueness
                $messages['staff_id']['is_unique'] = 'This Staff ID is already registered.';
            }

            // Perform validation
            if (!$this->validate($rules, $messages)) {
                return view('auth/register', ['validation' => $this->validator]);
            }

            // --- 2. Prepare Conditional Data ---
            $data = [
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'), // Model will hash this via beforeInsert
                'role'     => $role,
            ];

            // Initialize all conditional fields to NULL
            $data['class']       = null;
            $data['student_id']  = null;
            $data['phone']       = null;
            $data['ic_number']   = null;
            $data['staff_id']    = null;

            if ($role === 'user') {
                // Student fields only
                $data['class']       = $this->request->getPost('class');
                $data['student_id']  = $this->request->getPost('student_id');
                $data['phone']       = $this->request->getPost('phone');
                $data['ic_number']   = $this->request->getPost('ic_number');
            } elseif ($role === 'organizer') {
                // Organizer field only
                $data['staff_id'] = $this->request->getPost('staff_id');
            }

            // --- 3. Save Based on Role ---
if ($role === 'organizer') {
    // Save to pending_organizers table instead
    $pendingModel = new \App\Models\PendingOrganizerModel();

    try {
        if (!$pendingModel->save($data)) {
            $dbError = $pendingModel->db()->error();
            $errorMessage = 'Failed to submit organizer registration.';

            if (!empty($dbError['message'])) {
                $errorMessage .= ' Database Error: ' . $dbError['message'];
            }

            session()->setFlashdata('error', $errorMessage);
            return redirect()->back()->withInput();
        }
    } catch (\Exception $e) {
        session()->setFlashdata('error', 'Database error: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }

    // Show message for organizer approval
    return redirect()->to(base_url('auth/login'))
                     ->with('success', 'Your registration has been submitted and is pending approval by the IEEP Coordinator.');

} else {
    // Regular user registration
    $userModel = new \App\Models\UserModel();

    try {
        if (!$userModel->save($data)) {
            $dbError = $userModel->db()->error();
            $errorMessage = 'Failed to save user.';

            if (!empty($dbError['message'])) {
                $errorMessage .= ' Database Error: ' . $dbError['message'];
            }

            session()->setFlashdata('error', $errorMessage);
            return redirect()->back()->withInput();
        }
    } catch (\Exception $e) {
        session()->setFlashdata('error', 'Database error: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }

    return redirect()->to(base_url('auth/login'))
                     ->with('success', 'Registration successful, please login.');
}
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
                            'isLoggedIn' => true
                        ];

                        session()->set([
                            'id'         => $user['id'],   // ✅ changed key to 'id'
                            'name'       => $user['name'],
                            'email'      => $user['email'],
                            'role'       => $role,
                            'isLoggedIn' => true
                        ]);


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

        return view('auth/login'); // This loads the initial login form
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
