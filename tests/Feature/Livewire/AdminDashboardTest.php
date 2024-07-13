<?php

use App\Livewire\AdminDashboard;
use App\Models\Game;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

// @todo: test that this route is protected
it('renders successfully for admin but not others', function () {
    Livewire::actingAs($this->caleb->user)
        ->test(AdminDashboard::class, ['game' => $this->game])
        ->assertStatus(403);
})->skip();
