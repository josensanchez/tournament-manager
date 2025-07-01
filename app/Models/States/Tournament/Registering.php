<?php

namespace App\Models\States\Tournament;

use App\Models\States\TournamentState;

class Registering extends TournamentState
{
    public static function name(): string
    {
        return 'Registering';
    }
}
