<?php

use App\Models\Game;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;
use App\Livewire\SecretAlliancePage;
use App\Events\PlayerEnteredSecretCode;
use App\Events\PlayerEnteredAllianceCode;

beforeEach(function () {
    Verbs::commitImmediately();

    $this->bootGame();

    $this->game = Game::first();
});

it('assigns a random alliance when you visit the page', function () {
    expect($this->taylor->state()->ally())->toBeNull();

    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
    ]);

    expect($this->taylor->state()->ally())->not->toBeNull();

    $ally = $this->taylor->state()->ally();

    expect($ally->ally_id)->toBe($this->taylor->id);
});

it('gives you an upvote for connecting with your ally', function() {
    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
    ]);

    $ally = $this->taylor->state()->ally();

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
        ])
        ->set('code', $ally->code_to_give_to_ally)
        ->call('connectWithAlly');

    $this->actingAs($ally->model()->user);

    Livewire::test(SecretAlliancePage::class, [
            'player' => $ally->model(),
        ])
        ->set('code', $this->taylor->state()->code_to_give_to_ally)
        ->call('connectWithAlly');

    expect($this->taylor->state()->upvotes)->toHaveCount(1);
    expect($this->taylor->state()->score())->toBe(1);
    expect($this->taylor->state()->has_connected_with_ally)->toBeTrue();

    expect($ally->upvotes)->toHaveCount(1);
    expect($ally->score())->toBe(1);
    expect($ally->has_connected_with_ally)->toBeTrue();
});

it('gives allies upvotes for cooperating', function() {
    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
    ]);

    $ally = $this->taylor->state()->ally();

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
        ])
        ->set('code', $ally->code_to_give_to_ally)
        ->call('connectWithAlly');

    $this->actingAs($ally->model()->user);

    Livewire::test(SecretAlliancePage::class, [
            'player' => $ally->model(),
        ])
        ->set('code', $this->taylor->state()->code_to_give_to_ally)
        ->call('connectWithAlly');

    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
        ])
        ->call('playNice');

    $this->actingAs($ally->model()->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $ally->model(),
        ])
        ->call('playNice');

    expect($this->taylor->state()->score())->toBe(3);
    expect($this->taylor->state()->prisoners_dilemma_choice)->toBe('nice');

    expect($ally->score())->toBe(3);
    expect($ally->prisoners_dilemma_choice)->toBe('nice');
});

it('gives allies downvotes for both being nasty', function() {
    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
    ]);

    $ally = $this->taylor->state()->ally();

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
        ])
        ->set('code', $ally->code_to_give_to_ally)
        ->call('connectWithAlly');

    $this->actingAs($ally->model()->user);

    Livewire::test(SecretAlliancePage::class, [
            'player' => $ally->model(),
        ])
        ->set('code', $this->taylor->state()->code_to_give_to_ally)
        ->call('connectWithAlly');

    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
        ])
        ->call('playNasty');

    $this->actingAs($ally->model()->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $ally->model(),
        ])
        ->call('playNasty');

    expect($this->taylor->state()->score())->toBe(-1);
    expect($this->taylor->state()->prisoners_dilemma_choice)->toBe('nasty');

    expect($ally->score())->toBe(-1);
    expect($ally->prisoners_dilemma_choice)->toBe('nasty');
});

it('gives ally 5 upvotes for being nasty when ally was nice', function() {
    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
    ]);

    $ally = $this->taylor->state()->ally();

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
        ])
        ->set('code', $ally->code_to_give_to_ally)
        ->call('connectWithAlly');

    $this->actingAs($ally->model()->user);

    Livewire::test(SecretAlliancePage::class, [
            'player' => $ally->model(),
        ])
        ->set('code', $this->taylor->state()->code_to_give_to_ally)
        ->call('connectWithAlly');

    $this->actingAs($this->taylor->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $this->taylor,
        ])
        ->call('playNasty');

    $this->actingAs($ally->model()->user);

    Livewire::test(SecretAlliancePage::class, [
        'player' => $ally->model(),
        ])
        ->call('playNice');

    expect($this->taylor->state()->score())->toBe(6);
    expect($this->taylor->state()->prisoners_dilemma_choice)->toBe('nasty');

    expect($ally->score())->toBe(1);
    expect($ally->prisoners_dilemma_choice)->toBe('nice');
});