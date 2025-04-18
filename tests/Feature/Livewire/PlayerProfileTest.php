<?php

use App\Events\PlayerVoted;
use App\Livewire\PlayerProfile;
use App\Models\Game;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

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
            'player' => $this->aaron,
        ]);

    $component->assertSee('Upvoted by Caleb Porzio');

    $component = Livewire::actingAs($this->caleb->user)
        ->test(PlayerProfile::class, [
            'player' => $this->taylor,
        ]);

    $component->assertSee('Downvoted by Caleb Porzio');
});
