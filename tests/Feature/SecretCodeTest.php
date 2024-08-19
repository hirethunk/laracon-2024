<?php

use App\Events\PlayerEnteredSecretCode;
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

it('a player can input a secret code for an upvote', function () {
    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'GO1VCQJ0OQ'
    );

    expect($this->taylor->state()->score)->toBe(1);
});

it('allows player to submit code in livewire component', function () {
    $this->actingAs($this->taylor->user);

    Livewire::test(SecretCodePage::class)
        // ->assertSeeText("Submit")
        ->set('code', 'GO1VCQJ0OQ')
        ->call('submitCode');

    expect($this->taylor->state()->score)->toBe(1);

    Livewire::test(SecretCodePage::class)
        // ->assertSeeText("Submit")
        ->set('code', 'I created Laravel')
        ->call('submitCode');

    expect($this->taylor->state()->score)->toBe(0);

    Livewire::test(SecretCodePage::class)
        ->assertSeeText('bad code');
});

it('does not reward player for using the same code twice', function () {
    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'GO1VCQJ0OQ'
    );

    try {
        PlayerEnteredSecretCode::fire(
            player_id: $this->taylor->id,
            game_id: $this->game->id,
            secret_code: 'GO1VCQJ0OQ'
        );
    } catch (Exception $e) { }

    expect($this->taylor->state()->score)->toBe(1);
});

it('penalizes players for using invalid codes', function () {
    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'I created Laravel'
    );

    expect($this->taylor->state()->score)->toBe(-1);
});

it('does not allow players to enter codes for an hour after using invalid codes', function () {
    PlayerEnteredSecretCode::fire(
        player_id: $this->taylor->id,
        game_id: $this->game->id,
        secret_code: 'I created Laravel'
    );

    expect($this->taylor->state()->canSubmitCode())->toBeFalse();

    Carbon::setTestNow(now()->addMinutes(61));

    expect($this->taylor->state()->canSubmitCode())->toBeTrue();
});
