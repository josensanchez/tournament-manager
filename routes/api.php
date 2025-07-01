<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::resource('tournament', TournamentController::class);
