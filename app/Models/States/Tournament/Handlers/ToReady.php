<?php

namespace App\Models\States\Tournament\Handlers;

use App\Models\Tournament;
use Exception;
use Spatie\ModelStates\Transition;

class ToReady extends Transition
{
    public function __construct(private Tournament $tournament) {}

    public function handle(): Tournament
    {
        if (! is_power_of_two($this->tournament->players()->count())) {
            throw new Exception('A tournament must have exactly 2^n players to be ready.');
        }

        return $this->tournament;
    }
}
