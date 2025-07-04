<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\States\MatchGame\Finished;
use App\Models\States\MatchGame\InProgress as MatchGameInProgress;
use App\Models\States\Tournament\InProgress;
use App\Models\States\Tournament\Ready;
use App\Models\States\Tournament\Registering;
use App\Models\Tournament;
use Exception;

class SimulateService
{
    public function __construct(
        protected TournamentService $tournamentService,
        protected MatchGameService $matchGameService,
        protected PlayerService $playerService
    ) {}

    /**
     * Simulate a tournament based on the provided data.
     *
     * @param  SimulationPayload  $data
     */
    public function simulateTournament(array $data): Tournament
    {
        $tournament = Tournament::create($data);

        $this->tournamentService->transitionTo(Registering::class, $tournament);
        foreach ($data['players'] as $playerData) {
            $this->playerService->createPlayer($playerData, $tournament);
        }
        $this->tournamentService->transitionTo(Ready::class, $tournament);
        $this->tournamentService->transitionTo(InProgress::class, $tournament);

        $tournamentStage = count($data['players']);

        while ($tournamentStage > 1) {
            $tournament->refresh();
            $activeGames = $tournament->matches()->where('stage', '=', $tournamentStage)->get();

            foreach ($activeGames as $match) {
                // Simulate match logic here, e.g., randomly determine a winner
                $this->matchGameService->transitionTo(MatchGameInProgress::class, null, $match);

                // Simulate match outcome
                $score = $this->simulateMatchOutcome($match);
                $this->matchGameService->transitionTo(Finished::class, $score, $match);
            }
            $tournamentStage /= 2;
        }

        $tournament->refresh();
        $tournament->load('matches', 'players');

        return $tournament;
    }

    /**
     * Simulate the outcome of a match.
     *
     * @return array<int, string> The score of the match in the format ['6-0', '6-1', '6-2']
     *
     * @throws Exception
     */
    protected function simulateMatchOutcome(MatchGame $match): array
    {
        if (! $match->firstPlayer || ! $match->secondPlayer) {
            throw new Exception('Match must have two players to simulate.');
        }
        $firstPlayerStat = $match->firstPlayer->stats();
        $secondPlayerStats = $match->secondPlayer->stats();

        $score = [];
        for ($i = 0; $i < 3; $i++) {
            $firstPlayerScore = rand(1, $firstPlayerStat);
            $secondPlayerScore = rand(1, $secondPlayerStats);
            if ($firstPlayerScore > $secondPlayerScore) {
                $score[] = '6-' . (int) ($secondPlayerScore * 5 / $firstPlayerScore); // First player wins this round
            } else {
                $score[] = (int) ($firstPlayerScore * 5 / $secondPlayerScore) . '-6'; // Second player wins this round
            }
        }

        return $score;
    }
}
