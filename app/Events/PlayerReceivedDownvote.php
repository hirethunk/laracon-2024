<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\HasVoter;
use App\Events\Concerns\RequiresActiveGame;
use App\Models\Player;
use App\States\PlayerState;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedDownvote extends Event implements ExposesHistory
{
	use RequiresActiveGame;
	use HasVoter;
	use HasPlayer;
	use HasGame;

    public int $amount;

    public string $type;

    public function apply(PlayerState $player)
    {
        $player->downvotes[] = [
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
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => $this->type,
                'amount' => -$this->amount,
                'voter_name' => $this->voter()->name,
                'score' => $this->player()->score(),
            ]
        );
    }
}
