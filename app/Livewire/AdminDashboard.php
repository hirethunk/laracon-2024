<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Events\PlayerJoinedGame;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Events\AdminRejectedNewPlayer;
use Thunk\Verbs\Facades\Verbs;

class AdminDashboard extends Component
{
    public ?string $search = '';

    public null|int|string $user_id = null;

    public Game $game;

    #[Computed]
    public function unapprovedUsers()
    {
        return User::whereNull('current_game_id')->orderBy('name')->get();
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[Computed]
    public function options()
    {
        return $this->unapprovedUsers
        ->filter(function ($user) {
            if (isset($this->search)) {
                return stripos($user->name, $this->search) !== false;
            }
        })
            ->sortBy(fn ($user) => $user->name)
            ->mapWithKeys(function ($unapproved_user) {
                $looks_like_dupe = User::all()
                    ->reject(fn ($user) => $user->id === $unapproved_user->id)
                    ->pluck('name')
                    ->filter(fn ($name) => $name === $unapproved_user->name
                    )->isNotEmpty();

                $name_modified = $looks_like_dupe
                    ? 'DUPE: '.$unapproved_user->name.' ('.$unapproved_user->email.')'
                    : $unapproved_user->name.' ('.$unapproved_user->email.')';

                return [$unapproved_user->id => $name_modified];
            });
    }

    public $rules = [
        'user_id' => 'integer|exists:users,id',
    ];

    public function approve()
    {
        if ($this->user_id === null) {
            dd('no user id');
            return;
        }

        $this->user_id = (int) $this->user_id;

        $this->validate();
      
        PlayerJoinedGame::fire(
            user_id: $this->user_id,
            game_id: $this->game->id,
        );

        Verbs::commit();
        return redirect()->route('admin-dashboard', $this->game->id);
    }

    public function reject()
    {
        if ($this->user_id === null) {
            return;
        }

        $this->user_id = (int) $this->user_id;

        $this->validate();

        AdminRejectedNewPlayer::fire(
            admin_id: $this->user->id,
            user_id: $this->user_id,
            game_id: $this->game->id,
            player_id: null,
        );

        session()->flash('event', 'AdminRejectedNewPlayer');

        return redirect()->route('admin-dashboard', $this->game->id);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
