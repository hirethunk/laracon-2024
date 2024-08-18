<?php

namespace App\Livewire;

use App\Events\UserAddedReferral;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class HomePage extends Component
{
    public ?string $name;
    public ?int $referrer_id;

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[Computed]
    public function referrer(): ?Player
    {
        return $this->user->referringPlayer();
    }

    #[Computed]
    public function game(): ?Game
    {
        return Game::firstWhere('status', 'active');
    }

    #[Computed]
    public function players()
    {
        return $this->game->players;
    }

    #[Computed]
    public function options()
    {
        return $this->players->filter(function ($player) {
            if (isset($this->name)) {
                return stripos($player->user->name, $this->name) !== false;
            }
        })->mapWithKeys(function ($player) {
            return [$player->id => $player->user->name];
        });
    }

    public function mount()
    {
        if ($this->user->currentPlayer()) {
            return redirect()->route('player-dashboard', ['player' => $this->user->currentPlayer()]);
        }
    }

    public function addReferrer()
    {
        if (! isset($this->referrer_id)) {
            dump('not set' , $this->referrer_id);
            return;
        }

        dump('set' , $this->referrer_id);

        UserAddedReferral::fire(
            user_id: $this->user->id,
            referrer_player_id: $this->referrer_id,
            game_id: $this->game->id,
        );

        return redirect()->route('home');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.home-page');
    }
}
