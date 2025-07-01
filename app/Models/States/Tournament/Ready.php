<?php

namespace App\Models\States\Tournament;

use App\Models\States\TournamentState;

class Ready extends TournamentState
{
    public static function name(): string
    {
        return 'Ready';
    }
}
