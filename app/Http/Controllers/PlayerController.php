<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlayerRequest;
use App\Models\Player;
use App\Models\Tournament;
use App\Services\PlayerService;
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
    public function store(PlayerService $service, CreatePlayerRequest $request, Tournament $tournament): JsonResponse
    {
        /** @var PlayerPayload $data */
        $data = $request->validated();
        $player = $service->createPlayer($data, $tournament);

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
