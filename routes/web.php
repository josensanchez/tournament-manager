<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return Http::get('http://host.docker.internal/api/tournaments')
    //     ->json();
    return view('welcome');
});

Route::get('health', function () {
    return response()->json(['status' => 'ok']);
});
