<?php

covers(App\Http\Controllers\TournamentController::class);

test('get all tournaments', function () {
    $response = $this->get('/api/tournaments');

    $response->assertStatus(200)->assertJson(['message' => 'Tournaments retrieved successfully', 'data' => []]);
});
