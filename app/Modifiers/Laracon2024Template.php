<?php

namespace App\Modifiers;

use App\States\GameState;
use Illuminate\Support\Carbon;

class Laracon2024Template
{
    public Carbon $starts_at;

    public function __construct(GameState $game)
    {
        $this->starts_at = $game->starts_at;
    }

    public function modifiers() 
    {
        // assume game starts at 5am the first day of the conference
        return [
            [
                'slug' => 'signing-bonus',
                'title' => 'Signing Bonus',
                'description' => 'If you refer a player who joins the game while Signing Bonus is active, no one can downvote you for 1 hour.',
                // starts at 5am the first day
                'starts_at' => $this->starts_at->copy(),
                // ends at 1pm of the first day
                'ends_at' => $this->starts_at->copy()->addHours(8),
            ],
            [
                'slug' => 'double-down',
                'title' => 'Double Down',
                'description' => 'Votes from ballots count double.',
                // starts at 1pm of the first day
                'starts_at' => $this->starts_at->copy()->addHours(8),
                // ends at 5pm of the first day
                'ends_at' => $this->starts_at->copy()->addHours(12),
            ],
            [
                'slug' => 'buddy-system',
                'title' => 'Buddy System',
                'description' => 'If you and another player upvote each other while Buddy System is active, you will each receive an extra upvote (only works once per Buddy).',
                // starts at 5pm of the first day
                'starts_at' => $this->starts_at->copy()->addHours(12),
                // ends at 5am of the final day
                'ends_at' => $this->starts_at->copy()->addHours(24),
            ],
            [
                'slug' => 'first-shall-be-last',
                'title' => 'The First Shall Be Last',
                'description' => 'You cannot upvote players with positive scores, or downvote players with negative scores.',
                // starts at 5am of the final day
                'starts_at' => $this->starts_at->copy()->addHours(24),
                // ends at 12pm of the final day
                'ends_at' => $this->starts_at->copy()->addHours(31),
            ],
            [
                'slug' => 'blackout',
                'title' => 'Blackout',
                'description' => 'Votes from ballots do not count.',
                // starts at 12pm of the final day
                'starts_at' => $this->starts_at->copy()->addHours(31),
                // ends at 2pm of the final day
                'ends_at' => $this->starts_at->copy()->addHours(33),
            ],
            [
                'slug' => 'double-down',
                'title' => 'Double Down',
                'description' => 'Votes from ballots count double.',
                // starts at 2pm of the final day
                'starts_at' => $this->starts_at->copy()->addHours(33),
                // ends at 5pm of the final day
                'ends_at' => $this->starts_at->copy()->addHours(36),
            ],
        ];
    }
}