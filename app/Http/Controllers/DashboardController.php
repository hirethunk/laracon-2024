<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->currentPlayer()) {
            return redirect()->route('player-dashboard');
        }

        return view('home');
    }
}
