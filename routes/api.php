<?php

use App\Http\Controllers\MatchGameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentSimulationController;
use Illuminate\Support\Facades\Route;

Route::resource('tournaments', TournamentController::class);
Route::patch('tournaments/{tournament}', [TournamentController::class, 'transitionState']);
Route::post('tournaments/{tournament}/players', [PlayerController::class, 'store']);
Route::patch('tournaments/{tournament}/match/{matchGame}', [MatchGameController::class, 'transitionState']);
Route::post('tournaments-sims', [TournamentSimulationController::class, 'store']);
