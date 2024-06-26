<?php

use App\Models\Game;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;
use App\Livewire\ResignationCard;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();

    Livewire::actingAs($this->taylor->user);
});

it('renders successfully', function () {
    Livewire::test(ResignationCard::class, ['player' => $this->taylor])
        ->assertStatus(200);
});

test('a player can resign', function () {
    Livewire::test(ResignationCard::class, ['player' => $this->taylor])
        ->set('beneficiary_id', $this->caleb->id)
        ->call('resign');

    expect($this->taylor->state()->is_active)->toBeFalse();
    expect($this->taylor->fresh()->is_active)->toBeFalse();
});
