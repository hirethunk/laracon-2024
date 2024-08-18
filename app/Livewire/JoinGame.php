<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\UserRequestedToJoinGame;
use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;

class JoinGame extends Component
{
    public int $game_id;

    public function requestToJoinGame()
    {
        UserRequestedToJoinGame::fire(
            user_id: auth()->id(),
            game_id: $this->game_id
        );
    }
    
    public function render()
    {
        if(Player::where([
            'user_id' => auth()->id(),
            'game_id' => $this->game_id
        ])->exists()) {
            return redirect()->route('home');
        }

        return view('livewire.join-game');
    }
}
