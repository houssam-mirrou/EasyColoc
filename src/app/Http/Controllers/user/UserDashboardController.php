<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;

class UserDashboardController
{
    public function index()
    {
        return view('pages.user.dashboard');
    }
}