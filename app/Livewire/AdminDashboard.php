<?php

namespace App\Livewire;

use App\Events\AdminApprovedNewPlayer;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AdminDashboard extends Component
{
    public ?int $user_id;

    public Game $game;

    #[Computed]
    public function unapprovedUsers()
    {
        return $this->game->state()->usersAwaitingApproval()
            ->sortBy(fn ($user) => $user->name);
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[Computed]
    public function options()
    {
        return $this->unapprovedUsers->mapWithKeys(fn ($user) => [$user->id => $user->name]);
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

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
