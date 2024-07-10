<?php

use App\Models\Game;
use App\Models\Player;
use Livewire\Livewire;
use App\Events\PlayerVoted;
use Thunk\Verbs\Facades\Verbs;
use App\Livewire\PlayerProfile;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('renders successfully', function () {
    Livewire::actingAs($this->caleb->user)
        ->test(PlayerProfile::class, ['player' => $this->aaron])
        ->assertStatus(200);
});

it('can see another another players score history', function () {
    PlayerVoted::fire(
        player_id: $this->caleb->id,
        game_id: $this->game->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->taylor->id,
    );

    $component = Livewire::actingAs($this->caleb->user)
        ->test(PlayerProfile::class, [
            'player' => $this->aaron
        ]);

    $component->assertSee('Upvoted by Caleb Porzio');

    $component = Livewire::actingAs($this->caleb->user)
        ->test(PlayerProfile::class, [
            'player' => $this->taylor
        ]);

    $component->assertSee('Downvoted by Caleb Porzio');
});
