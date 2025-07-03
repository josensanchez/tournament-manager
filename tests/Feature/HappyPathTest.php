<?php

use App\Models\States\MatchGame\Finished;
use App\Models\States\MatchGame\InProgress as MatchGameInProgress;
use App\Models\States\Tournament\Created;
use App\Models\States\Tournament\InProgress;
use App\Models\States\Tournament\Ready;
use App\Models\States\Tournament\Registering;
use App\Models\States\Tournament\TournamentFinished;
use App\Models\Tournament;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('tournament happy path', function () {
    // 1. Create a tournament
    $tournamentData = [
        'name' => 'Test Tournament',
        'gender' => 'male',
        'start_date' => '2025-08-01',
    ];

    $response = $this->postJson('/api/tournaments', $tournamentData);
    $response->assertStatus(201);
    $tournament = Tournament::first();
    expect($tournament)->not->toBeNull();
    expect($tournament->state::$name)->toEqual(Created::$name);

    // 2. Open it for registration (transition to 'registering' state)
    $response = $this->patchJson("/api/tournaments/{$tournament->id}", [
        'state' => 'Registering',
    ]);
    $response->assertStatus(200);
    $tournament->refresh();
    expect($tournament->state::$name)->toEqual(Registering::$name);

    // 3. Register 4 players
    for ($i = 1; $i <= 4; $i++) {
        $playerData = [
            'name' => "Player {$i}",
            'email' => "player{$i}@example.com",
        ];
        $response = $this->postJson("/api/tournaments/{$tournament->id}/players", $playerData);
        $response->assertStatus(201);
    }
    expect($tournament->players)->toHaveCount(4);

    // 4. Move the tournament to 'ready' state
    $response = $this->patchJson("/api/tournaments/{$tournament->id}", [
        'state' => 'Ready',
    ]);
    $response->assertStatus(200);
    $tournament->refresh();
    expect($tournament->state::$name)->toEqual(Ready::$name);

    // 5. Move the tournament to InProgress and Generate matches
    $response = $this->patchJson("/api/tournaments/{$tournament->id}", [
        'state' => 'In Progress',
    ]);
    $response->assertStatus(200);
    $tournament->refresh();
    expect($tournament->state::$name)->toEqual(InProgress::$name);
    expect($tournament->matches)->toHaveCount(2); // 4 players should generate 2 matches

    // 6. Move Matches to Finished state and check that the final match is generated
    foreach ($tournament->matches as $match) {
        $response = $this->patchJson("/api/tournaments/{$tournament->id}/match/{$match->id}", [
            'state' => 'In Progress',
        ]);
        $match->refresh();
        $response->assertStatus(200);
        expect($match->state::$name)->toEqual(MatchGameInProgress::$name);

        $response = $this->patchJson("/api/tournaments/{$tournament->id}/match/{$match->id}", [
            'state' => 'Finished',
            'score' => ['6-0', '1-6', '6-1', '7-6', '2-6'],
        ]);
        $match->refresh();
        $response->assertStatus(200);
        expect($match->state::$name)->toEqual(Finished::$name);
    }

    $tournament->refresh();
    expect($tournament->matches)->toHaveCount(3); // 2 matches + 1 final match
    expect($tournament->matches->last()->stage)->toEqual(2); // Final match should be stage 2

    expect($tournament->players->where('state', 'Eliminated'))->toHaveCount(2); // 2 players should be eliminated
    expect($tournament->players->where('state', 'Playing'))->toHaveCount(2); // 2 players should be still playing

    // 7. Transition tournament to Finished state
    $finalMatch = $tournament->matches->last();

    $response = $this->patchJson("/api/tournaments/{$tournament->id}/match/{$finalMatch->id}", [
        'state' => 'In Progress',
    ]);
    $finalMatch->refresh();
    $response->assertStatus(200);
    expect($finalMatch->state::$name)->toEqual(MatchGameInProgress::$name);

    $response = $this->patchJson("/api/tournaments/{$tournament->id}/match/{$finalMatch->id}", [
        'state' => 'Finished',
        'score' => ['6-0', '1-6', '6-1', '7-6', '2-6'],
    ]);
    $finalMatch->refresh();
    $response->assertStatus(200);
    expect($finalMatch->state::$name)->toEqual(Finished::$name);

    $tournament->refresh();

    expect($tournament->players->where('state', 'Eliminated'))->toHaveCount(3); // 2 players should be eliminated
    expect($tournament->players->where('state', 'Playing'))->toHaveCount(0); // 0 players should be still playing
    expect($tournament->players->where('state', 'Winner'))->toHaveCount(1); // 1 player should be THE WINNER
    expect($tournament->state::$name)->toEqual(TournamentFinished::$name);
});
