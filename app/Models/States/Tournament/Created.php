<?php

namespace App\Models\States\Tournament;

use App\Models\States\TournamentState;

class Created extends TournamentState
{
    public static function name(): string
    {
        return 'created';
    }
}
