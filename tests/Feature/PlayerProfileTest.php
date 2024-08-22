<?php

use App\Livewire\PlayerProfile;
use App\Models\Game;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('renders the score history', function () {
    $this->actingAs($this->taylor->user);

    Livewire::test(PlayerProfile::class, ['player' => $this->taylor])
        ->assertSee('Taylor');
});