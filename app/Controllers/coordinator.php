<?php

namespace App\Controllers;

class Coordinator extends BaseController
{
    public function dashboard()
    {
        $data['title'] = "IEEP Coordinator Dashboard";
        return view('coordinator/dashboard', $data);
    }
}
