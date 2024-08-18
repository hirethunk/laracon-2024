<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Player;
use Livewire\Component;
use App\States\GameState;
use App\States\PlayerState;
use App\Events\UserRequestedToJoinGame;

class JoinGame extends Component
{
    public Game $game;

    public function requestToJoinGame()
    {
        UserRequestedToJoinGame::fire(
            user_id: auth()->id(),
            game_id: $this->game->id
        );
    }
    
    public function render()
    {
        if(Player::where([
            'user_id' => auth()->id(),
            'game_id' => $this->game->id
        ])->exists()) {
            return redirect()->route('home');
        }

        return view('livewire.join-game');
    }
}
