<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PlayerDashboard extends Component
{
    #[Computed]
    public function user(): User
    {
        return Auth::user();
    }

    #[Computed]
    public function player(): Player
    {
        return $this->user->currentPlayer();
    }

    #[Computed]
    public function game(): Game
    {
        return $this->user->currentGame();
    }

    #[Computed]
    public function gameIsActive(): bool
    {
        return $this->game->state()->ends_at > now();
    }

    #[Computed]
    public function modifier()
    {
        return $this->game->state()->activeModifier();
    }

    #[Computed]
    public function showScoreboard(): bool
    {
        $mod = $this->game->state()->activeModifier();

        return ! $mod || $mod['slug'] !== 'blackout';
    }

    public function render()
    {
        return view('livewire.player-dashboard')->layout('layouts.app');
    }
}
