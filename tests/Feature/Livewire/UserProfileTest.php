<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\UserProfile;
use Thunk\Verbs\Facades\Verbs;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Verbs::commitImmediately();
});

it('renders successfully', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(UserProfile::class)
        ->assertStatus(200);
});

it('updates the user name', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(UserProfile::class)
        ->set('name', 'John Doe')
        ->call('updateName');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John Doe',
    ]);
});

it('validates the user name', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(UserProfile::class)
        ->set('name', '')
        ->call('updateName')
        ->assertHasErrors(['user.name' => 'required']);
});

it('does not update user names when the user is already approved', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'status' => 'approved',
    ]);

    Livewire::actingAs($user)
        ->test(UserProfile::class)
        ->set('name', 'Something New')
        ->call('updateName');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John Doe',
    ]);
});
