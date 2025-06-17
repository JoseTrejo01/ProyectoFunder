<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard general para cualquier usuario autenticado.
     */
    public function index()
    {
        return view('dashboard');
    }
}
