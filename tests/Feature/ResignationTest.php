<?php

use App\Models\Game;
use App\Models\User;
use Livewire\Livewire;
use App\Events\PlayerVoted;
use App\Livewire\VotingCard;
use App\Events\PlayerResigned;
use App\Livewire\Scoreboard;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('a player can resign', function () {
    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->caleb->id,
    );

    PlayerVoted::fire(
        player_id: $this->caleb->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->taylor->id,
    );

    $this->assertEquals(2, $this->aaron->state()->score());
    $this->assertEquals(-1, $this->caleb->state()->score());

    PlayerResigned::fire(
        player_id: $this->aaron->id,
        game_id: $this->game->id,
        beneficiary_id: $this->caleb->id,
    );

    $this->assertEquals(1, $this->caleb->state()->score());
});

it('does not allow players to vote for someone who resigned', function () {
    PlayerResigned::fire(
        player_id: $this->aaron->id,
        game_id: $this->game->id,
        beneficiary_id: $this->caleb->id,
    );

    PlayerVoted::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->caleb->id,
    );
})->throws('Upvotee has already resigned.');

it('does not allow player to resign twice', function () {
    PlayerResigned::fire(
        player_id: $this->aaron->id,
        game_id: $this->game->id,
        beneficiary_id: $this->caleb->id,
    );

    PlayerResigned::fire(
        player_id: $this->aaron->id,
        game_id: $this->game->id,
        beneficiary_id: $this->caleb->id,
    );
})->throws('Player has already resigned.');
