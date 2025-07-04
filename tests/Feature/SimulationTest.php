<?php

use App\Http\Controllers\TournamentSimulationController;

covers(TournamentSimulationController::class);

test('simulate a tournament', function () {
    // 1. generate 16 players
    $players = [];
    for ($i = 1; $i <= 16; $i++) {
        $players[] = [
            'name' => "Player {$i}",
            'email' => "player{$i}@example.com",
            'hability' => rand(1, 100),
            'strength' => rand(1, 100),
            'speed' => rand(1, 100),
            'gender' => 'male',
        ];
    }
    $response = $this->post('/api/tournaments-sims', [
        'name' => 'Simulated Tournament',
        'start_date' => '2025-08-01',
        'gender' => 'male',
        'players' => $players,
    ]);

    $response->assertStatus(200);
});
