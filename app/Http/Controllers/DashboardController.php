<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_approved) {
            return redirect()->route('player-dashboard');
        }

        return view('dashboard');
    }
}
