<?php

namespace App\Models\States\Tournament\Handlers;

use App\Models\States\Tournament\Ready;
use App\Models\Tournament;
use Spatie\ModelStates\Exceptions\TransitionNotAllowed;
use Spatie\ModelStates\Transition;

class ToReady extends Transition
{
    public function __construct(private Tournament $tournament) {}

    public function handle(): Tournament
    {
        if (! is_power_of_two($this->tournament->players()->count())) {
            throw new TransitionNotAllowed('A tournament must have exactly 2^n players to be ready.');
        }
        $this->tournament->state = new Ready($this->tournament);

        return $this->tournament;
    }
}
