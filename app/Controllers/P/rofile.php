<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Profile extends Controller
{
    public function index()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        return view('profile', [
            'title' => 'Profile',
            'userName' => $session->get('userName'),
            'userEmail' => $session->get('userEmail')
        ]);
    }
}
