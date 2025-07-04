<?php

namespace App\Models\States\Tournament\Handlers;

use App\Models\States\Tournament\TournamentFinished;
use App\Models\Tournament;
use Spatie\ModelStates\Transition;

class ToTournamentFinished extends Transition
{
    public function __construct(private Tournament $tournament) {}

    public function handle(): Tournament
    {
        $this->tournament->end_date = now();
        $this->tournament->state = new TournamentFinished($this->tournament);

        return $this->tournament;
    }
}
