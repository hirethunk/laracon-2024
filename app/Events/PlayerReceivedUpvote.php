<?php

namespace App\Events;

use App\Models\Player;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedUpvote extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $voter_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public int $amount;

    public string $type;

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
            'votes' => $this->amount,
            'type' => $this->type,
        ];
    }

    public function handle()
    {
        $player = Player::find($this->player_id);

        $player->score = $this->state(PlayerState::class)->score();

        $player->save();
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return 'player_id = ' . Player::firstWhere('id', $this->player_id)->user->name .
            'game_id = ' . $this->game_id .
            'received '. $this->amount .' upvotes from ' . $this->type;
    }
}
