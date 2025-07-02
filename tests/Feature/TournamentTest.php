<?php

use App\Models\Tournament;

covers(App\Http\Controllers\TournamentController::class);

test('get all tournaments', function () {
    Tournament::factory()->count(21)->create();
    $response = $this->get('/api/tournaments');
    expect($response->content())
        ->json()
        ->current_page->toBe(1)
        ->data->toHaveCount(15);
});
