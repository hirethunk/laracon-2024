<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Events\AdminApprovedNewPlayer;

class AdminDashboard extends Component
{
    public ?int $user_id;

    #[Computed]
    public function unapprovedUsers()
    {
        return User::unapproved()->get();
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[Computed]
    public function game()
    {
        $game_id = $this->user->state()->is_admin_for->last();

        return Game::find($game_id);
    }

    #[Computed]
    public function options()
    {
        return $this->unapprovedUsers->mapWithKeys(fn($user) => [$user->id => $user->name]);
    }

    public function approve()
    {
        if ($this->user_id === null) {
            return;
        }

        AdminApprovedNewPlayer::fire(
            admin_id: $this->user->id,
            user_id: $this->user_id,
            game_id: $this->game->id,
            player_id: null,
        );

        session()->flash('event', 'AdminApprovedNewPlayer');

        return redirect()->route('admin-dashboard', $this->game->id);
    }

    public function render()
    {
        return view('livewire.admin-dashboard')->layout('layouts.app');
    }
}
