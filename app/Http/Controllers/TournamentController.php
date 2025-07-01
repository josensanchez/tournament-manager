<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
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
        return response()->json([
            'data' => $tournaments,
            'message' => 'Tournaments retrieved successfully',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
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
    public function transitionState(Request $request, Tournament $tournament): JsonResponse
    {
        // Validate the request
        $request->validate([
            'state' => 'required|string|in:planned,ongoing,completed,cancelled',
        ]);

        // Transition the state
        // @phpstan-ignore method.nonObject
        $tournament->state->transitionTo($request->input('state'));

        // Save the tournament
        $tournament->save();

        return response()->json([
            'data' => $tournament,
            'message' => 'Tournament state updated successfully',
        ]);
    }
}
