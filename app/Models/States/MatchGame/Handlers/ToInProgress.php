<?php

namespace App\Models\States\MatchGame\Handlers;

use App\Models\MatchGame;
use App\Models\States\MatchGame\InProgress;
use App\Models\States\Player\Playing;
use Spatie\ModelStates\Transition;

class ToInProgress extends Transition
{
    public function __construct(private MatchGame $matchGame) {}

    public function handle(): MatchGame
    {
        if ($this->matchGame->firstPlayer) {
            $this->matchGame->firstPlayer->state->transitionTo(Playing::class);
        }
        if ($this->matchGame->secondPlayer) {
            $this->matchGame->secondPlayer->state->transitionTo(Playing::class);
        }

        $this->matchGame->state = new InProgress($this->matchGame);

        return $this->matchGame;
    }
}
