<?php

namespace App\Events;

use App\Models\Player;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerResigned extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $beneficiary_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public int $score = 0;

    public function authorize()
    {
        $this->assert(
            GameState::load($this->game_id)->player_ids->contains($this->player_id),
            'Player is not in the game.'
        );
    }

    public function validate()
    {
        $this->assert(
            PlayerState::load($this->player_id)->is_active,
            'Player has already resigned.'
        );

        $this->assert(
            $this->state(GameState::class)->player_ids->contains($this->beneficiary_id),
            'Beneficiary is not in the game.'
        );

        $this->assert(
            PlayerState::load($this->beneficiary_id)->is_active,
            'Beneficiary has already resigned.'
        );

        $this->assert(
            GameState::load($this->game_id)->ends_at > now(),
            'The game is over.'
        );
    }

    public function applyToPlayer(PlayerState $state)
    {
        $this->score = $this->state(PlayerState::class)->score;
        $state->score = 0;

        $state->is_active = false;
        $state->beneficiary_id = $this->beneficiary_id;
    }

    public function applyToGame(GameState $state)
    {
        // @todo why does this function need to exist?
    }

    public function fired()
    {
        if ($this->score > 0) {
            PlayerReceivedUpvote::fire(
                player_id: $this->beneficiary_id,
                voter_id: $this->player_id,
                game_id: $this->game_id,
                type: 'inherited',
                amount: $this->score,
            );
        }

        if ($this->score < 0) {
            PlayerReceivedDownvote::fire(
                player_id: $this->beneficiary_id,
                voter_id: $this->player_id,
                game_id: $this->game_id,
                type: 'inherited',
                amount: -$this->score,
            );
        }
    }

    public function handle()
    {
        $player = Player::find($this->player_id);

        $player->is_active = false;

        $player->save();
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => 'resigned',
                'amount' => 0 - $this->score,
                'voter_name' => PlayerState::load($this->beneficiary_id)->name,
                'score' => 0,
            ]
        );
    }
}
