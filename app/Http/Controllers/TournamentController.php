<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTournamentRequest;
use App\Http\Requests\TournamentTransitionRequest;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Fetch all tournaments
        $tournaments = Tournament::paginate();

        // Return the view with tournaments data
        return response()->json($tournaments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTournamentRequest $request): JsonResponse
    {
        $tournament = Tournament::create($request->validated());

        return response()->json($tournament, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament): JsonResponse
    {
        $tournament->load(['matches', 'players']);

        return response()->json($tournament);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }

    /**
     * Transition the state of a tournament.
     */
    public function transitionState(TournamentService $service, TournamentTransitionRequest $request, Tournament $tournament): JsonResponse
    {
        /** @var string $state */
        $state = $request->validated()['state'];
        $tournament = $service->transitionTo($state, $tournament);

        return response()->json([
            'data' => $tournament,
            'message' => 'Tournament state updated successfully',
        ]);
    }
}
