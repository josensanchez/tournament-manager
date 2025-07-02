<?php

namespace App\Services;

use App\Models\MatchGame;

class MatchGameService
{
    public function transitionTo(string $state, ?int $score, MatchGame $match): MatchGame
    {
        // @phpstan-ignore method.nonObject
        $match->state->transitionTo($state, $score);

        // Save the game
        $match->save();

        return $match;
    }
}
