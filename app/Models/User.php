<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Player;
use App\States\UserState;
use Glhd\Bits\Database\HasSnowflakes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasSnowflakes;

    protected $fillable = [
        'id',
        'is_admin',
        'name',
        'email',
        'password',
        'status',
        'referrer_player_id',
        'player_id',
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

    public function isApproved(): Attribute
    {
        return new Attribute(function () {
            return $this->status === 'approved';
        });
    }

    public function scopeUnapproved(Builder $query): void
    {
        $query->whereNot('status', '=', 'approved');
    }

    public function state()
    {
        return UserState::load($this->id);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
