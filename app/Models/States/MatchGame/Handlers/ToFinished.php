<?php

namespace App\Models\States\MatchGame\Handlers;

use App\Jobs\UpdateTournamentState;
use App\Models\MatchGame;
use App\Models\States\MatchGame\Finished;
use App\Models\States\Player\Eliminated;
use App\Services\MatchGameService;
use Spatie\ModelStates\Transition;

class ToFinished extends Transition
{
    /**
     * Create a new transition instance.
     *
     * @param  array<int, string>  $score
     */
    public function __construct(private MatchGame $matchGame, private array $score) {}

    public function handle(MatchGameService $service): MatchGame
    {
        $winner = $service->getWinner($this->score, $this->matchGame);
        $this->matchGame->state = new Finished($this->matchGame);
        $this->matchGame->winner()->associate($winner);
        $this->matchGame->score = implode(',', $this->score);
        $this->matchGame->save();

        if ($this->matchGame->firstPlayer && $this->matchGame->winner_id !== $this->matchGame->first_player_id) {
            $this->matchGame->firstPlayer->state->transitionTo(Eliminated::class);
        }
        if ($this->matchGame->secondPlayer && $this->matchGame->winner_id !== $this->matchGame->second_player_id) {
            $this->matchGame->secondPlayer->state->transitionTo(Eliminated::class);
        }
        $this->matchGame->state = new Finished($this->matchGame);

        UpdateTournamentState::dispatch($this->matchGame);

        return $this->matchGame;
    }
}
