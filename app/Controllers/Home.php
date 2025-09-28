<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $events = [
            ['id' => 1, 'title' => 'Seminar AI', 'description' => 'Belajar asas AI untuk pemula', 'date' => '2025-10-10'],
            ['id' => 2, 'title' => 'Workshop IoT', 'description' => 'Hands-on project IoT devices', 'date' => '2025-11-05'],
            ['id' => 3, 'title' => 'Tech Talk Cloud', 'description' => 'Sharing session on Cloud Computing', 'date' => '2025-12-01'],
        ];

        return view('home', [
            'title' => 'Home',
            'events' => $events
        ]);
    }
}
