<?php

namespace App\Events;

use App\Models\Player;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use App\Events\ScoreChanged;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class PlayerReceivedUpvote extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $voter_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public function validate()
    {   
        $this->assert(
            $this->state(GameState::class)->player_ids->contains($this->player_id),
            'Player is not in the game.'
        );

        $this->assert(
            $this->state(GameState::class)->player_ids->contains($this->voter_id),
            'Voter is not in the game.'
        );
    }

    public function applyToGame(GameState $state)
    {
        // @todo why does this function need to exist?
    }

    public function applyToPlayer(PlayerState $state)
    {
        $state->upvotes[] = [
            'source' => $this->voter_id,
            'votes' => 1,
        ];
    }

    public function handle()
    {
        $player = Player::find($this->player_id);

        $player->score = $this->state(PlayerState::class)->score();

        $player->save();

        ScoreChanged::dispatch($this->game_id);
    }
}
