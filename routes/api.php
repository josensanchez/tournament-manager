<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::resource('tournaments', TournamentController::class);
Route::patch('tournaments/{tournament}', [TournamentController::class, 'transitionState']);
Route::post('tournaments/{tournament}/players', [PlayerController::class, 'store']);
