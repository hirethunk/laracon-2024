<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerPlayedPrisonersDilemma extends Event
{
    #[StateId(PlayerState::class, 'player')]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

    #[StateId(PlayerState::class, 'ally')]
    public int $ally_id;

    public string $nice_or_nasty;

    public function authorize(GameState $game, PlayerState $player)
    {
        $this->assert(
            $game->id === $player->game_id,
            'Player is not in the game.'
        );

        $this->assert(
            $game->ends_at > now(),
            'The game is over.'
        );
    }

    public function validate()
    {
        $this->assert(
            ! PlayerState::load($this->player_id)->prisoners_dilemma_choice,
            'Already played Prisoners Dilemma.'
        );
    }

    public function apply(PlayerState $player)
    {
        $player->prisoners_dilemma_choice = $this->nice_or_nasty;
    }

    public function fired(PlayerState $ally)
    {
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
        }

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
        }

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
        }
    }
}
