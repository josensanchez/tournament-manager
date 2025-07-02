<?php

use App\Models\States\Tournament\Created;
use App\Models\States\Tournament\InProgress;
use App\Models\States\Tournament\Ready;
use App\Models\States\Tournament\Registering;
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
        $response->assertStatus(200);
        $response = $this->patchJson("/api/tournaments/{$tournament->id}/match/{$match->id}", [
            'state' => 'Finished',
        ]);
        $response->assertStatus(200);
    }
});
