<?php

namespace App\Jobs;

use App\Models\MatchGame;
use App\Services\TournamentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateTournamentState implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected MatchGame $matchGame) {}

    /**
     * Execute the job.
     */
    public function handle(TournamentService $service): void
    {
        // dd('UpdateTournamentState job executed for MatchGame: ' . $this->matchGame->id);
        // a game has finished, so we need to update the tournament state
        $service->updateTournamentState($this->matchGame);
    }
}
