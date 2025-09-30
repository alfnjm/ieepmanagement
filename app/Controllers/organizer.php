<?php

namespace App\Controllers;

class Organizer extends BaseController
{
    public function dashboard()
    {
        $data['title'] = "Program Organizer Dashboard";
        return view('organizer/dashboard', $data);
    }
}
