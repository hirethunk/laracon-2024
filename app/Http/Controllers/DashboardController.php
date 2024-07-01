<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->currentPlayer()) {
            return redirect()->route('player-dashboard', ['player' => Auth::user()->currentPlayer()]);
        }

        return view('dashboard');
    }
}
