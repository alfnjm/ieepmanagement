<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        helper(['form']);
        print_r($this->request->getMethod());
        
        if ($this->request->getMethod() === 'POST') {
            // print_r("form submit");
            // die();
            // 1. Define Validation Rules
            // These rules prevent data integrity failures in the database.
            $rules = [
                'name'     => 'required|max_length[100]',
                'email'    => 'required|valid_email|is_unique[users.email]|max_length[150]',
                'password' => 'required|min_length[8]',
                // If the fields are optional, use 'permit_empty'. If they must be unique, check that too.
                'student_id' => 'permit_empty|is_unique[users.student_id]', 
                'ic_number' => 'permit_empty|is_unique[users.ic_number]',
                'class' => 'permit_empty',
                'phone' => 'permit_empty',
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
            ];

            $userModel = new UserModel();

            if (!$userModel->save($data)) {
                // If save fails, redirect back with model errors
                // This will force the red box to show up on the register page with the exact error.
                session()->setFlashdata('error', $userModel->errors());
                return redirect()->back()->withInput();
            }

            // 3. Success: Use base_url() for correct routing
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
                        // User authenticated successfully, set session
                        $userData = [
                            'id'         => $user['id'],
                            'name'       => $user['name'],
                            'email'      => $user['email'],
                            'isLoggedIn' => TRUE
                        ];
                        session()->set($userData);

                        // Redirect to the user/dashboard
                        return redirect()->to(base_url('user/dashboard'));
                    } else {
                        // Password mismatch
                        session()->setFlashdata('error', 'Invalid credentials. Password incorrect.');
                        return redirect()->back()->withInput();
                    }
                } else {
                    // User not found
                    session()->setFlashdata('error', 'Invalid credentials. Email not found.');
                    return redirect()->back()->withInput();
                }
            } else {
                // Validation failed (missing email/password)
                return view('auth/login', ['validation' => $this->validator]);
            }
        }
        
        return view('auth/login');
    }
}
