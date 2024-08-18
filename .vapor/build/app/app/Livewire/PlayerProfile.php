<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PlayerProfile extends Component
{
    public Player $player;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.player-profile');
    }
}
