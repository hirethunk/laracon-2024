<?php

namespace App\Events;

use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedUpvote extends Event implements ExposesHistory
{
	use ValidatesPlayerInActiveGame;
	use ValidatesVoter;
	use AffectsVotes;
	
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $voter_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public int $amount;

    public string $type;

    public function applyToGame(GameState $state)
    {
        // @todo why does this function need to exist?
    }

    public function applyToPlayer(PlayerState $state)
    {
        $this->upvotePlayer($state, $this->voter_id, $this->type, $this->amount);
    }

    public function handle()
    {
		$this->syncPlayerScore($this->state(PlayerState::class));
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => $this->type,
                'amount' => $this->amount,
                'voter_name' => PlayerState::load($this->voter_id)->name,
                'score' => $this->state(PlayerState::class)->score(),
            ]
        );
    }
}
