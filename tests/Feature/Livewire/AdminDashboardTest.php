<?php

use App\Livewire\AdminDashboard;
use App\Models\User;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();
    $this->bootGame();
});

// @todo: test that this route is protected
it('renders successfully for admin but not others', function () {
    Livewire::actingAs($this->caleb->user)
        ->test(AdminDashboard::class, ['game' => $this->game])
        ->assertStatus(403);
})->skip();

it('allows admins to approve new players', function () {
    expect($this->admin->state()->is_admin_for)->toHaveCount(1);

    $this->getUnapprovedUser();

    Livewire::actingAs($this->admin)
        ->test(AdminDashboard::class, ['game' => $this->game])
        ->set('user_id', $this->unapproved_user->id)
        ->call('approve');

    expect($this->unapproved_user->state()->current_game_id)->not->toBeNull();

    expect($this->game->state()->usersAwaitingApproval())->toHaveCount(0);

    expect($this->game->state()->player_ids)->toContain($this->unapproved_user->state()->current_player_id);
});

it('allows admins to reject new players', function () {
    $this->getUnapprovedUser();

    Livewire::actingAs($this->admin)
        ->test(AdminDashboard::class, ['game' => $this->game])
        ->set('user_id', $this->unapproved_user->id)
        ->call('reject');

    expect($this->unapproved_user->state()->current_game_id)->toBeNull();

    expect($this->unapproved_user->state()->current_player_id)->toBeNull();

    expect($this->game->state()->usersAwaitingApproval())->toHaveCount(0);
});
