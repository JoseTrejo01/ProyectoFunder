<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class   DashboardController extends Controller
{

    public function admin()
    {
        return view('admin.dashboard'); // vista para admin
    }

    public function general()
    {
        return view('dashboard'); // vista general para otros usuarios
    }
   
}
