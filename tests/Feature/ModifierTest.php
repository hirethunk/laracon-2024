<?php

use App\Events\AdminApprovedNewPlayer;
use App\Events\PlayerVoted;
use App\Events\UserAddedReferral;
use App\Events\UserCreated;
use App\Livewire\PlayerDashboard;
use App\Livewire\VotingCard;
use App\Models\Game;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();

    // testTime()->addMinutes(1);
});

it('selects modifiers at the correct times', function () {
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

it('hides referrers from downvote options after signing bonus', function () {
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

    expect($this->taylor->state()->cannotBeDownvoted())->toBeTrue();

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($downvote_options->pluck('id'))
        ->not()->toContain($this->taylor->id);

    testTime()->addHours(1);

    expect($this->taylor->state()->cannotBeDownvoted())->toBeFalse();

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($downvote_options->pluck('id'))
        ->toContain($this->taylor->id);

    // this doesn't work after the signing bonus period

    testTime()->addHours(24);

    expect($this->game->state()->activeModifier()['slug'])
        ->not()->toBe('signing-bonus');

    $new_user_id = UserCreated::fire(
        name: 'bogdan',
        email: 'bogdan@katz.nachi',
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

    expect($this->taylor->state()->cannotBeDownvoted())->toBeFalse();

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($downvote_options->pluck('id'))
        ->toContain($this->taylor->id);
});

it('doubles ballot votes with double down', function () {
    testTime()->addHours(8);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('double-down');

    PlayerVoted::fire(
        player_id: $this->caleb->id,
        upvotee_id: $this->taylor->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    expect($this->taylor->state()->score)->toBe(2);
    expect($this->aaron->state()->score)->toBe(-2);

    testTime()->addHours(4);

    expect($this->game->state()->activeModifier()['slug'])
        ->not()->toBe('double-down');

    PlayerVoted::fire(
        player_id: $this->caleb->id,
        upvotee_id: $this->taylor->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    expect($this->taylor->state()->score)->toBe(3);
    expect($this->aaron->state()->score)->toBe(-3);
});

it('gives bonus votes for buddy system', function () {
    testTime()->addHours(12);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('buddy-system');

    PlayerVoted::fire(
        player_id: $this->caleb->id,
        upvotee_id: $this->taylor->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    // the initial ballot has a normal effect
    expect($this->taylor->state()->score)->toBe(1);
    expect($this->caleb->state()->score)->toBe(0);
    expect($this->aaron->state()->score)->toBe(-1);

    PlayerVoted::fire(
        player_id: $this->taylor->id,
        upvotee_id: $this->caleb->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    // taylor's ballot gives him and caleb bonus votes
    expect($this->taylor->state()->score)->toBe(3);
    expect($this->caleb->state()->score)->toBe(3);
    expect($this->aaron->state()->score)->toBe(-2);

    testTime()->addMinutes(61);

    PlayerVoted::fire(
        player_id: $this->taylor->id,
        upvotee_id: $this->caleb->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    // the bonus only applies once per player, so this ballot is normal
    expect($this->taylor->state()->score)->toBe(3);
    expect($this->caleb->state()->score)->toBe(4);
    expect($this->aaron->state()->score)->toBe(-3);

    PlayerVoted::fire(
        player_id: $this->aaron->id,
        upvotee_id: $this->caleb->id,
        downvotee_id: $this->taylor->id,
        game_id: $this->game->id
    );

    PlayerVoted::fire(
        player_id: $this->caleb->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->taylor->id,
        game_id: $this->game->id
    );

    // a player can be in multiple buddy pairs
    expect($this->taylor->state()->score)->toBe(1);
    expect($this->caleb->state()->score)->toBe(7);
    expect($this->aaron->state()->score)->toBe(0);

    testTime()->addHours(19);

    expect($this->game->state()->activeModifier()['slug'])
        ->not()->toBe('buddy-system');

    PlayerVoted::fire(
        player_id: $this->caleb->id,
        upvotee_id: $this->taylor->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    expect($this->taylor->state()->score)->toBe(2);
    expect($this->caleb->state()->score)->toBe(7);
    expect($this->aaron->state()->score)->toBe(-1);

    PlayerVoted::fire(
        player_id: $this->taylor->id,
        upvotee_id: $this->caleb->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    // the buddy system bonus is no longer active
    expect($this->taylor->state()->score)->toBe(2);
    expect($this->caleb->state()->score)->toBe(8);
    expect($this->aaron->state()->score)->toBe(-2);
});

it('first shall be last does not allow upvotes for positive players or downvotes for negative players', function () {
    PlayerVoted::fire(
        player_id: $this->caleb->id,
        upvotee_id: $this->taylor->id,
        downvotee_id: $this->aaron->id,
        game_id: $this->game->id
    );

    expect($this->taylor->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->taylor->state()->cannotBeUpvoted())->toBeFalse();
    expect($this->caleb->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->caleb->state()->cannotBeUpvoted())->toBeFalse();
    expect($this->aaron->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->aaron->state()->cannotBeUpvoted())->toBeFalse();

    testTime()->addHours(24);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('first-shall-be-last');

    expect($this->taylor->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->taylor->state()->cannotBeUpvoted())->toBeTrue();
    expect($this->caleb->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->caleb->state()->cannotBeUpvoted())->toBeFalse();
    expect($this->aaron->state()->cannotBeDownvoted())->toBeTrue();
    expect($this->aaron->state()->cannotBeUpvoted())->toBeFalse();

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($downvote_options->pluck('id'))
        ->toContain($this->taylor->id)
        ->not()->toContain($this->aaron->id);

    $upvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('upvote_options');

    expect($upvote_options->pluck('id'))
        ->toContain($this->aaron->id)
        ->not()->toContain($this->taylor->id);

    // if we even the scores, everyone is in play again
    PlayerVoted::fire(
        player_id: $this->caleb->id,
        upvotee_id: $this->aaron->id,
        downvotee_id: $this->taylor->id,
        game_id: $this->game->id
    );

    expect($this->taylor->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->taylor->state()->cannotBeUpvoted())->toBeFalse();
    expect($this->caleb->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->caleb->state()->cannotBeUpvoted())->toBeFalse();
    expect($this->aaron->state()->cannotBeDownvoted())->toBeFalse();
    expect($this->aaron->state()->cannotBeUpvoted())->toBeFalse();

    $downvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('downvote_options');

    expect($downvote_options->pluck('id'))
        ->toContain($this->taylor->id)
        ->toContain($this->aaron->id);

    $upvote_options = Livewire::test(VotingCard::class, [
        'player' => $this->caleb,
    ])
        ->get('upvote_options');

    expect($upvote_options->pluck('id'))
        ->toContain($this->aaron->id)
        ->toContain($this->taylor->id);
});

it('hides the scoreboard during blackout', function () {
    $this->actingAs($this->caleb->user);

    Livewire::test(PlayerDashboard::class)
        ->assertSet('show_scoreboard', true);

    testTime()->addHours(31);

    expect($this->game->state()->activeModifier()['slug'])
        ->toBe('blackout');

    Livewire::test(PlayerDashboard::class)
        ->assertSet('show_scoreboard', false);

    testTime()->addHours(2);

    expect($this->game->state()->activeModifier()['slug'])
        ->not()->toBe('blackout');

    Livewire::test(PlayerDashboard::class)
        ->assertSet('show_scoreboard', true);
});
