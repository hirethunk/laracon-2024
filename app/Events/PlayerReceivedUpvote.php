<?php

namespace App\Events;

use App\Models\Player;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedUpvote extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $voter_id;

    public int $amount;

    public string $type;

    protected ?Player $player = null;

    public function validate(PlayerState $player, PlayerState $voter)
    {
        $this->assert(
            $voter->game_id === $player->game_id,
            'Voter and target are not in the same game.'
        );
    }

    public function applyToPlayer(PlayerState $player)
    {
        $player->score += $this->amount;

        if ($this->type === 'buddy-system-reward') {
            $player->buddy_system_reward_received = true;
        }
    }

    public function handle()
    {
        $this->player ??= Player::find($this->player_id);

        $this->player->update([
            'score' => $this->states()->get('player')->score,
        ]);
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => $this->type,
                'amount' => $this->amount,
                'voter_name' => PlayerState::load($this->voter_id)->name,
                'score' => $this->states()->get('player')->score,
            ]
        );
    }
}
