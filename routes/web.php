<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('swagger');
});


Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
