<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'post') {
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
                // 🔴 Debug error kalau gagal
                dd($userModel->errors());
            }

            return redirect()->to('auth/login')->with('success', 'Registration successful, please login.');
        }

        return view('auth/register');
    }

    public function login()
    {
        return view('auth/login');
    }
}
