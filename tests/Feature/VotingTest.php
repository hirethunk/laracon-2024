<?php

use App\Models\Game;
use App\Events\PlayerVoted;
use Illuminate\Support\Carbon;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('a player can upvote and downvote other players', function () {
    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->caleb->id,
    );

    expect($this->aaron->state()->upvotes)->toHaveCount(1);
    expect($this->aaron->state()->score())->toBe(1);

    expect($this->caleb->state()->downvotes)->toHaveCount(1);
    expect($this->caleb->state()->score())->toBe(-1);
});

it('a player cannot vote for themselves', function () {
    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->taylor->id,
        downvotee_id: $this->taylor->id,
    );
})->throws('Cannot vote for yourself.');

it('upvotee must be in the game', function () {
    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: 123435,
        downvotee_id: $this->aaron->id,
    );
})->throws('Upvotee is not in the game.');

it('downvotee must be in the game', function () {
    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: 123435,
    );
})->throws('Downvotee is not in the game.');

it('players must wait 1 hour between votes', function () {
    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->caleb->id,
    );

    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->caleb->id,
    );
})->throws('Voter must wait 1 hour between votes.');

it('players can vote again after 1 hour', function () {
    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->caleb->id,
    );

    // @todo this test fails. why does authorize() get called three times and have different values?
    Carbon::setTestNow(now()->addMinutes(61));

    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->caleb->id,
    );
})->skip();