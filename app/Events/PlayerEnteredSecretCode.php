<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerEnteredSecretCode extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public string $secret_code;

    public function authorize(GameState $game)
    {
        $this->assert(
            $game->player_ids->contains($this->player_id),
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
            ! $this->state(GameState::class)->codeIsUsed($this->secret_code),
            'Code has already been used.'
        );
    }

    public function applyToPlayer(PlayerState $player, GameState $game)
    {
        if (! $game->codeIsValid($this->secret_code)) {
            $player->score -= 1;
            $player->can_submit_code_at = now()->addHour(1);

            return;
        }

        if (! $game->codeIsUnused($this->secret_code)) {
            return;
        }

        $player->score += 1;
    }

    public function applyToGame(GameState $game)
    {
        if (! $game->codeIsUnused($this->secret_code)) {
            return;
        }

        $game->used_codes[] = $this->secret_code;
        $game->unused_codes = array_filter($game->unused_codes, fn ($code) => $code !== $this->secret_code);
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        $game = $this->state(GameState::class);
        $player = PlayerState::load($this->player_id);

        if (! $game->codeIsValid($this->secret_code)) {
            return new HistoryComponentDto(
                component: 'history.vote',
                props: [
                    'type' => 'invalid-secret-code',
                    'amount' => -1,
                    'voter_name' => $player->name,
                    'score' => $player->score,
                ]
            );
        }

        if (! $game->codeIsUnused($this->secret_code)) {
            return new HistoryComponentDto(
                component: 'history.vote',
                props: [
                    'type' => 'reused-secret-code',
                    'amount' => 0,
                    'voter_name' => $player->name,
                    'score' => $player->score,
                ]
            );
        }

        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => 'secret-code-reward',
                'amount' => 1,
                'voter_name' => $player->name,
                'score' => $player->score,
            ]
        );
    }
}
