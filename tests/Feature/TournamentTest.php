<?php

use App\Models\Tournament;

covers(App\Http\Controllers\TournamentController::class);

describe('Tournaments', function () {
    it('get all tournaments', function () {
        Tournament::factory()->count(21)->create();
        $response = $this->get('/api/tournaments');
        expect($response->content())
            ->json()
            ->current_page->toBe(1)
            ->data->toHaveCount(15);
    });

    it('should transition tournament from created to registering', function () {
        $tournament = Tournament::factory()->create();

        $response = $this->patch("/api/tournaments/{$tournament->id}", ['state' => 'Registering']);

        $response->assertOk();
    });

    it('should not move from created to ready', function () {
        $tournament = Tournament::factory()->create();

        $response = $this->patch("/api/tournaments/{$tournament->id}", ['state' => 'Ready']);

        $response->assertStatus(422);
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
    });
});
