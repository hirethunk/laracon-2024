<?php

use App\Events\PlayerEnteredSecretCode;
use App\Livewire\PlayerProfile;
use App\Livewire\SecretCodePage;
use App\Models\Game;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('allows player to submit code in livewire component', function () {
    $this->actingAs($this->taylor->user);

    Livewire::test(PlayerProfile::class, ['player' => $this->taylor])
        ->assertSee('Taylor');
});