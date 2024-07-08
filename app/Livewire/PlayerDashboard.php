<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\User;
use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

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
        return $this->player->currentGame();
    }

    #[Computed]
    public function modifier(): array|null
    {
        return $this->game->state()->activeModifier();
    }

    public function render()
    {
        return view('livewire.player-dashboard')->layout('layouts.app');
    }
}
