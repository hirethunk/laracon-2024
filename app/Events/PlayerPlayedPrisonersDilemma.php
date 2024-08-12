<?php

namespace App\Events;

use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class PlayerPlayedPrisonersDilemma extends Event 
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public int $ally_id;

    public string $nice_or_nasty;

    public function authorize()
    {
        $this->assert(
            GameState::load($this->game_id)->player_ids->contains($this->player_id),
            'Player is not in the game.'
        );

        $this->assert(
            GameState::load($this->game_id)->ends_at > now(),
            'The game is over.'
        );
    }

    public function apply(PlayerState $state)
    {
        $state->prisoners_dilemma_choice = $this->nice_or_nasty;
    }

    public function fired()
    {
        $ally = PlayerState::load($this->ally_id);
        
        if (! $ally->prisoners_dilemma_choice) {
            return;
        }

        if ($this->nice_or_nasty === 'nice' && $ally->prisoners_dilemma_choice === 'nice') {
            PlayerReceivedUpvote::fire(
                player_id: $this->player_id,
                voter_id: $this->ally_id,
                game_id: $this->game_id,
                type: 'prisoners-dilemma-nice-nice',
                amount: 2,
            );

            PlayerReceivedUpvote::fire(
                player_id: $this->ally_id,
                voter_id: $this->player_id,
                game_id: $this->game_id,
                type: 'prisoners-dilemma-nice-nice',
                amount: 2,
            );
        };

        if ($this->nice_or_nasty === 'nasty' && $ally->prisoners_dilemma_choice === 'nasty') {
            PlayerReceivedDownvote::fire(
                player_id: $this->player_id,
                voter_id: $this->ally_id,
                game_id: $this->game_id,
                type: 'prisoners-dilemma-nasty-nasty',
                amount: 2,
            );

            PlayerReceivedDownvote::fire(
                player_id: $this->ally_id,
                voter_id: $this->player_id,
                game_id: $this->game_id,
                type: 'prisoners-dilemma-nasty-nasty',
                amount: 2,
            );
        };

        if ($this->nice_or_nasty !== $ally->prisoners_dilemma_choice) {
            $nasty_player_id = $this->nice_or_nasty === 'nasty' 
                ? $this->player_id 
                : $this->ally_id;

            $nice_player_id = $this->nice_or_nasty === 'nice' 
                ? $this->player_id 
                : $this->ally_id;

            PlayerReceivedUpvote::fire(
                player_id: $nasty_player_id,
                voter_id: $nice_player_id,
                game_id: $this->game_id,
                type: 'prisoners-dilemma-nasty-nice',
                amount: 5,
            );
        };
    }
}
