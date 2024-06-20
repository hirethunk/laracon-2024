<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;

class PlayerProfile extends Component
{
    public Player $player;

    public function render()
    {
        return view('livewire.player-profile')->layout('layouts.app');
    }
}
