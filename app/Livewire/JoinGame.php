<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Player;
use Livewire\Component;
use App\States\GameState;
use App\States\PlayerState;
use Livewire\Attributes\Layout;
use App\Events\UserRequestedToJoinGame;

class JoinGame extends Component
{
    public Game $game;

    public function requestJoinGame()
    {
        UserRequestedToJoinGame::fire(
            user_id: auth()->id(),
            game_id: $this->game->id
        );
    }

    #[Layout('layouts.app')]
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
