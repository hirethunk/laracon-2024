<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Events\UserCreated;
use Illuminate\Http\Request;
use Thunk\Verbs\Facades\Verbs;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user_id = UserCreated::fire([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->user_id;

        Verbs::commit();

        $user = User::find($user_id);

        event(new Registered($user));

        Auth::login($user);

        return $user->isApproved
            ? redirect(route('player-dashboard'))
            : redirect(route('dashboard', absolute: false));
    }
}
