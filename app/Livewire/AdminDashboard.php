<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Events\AdminApprovedNewPlayer;
use App\Events\AdminRejectedNewPlayer;

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
        return $this->unapprovedUsers->mapWithKeys(function ($unapproved_user) {
            $looks_like_dupe = User::all()
                ->reject(fn ($user) => $user->id === $unapproved_user->id)
                ->pluck('name')
                ->filter(fn ($name) => 
                    $name === $unapproved_user->name
                )->isNotEmpty();

            $name_modified = $looks_like_dupe 
                ? 'DUPE: ' . $unapproved_user->name . ' (' . $unapproved_user->email . ')'
                : $unapproved_user->name . ' (' . $unapproved_user->email . ')';

            return [$unapproved_user->id => $name_modified];
        });
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

    public function reject()
    {
        if ($this->user_id === null) {
            return;
        }

        AdminRejectedNewPlayer::fire(
            admin_id: $this->user->id,
            user_id: $this->user_id,
            game_id: $this->game->id,
            player_id: null,
        );

        return redirect()->route('admin-dashboard', $this->game->id);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
