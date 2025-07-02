<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tournament $tournament): JsonResponse
    {
        // @phpstan-ignore argument.type
        $player = $tournament->players()->create($request->all());

        return response()->json($player, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player): void
    {
        //
    }
}
