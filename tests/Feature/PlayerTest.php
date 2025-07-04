<?php

use App\Http\Controllers\PlayerController;
use App\Models\Tournament;

covers(PlayerController::class);

describe('PlayerController', function () {
    it('should allow a player to Register to a Tournament', function () {
        //
        $tournament = Tournament::factory()->create(['state' => 'Registering', 'gender' => 'female']);
        $playerData = [
            'name' => 'Player 5',
            'email' => 'player5@example.com',
            'hability' => rand(1, 100),
            'strength' => rand(1, 100),
            'speed' => rand(1, 100),
            'gender' => 'female',
        ];
        $response = $this->postJson("/api/tournaments/{$tournament->id}/players", $playerData);
        $response->assertStatus(201);
        expect($response->content())
            ->json()
            ->name->toBe('Player 5');
    });

    it('should not allow a player to Register to a Tournament', function () {
        //
        $tournament = Tournament::factory()->create(['state' => 'Ready', 'gender' => 'female']);
        $playerData = [
            'name' => 'Player 5',
            'email' => 'player5@example.com',
            'hability' => rand(1, 100),
            'strength' => rand(1, 100),
            'speed' => rand(1, 100),
            'gender' => 'female',
        ];
        $response = $this->postJson("/api/tournaments/{$tournament->id}/players", $playerData);
        $response->assertStatus(422);
        expect($response->content())
            ->json()
            ->error->toBe('Cannot add players to this tournament.');
    });

    it('should not allow a female player to Register to male Tournament', function () {
        //
        $tournament = Tournament::factory()->create(['state' => 'Registering', 'gender' => 'male']);
        $playerData = [
            'name' => 'Player 5',
            'email' => 'player5@example.com',
            'hability' => rand(1, 100),
            'strength' => rand(1, 100),
            'speed' => rand(1, 100),
            'gender' => 'female',
        ];
        $response = $this->postJson("/api/tournaments/{$tournament->id}/players", $playerData);
        $response->assertStatus(422);
        expect($response->content())
            ->json()
            ->error->toBe('Player `Player 5` (female) cannot be added to this tournament (male).');
    });
});
