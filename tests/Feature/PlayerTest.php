<?php

use App\Http\Controllers\PlayerController;
use App\Models\Tournament;

covers(PlayerController::class);

describe('PlayerController', function () {
    it('should allow a player to Register to a Tournament', function () {
        //
        $tournament = Tournament::factory()->create(['state' => 'Registering']);
        $playerData = [
            'name' => 'Player 5',
            'email' => 'player5@example.com',
        ];
        $response = $this->postJson("/api/tournaments/{$tournament->id}/players", $playerData);
        $response->assertStatus(201);
        expect($response->content())
            ->json()
            ->name->toBe('Player 5');
    });

    it('should not allow a player to Register to a Tournament', function () {
        //
        $tournament = Tournament::factory()->create(['state' => 'Ready']);
        $playerData = [
            'name' => 'Player 5',
            'email' => 'player5@example.com',
        ];
        $response = $this->postJson("/api/tournaments/{$tournament->id}/players", $playerData);
        $response->assertStatus(403);
        expect($response->content())
            ->json()
            ->error->toBe('Cannot add players to this tournament.');
    });
});
