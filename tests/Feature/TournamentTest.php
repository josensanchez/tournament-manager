<?php

use App\Http\Controllers\TournamentController;
use App\Models\MatchGame;
use App\Models\Player;
use App\Models\Tournament;

covers(TournamentController::class);

describe('Tournaments', function () {
    it('get all tournaments', function () {
        Tournament::factory()->count(21)->create();
        $response = $this->get('/api/tournaments');
        expect($response->content())
            ->json()
            ->current_page->toBe(1)
            ->data->toHaveCount(15);
    });

    it('should create a tournament', function () {
        $tournamentData = [
            'name' => 'Test Tournament',
            'gender' => 'male',
            'start_date' => '2025-08-01',
            'gender' => 'male',
        ];

        $response = $this->post('/api/tournaments', $tournamentData);
        $response->assertStatus(201);
        expect($response->content())
            ->json()
            ->name->toBe('Test Tournament');
    });

    it('should transition tournament from created to registering', function () {
        $tournament = Tournament::factory()->create();

        $response = $this->patch("/api/tournaments/{$tournament->id}", ['state' => 'Registering']);

        $response->assertStatus(200);
    });

    it('should not move from created to ready', function () {
        $tournament = Tournament::factory()->create();

        $response = $this->patch("/api/tournaments/{$tournament->id}", ['state' => 'Ready']);

        $response->assertStatus(403);
    });

    it('should not transition tournament from registering to ready if there is no enought players registered', function () {
        $tournament = Tournament::factory()
            ->hasPlayers(3)
            ->create(['state' => 'Registering']);

        $response = $this->patch("/api/tournaments/{$tournament->id}", ['state' => 'Ready']);

        $response->assertStatus(422);
    });

    it('should transition tournament from registering to ready if there is enough players registered', function () {
        $tournament = Tournament::factory()
            ->hasPlayers(4)
            ->create(['state' => 'Registering']);

        $response = $this->patch("/api/tournaments/{$tournament->id}", ['state' => 'Ready']);

        $response->assertOk();
        expect($response->content())
            ->json()
            ->message->toBe('Tournament state updated successfully')
            ->data->state->toBe('Ready');
    });

    it('should show a tournament full data', function () {
        $tournament = Tournament::factory()->create();
        $players = Player::factory()->count(2)->create([
            'tournament_id' => $tournament->id,
            'gender' => $tournament->gender,
        ]);
        MatchGame::factory()->create([
            'tournament_id' => $tournament->id,
            'first_player_id' => $players[0]->id,
            'second_player_id' => $players[1]->id,
            'stage' => 2,
        ]);

        $response = $this->get("/api/tournaments/{$tournament->id}");
        $response->assertOk();
        expect($response->content())
            ->json()
            ->matches->toHaveCount(1)
            ->players->toHaveCount(2);
    });
});
