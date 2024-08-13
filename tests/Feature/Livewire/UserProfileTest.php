<?php

use App\Livewire\UserProfile;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();

    $this->getUnapprovedUser();

    $this->actingAs($this->unapproved_user);
});

it('renders successfully', function () {
    Livewire::test(UserProfile::class)
        ->assertStatus(200);
});

it('updates the user name', function () {
    expect('unapproved')
        ->toEqual($this->unapproved_user->fresh()->name)
        ->toEqual($this->unapproved_user->state()->name);

    Livewire::test(UserProfile::class)
        ->set('name', 'John Doe')
        ->call('updateName');

    expect('John Doe')
        ->toEqual($this->unapproved_user->state()->name)
        ->toEqual($this->unapproved_user->fresh()->name);

    $this->assertDatabaseHas('users', [
        'id' => $this->unapproved_user->id,
        'name' => 'John Doe',
    ]);
});

it('requires a name prop in order to update', function () {
    Livewire::test(UserProfile::class)
        ->set('name', '')
        ->call('updateName')
        ->assertHasErrors(['name' => 'required']);
});

it('does not update a user name when the user is already approved', function () {
    Livewire::actingAs($this->caleb->user)
        ->test(UserProfile::class)
        ->set('name', 'Something New')
        ->call('updateName');

    expect('Caleb Porzio')
        ->toEqual($this->caleb->user->fresh()->name)
        ->toEqual($this->caleb->user->state()->name);

    $this->assertDatabaseHas('users', [
        'id' => $this->caleb->user->id,
        'name' => 'Caleb Porzio',
    ]);
});
