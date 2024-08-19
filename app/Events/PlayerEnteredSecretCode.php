<?php

namespace App\Events;

use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;

class PlayerEnteredSecretCode extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public string $secret_code;

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

    public function applyToGame(GameState $game)
    {
        if (collect($game->unused_codes)->contains($this->secret_code)) {
            $game->unused_codes = collect($game->unused_codes)
                ->filter(fn ($code) => $code !== $this->secret_code)
                ->toArray();

            $game->used_codes[] = $this->secret_code;
        }
    }

    public function applyToPlayer(PlayerState $player)
    {
        $game = $this->state(GameState::class);

        if ($game->codeIsUnused($this->secret_code)) {
            $player->score += 1;
        }

        if (! $game->codeIsValid($this->secret_code)) {
            $player->score -= 1;
            $player->can_submit_code_at = now()->addMinutes(60);
        }
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        $game = $this->state(GameState::class);

        if ($game->codeIsUnused($this->secret_code)) {
            return new HistoryComponentDto(
                component: 'history.vote',
                props: [
                    'type' => 'secret-code-reward',
                    'amount' => 1,
                    'voter_name' => PlayerState::load($this->voter_id)->name,
                    'score' => $this->state(PlayerState::class)->score,
                ]
            );
        }

        if (! $game->codeIsValid($this->secret_code)) {
            return new HistoryComponentDto(
                component: 'history.vote',
                props: [
                    'type' => 'invalid_secret_code',
                    'amount' => -1,
                    'voter_name' => PlayerState::load($this->player_id)->name,
                    'score' => $this->state(PlayerState::class)->score,
                ]
            );
        }
    }
}
