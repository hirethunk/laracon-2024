<?php

use App\Models\Game;
use App\Models\User;
use App\Models\Player;
use Livewire\Livewire;
use App\Livewire\VotingCard;
use App\Events\PlayerResigned;
use App\Livewire\PlayerDashboard;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {

    $response = $this->get('/');

    dump($response);

    $response->assertStatus(200);

    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();

    Livewire::actingAs($this->taylor->user);
});

it('renders successfully', function () {
    Livewire::test(PlayerDashboard::class)
        ->assertSeeLivewire(VotingCard::class);
});

it('does not show resigned players in dropdowns on voting card', function() {
    PlayerResigned::fire(
        player_id: $this->aaron->id,
        game_id: $this->game->id,
        beneficiary_id: $this->caleb->id,
    );

    Livewire::test(VotingCard::class, ['player' => $this->taylor])
        ->assertViewHas('players', function ($players) {
            return $players->pluck('id')->contains($this->caleb->id)
                && ! $players->pluck('id')->contains($this->aaron->id);
        });
});
