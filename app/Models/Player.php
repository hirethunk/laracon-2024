<?php

namespace App\Models;

use App\Models\Game;
use App\Models\User;
use App\States\PlayerState;
use Glhd\Bits\Database\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory, HasSnowflakes;

    protected $guarded = [];

    protected $casts = [
        'last_voted_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function state()
    {
        return PlayerState::load($this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
