<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
// use App\Events\UserRequestedToJoinGame;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Thunk\Verbs\Facades\Verbs;

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

        // $game = Game::firstWhere('status', 'active');

        // UserRequestedToJoinGame::fire(
        //     user_id: $user_id,
        //     game_id: $game->id,
        // );

        Verbs::commit();

        $user = User::find($user_id);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
