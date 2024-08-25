<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\States\UserState;
use Glhd\Bits\Database\HasSnowflakes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasSnowflakes, Notifiable;

    protected $fillable = [
        'id',
        'is_admin',
        'name',
        'email',
        'password',
        'status',
        'referrer_player_id',
        'player_id',
        'current_game_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'id' => 'int',
            'player_id' => 'int',
        ];
    }

    public function state()
    {
        return UserState::load($this->id);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function currentPlayer(): ?Player
    {
        return $this->currentGame()?->players->firstWhere('user_id', $this->id);
    }

    public function currentGame(): ?Game
    {
        return Game::find($this->current_game_id);
    }

    public function referringPlayer(): ?Player
    {
        return Player::firstWhere('id', $this->referrer_player_id);
    }

    public function stateApprovable($q)
    {
        $q->where([
            'rejected' => false,
            'current_game_id' => null,
        ]);
    }
}
