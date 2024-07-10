<?php

use App\Events\PlayerResigned;
use App\Events\PlayerVoted;
use App\Livewire\ResignationCard;
use App\Livewire\Scoreboard;
use App\Livewire\VotingCard;
use App\Models\Game;
use Livewire\Livewire;
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

test('a player can resign using the ResignationCard', function () {
    expect($this->taylor->state()->is_active)->toBeTrue();
    expect($this->taylor->fresh()->is_active)->toBeTrue();

    Livewire::test(ResignationCard::class, ['player' => $this->taylor])
        ->set('beneficiary_id', $this->caleb->id)
        ->call('resign');

    expect($this->taylor->state()->is_active)->toBeFalse();
    expect($this->taylor->fresh()->is_active)->toBeFalse();
});

it('does not show resigned players in dropdowns on VotingCard', function () {
    PlayerResigned::fire(
        player_id: $this->aaron->id,
        game_id: $this->game->id,
        beneficiary_id: $this->caleb->id,
    );

    $this->actingAs($this->caleb->user);

    $upvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('upvote_options');

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($upvote_options->pluck('id'))
        ->not()->toContain($this->aaron->id);

    expect($downvote_options->pluck('id'))
        ->not()->toContain($this->aaron->id);
});

it('does not show resigned players in Scoreboard', function () {
    PlayerResigned::fire(
        player_id: $this->aaron->id,
        game_id: $this->game->id,
        beneficiary_id: $this->caleb->id,
    );

    Livewire::test(Scoreboard::class, ['player' => $this->taylor])
        ->assertViewHas('players', function ($players) {
            return $players->pluck('id')->contains($this->caleb->id)
                && ! $players->pluck('id')->contains($this->aaron->id);
        });
});
