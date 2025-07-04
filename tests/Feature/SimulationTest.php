<?php

use App\Http\Controllers\TournamentSimulationController;
use App\Models\States\Tournament\TournamentFinished;
use App\Models\Tournament;

covers(TournamentSimulationController::class);

describe('Tournament Simulation', function () {
    it('should simulate a tournament with valid data', function () {
        // 1. generate 16 players
        $players = [];
        for ($i = 1; $i <= 16; $i++) {
            $players[] = [
                'name' => "Player {$i}",
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

        expect($response->content())
            ->json()
            ->name->toBe('Simulated Tournament')
            ->end_date->not->toBeNull()
            ->state->toBe(TournamentFinished::$name)
            ->matches->toHaveCount(8 + 4 + 2 + 1) // 8 first round, 4 second round, 2 semi-finals, 1 final
            ->players->toHaveCount(16);
    });

    it('should return 422 for invalid tournament data', function () {
        //
        expect(Tournament::count())->toBe(0);
        $players = [];
        for ($i = 1; $i <= 16; $i++) {
            $players[] = [
                'name' => "Player {$i}",
                'hability' => rand(1, 100),
                'strength' => rand(1, 100),
                'speed' => rand(1, 100),
                'gender' => 'female',
            ];
        }
        $players[5]['gender'] = 'male'; // Invalid player gender for this tournament
        $response = $this->post('/api/tournaments-sims', [
            'name' => 'Simulated Tournament',
            'start_date' => '2025-08-01',
            'gender' => 'female',
            'players' => $players,
        ]);
        $response->assertStatus(422);

        expect($response->content())
            ->json()
            ->error->toBe('Player `Player 6` (male) cannot be added to this tournament (female).');
        expect(Tournament::count())->toBe(0);
    });
    it('should return 422 for invalid tournament data: not enough players', function () {
        expect(Tournament::count())->toBe(0);
        $players = [];
        for ($i = 1; $i <= 15; $i++) {
            $players[] = [
                'name' => "Player {$i}",
                'hability' => rand(1, 100),
                'strength' => rand(1, 100),
                'speed' => rand(1, 100),
                'gender' => 'female',
            ];
        }

        $response = $this->post('/api/tournaments-sims', [
            'name' => 'Simulated Tournament',
            'start_date' => '2025-08-01',
            'gender' => 'female',
            'players' => $players,
        ]);
        $response->assertStatus(422);

        expect($response->content())
            ->json()
            ->error->toBe('A tournament must have exactly 2^n players to be ready.');
        expect(Tournament::count())->toBe(0);
    });
});
