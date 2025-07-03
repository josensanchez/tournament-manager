<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\States\MatchGame\Finished;
use App\Models\States\Player\Winner;
use App\Models\States\Tournament\TournamentFinished;
use App\Models\Tournament;

class TournamentService
{
    public function __construct(protected MatchGameService $matchGameService) {}

    public function transitionTo(string $state, Tournament $tournament): Tournament
    {
        // @phpstan-ignore method.nonObject
        $tournament->state->transitionTo($state);

        // Save the tournament
        $tournament->save();

        return $tournament;
    }

    public function updateTournamentState(MatchGame $matchGame): void
    {
        // Get the tournament associated with the match game
        /** @var Tournament $tournament */
        $tournament = $matchGame->tournament;
        // check if it is the final match game
        if ($matchGame->stage === 2) {
            $this->transitionTo(TournamentFinished::class, $tournament);
            if ($matchGame->winner) {
                $matchGame->winner->state->transitionTo(Winner::class);
            }

            return;
        }

        // Transition the tournament state based on the match game state
        $activeGames = $tournament->matches()->where('stage', '=', $matchGame->stage)->get();

        /** @var int $positionInArray */
        $positionInArray = array_search($matchGame->id, $activeGames->pluck('id')->toArray());

        if ($positionInArray % 2 === 0) {
            /** @var MatchGame $counterPart */
            $counterPart = $activeGames->get($positionInArray + 1);
        } else {
            /** @var MatchGame $counterPart */
            $counterPart = $activeGames->get($positionInArray - 1);
        }
        if (Finished::$name === $counterPart->state::$name) {
            // Nothing to do.
            $this->matchGameService->createNextMatchGame($matchGame, $counterPart);
        }
    }
}
