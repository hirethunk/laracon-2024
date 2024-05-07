<?php

namespace App\Models;

use App\Models\Player;
use App\States\GameState;
use Glhd\Bits\Database\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory, HasSnowflakes;

    protected $guarded = [];

    public function state()
    {
        return GameState::load($this->id);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
