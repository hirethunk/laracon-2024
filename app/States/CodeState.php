<?php

namespace App\States;

use Thunk\Verbs\State;

class CodeState extends State
{
    public string $code;

    public bool $is_used;

    public int $game_id;

    public int $used_by_player_id;
}
