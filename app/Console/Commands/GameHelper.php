<?php

namespace App\Console\Commands;

use App\Events\PlayerVoted;
use Thunk\Verbs\Facades\Verbs;
use Illuminate\Support\Collection;
use function Laravel\Prompts\progress;

class GameHelper
{
    public static function round(Collection $player_ids, int $game_id)
    {
        $progress = progress('Starting round...', $player_ids->count());
        $progress->start();
        
        $player_ids->each(function ($player_id) use ($player_ids, $game_id, $progress) {
            $upvote = $player_ids->reject(fn ($id) => $id === $player_id)->random();
            $downvote = $player_ids->reject(fn ($id) => $id === $player_id)->random();

            PlayerVoted::fire(
                player_id: $player_id,
                upvotee_id: $upvote,
                downvotee_id: $downvote,
                game_id: $game_id,
            );

            $progress->advance();
        });

        Verbs::commit();
    }
}
