<?php

namespace App\Services;

use App\Models\Tournament;

class TournamentService
{
    public function transitionTo(string $state, Tournament $tournament): Tournament
    {
        // @phpstan-ignore method.nonObject
        $tournament->state->transitionTo($state);

        // Save the tournament
        $tournament->save();

        return $tournament;
    }
}
