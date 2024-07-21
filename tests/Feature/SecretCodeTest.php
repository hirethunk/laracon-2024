<?php

use App\Events\PlayerEnteredSecretCode;
use App\Models\Game;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('a player can input a secret code for an upvote', function () {
    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'GO1VCQJ0OQ'
    );

    expect($this->taylor->state()->upvotes)->toHaveCount(1);
    expect($this->taylor->state()->score())->toBe(1);
});

it('does not reward player for using the same code twice', function () {
    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'GO1VCQJ0OQ'
    );

    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'GO1VCQJ0OQ'
    );

    expect($this->taylor->state()->upvotes)->toHaveCount(1);
    expect($this->taylor->state()->score())->toBe(1);
});

it('penalizes players for using invalid codes', function () {
    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'I created Laravel'
    );

    expect($this->taylor->state()->downvotes)->toHaveCount(1);
    expect($this->taylor->state()->score())->toBe(-1);
});
