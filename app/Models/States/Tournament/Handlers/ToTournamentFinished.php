<?php

namespace App\Models\States\Tournament\Handlers;

use App\Models\Tournament;
use Spatie\ModelStates\Transition;

class ToTournamentFinished extends Transition
{
    public function __construct(private Tournament $tournament) {}

    public function handle(): Tournament
    {
        $this->tournament->state = new ToTournamentFinished($this->tournament);

        return $this->tournament;
    }
}
