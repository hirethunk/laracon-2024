<?php

use App\Events\AdminApprovedNewPlayer;
use App\Events\UserAddedReferral;
use App\Models\Game;
use Livewire\Livewire;
use App\Events\UserCreated;
use App\Livewire\VotingCard;
use Thunk\Verbs\Facades\Verbs;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('selects modifiers at the correct times', function() {
    testTime()->addMinutes(1);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('signing-bonus');

    testTime()->addHours(8);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('double-down');

    testTime()->addHours(4);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('buddy-system');

    testTime()->addHours(12);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('first-shall-be-last');

    testTime()->addHours(7);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('blackout');

    testTime()->addHours(2);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('double-down');
});

it('hides referrers from downvote menu after signing bonus', function() {
    testTime()->addMinutes(1);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('signing-bonus');

    $this->actingAs($this->caleb->user);

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($downvote_options->pluck('id'))
        ->toContain($this->taylor->id);

    $new_user_id = UserCreated::fire(
        name: 'Skyler Katz', 
        email: 'skyler@katz.nachi',
        password: bcrypt('password')
    )->user_id;

    UserAddedReferral::fire(
        referrer_player_id: $this->taylor->id,
        user_id: $new_user_id,
        game_id: $this->game->id
    );

    AdminApprovedNewPlayer::fire(
        admin_id: $this->admin->id,
        user_id: $new_user_id,
        game_id: $this->game->id
    );

    testTime()->addMinutes(1);

    expect($this->taylor->state()->isImmune())->toBeTrue();

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($downvote_options->pluck('id'))
        ->not()->toContain($this->taylor->id);
});