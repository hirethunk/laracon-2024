<?php

namespace App\Events\Concerns;

use App\Models\Player;
use App\States\PlayerState;

trait AffectsVotes
{
    protected function applyUpvoteToPlayer(
        PlayerState|int $state,
        int $source,
        string $type,
        int $votes = 1
    ): void {
        state($state, PlayerState::class)->upvotes[] = [
            'source' => $source,
            'votes' => $votes,
            'type' => $type,
        ];
    }

    protected function applyDownvoteToPlayer(
        PlayerState|int $state,
        int $source,
        string $type,
        int $votes = 1
    ): void {
        state($state, PlayerState::class)->downvotes[] = [
            'source' => $source,
            'votes' => $votes,
            'type' => $type,
        ];
    }

    protected function syncPlayerScore(PlayerState|int $state): void
    {
        $state = state($state, PlayerState::class);

        $player = Player::find($state->id);
        $player->score = $state->score();
        $player->save();
    }
}
