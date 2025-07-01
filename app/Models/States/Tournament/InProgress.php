<?php

namespace App\Models\States\Tournament;

use App\Models\States\TournamentState;

class InProgress extends TournamentState
{
    public static function name(): string
    {
        return 'in Progress';
    }
}
