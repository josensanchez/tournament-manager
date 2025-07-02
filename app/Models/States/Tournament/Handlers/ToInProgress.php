<?php

namespace App\Models\States\Tournament\Handlers;

use App\Models\States\Tournament\InProgress;
use App\Models\Tournament;
use Spatie\ModelStates\Exceptions\TransitionNotAllowed;
use Spatie\ModelStates\Transition;

class ToInProgress extends Transition
{
    public function __construct(private Tournament $tournament) {}

    public function handle(): Tournament
    {
        if (! is_power_of_two($this->tournament->players()->count())) {
            throw new TransitionNotAllowed('A tournament must have exactly 2^n players to be ready.');
        }
        // generate the matches for the tournament
        $this->tournament->generateMatches();

        // Check if the tournament is ready to start
        if ($this->tournament->matches()->count() !== $this->tournament->players()->count() / 2) {
            throw new TransitionNotAllowed('The tournament matches are not fully generated.');
        }

        $this->tournament->state = new InProgress($this->tournament);

        return $this->tournament;
    }
}
