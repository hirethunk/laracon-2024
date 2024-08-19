<?php

namespace App\Livewire;

use Exception;
use App\Models\Game;
use App\Models\User;
use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Events\PlayerEnteredSecretCode;

class SecretCodePage extends Component
{
    public string $code = '';

    public $message = '';

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

    public function submitCode()
    {
        $game = $this->game->state();

        if ($game->codeIsUsed($this->code)) {
            $this->message = 'This code has already been used.';
        }

        if ($game->codeIsUnUsed($this->code)) {
            $this->message = 'Code accepted!';
        }

        if (! $game->codeIsValid($this->code)) {
            $this->message = 'Invalid code. You have received a downvote.';
        }

        try {
            PlayerEnteredSecretCode::fire(
                player_id: $this->player->id,
                game_id: $this->game->id,
                secret_code: $this->code,
            );
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.secret-code-page');
    }
}
