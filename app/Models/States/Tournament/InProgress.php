<?php

namespace App\Models\States\Tournament;

class InProgress extends TournamentState
{
    public static function name(): string
    {
        return 'in Progress';
    }
}
