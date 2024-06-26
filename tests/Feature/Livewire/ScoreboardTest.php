<?php

use App\Models\Game;
use Livewire\Livewire;
use App\Livewire\Scoreboard;
use App\Events\PlayerResigned;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();

    Livewire::actingAs($this->taylor->user);
});

it('renders successfully', function () {
    Livewire::test(Scoreboard::class, ['player' => $this->taylor])
        ->assertStatus(200);
});

it('does not show resigned players in scoreboard', function() {
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
