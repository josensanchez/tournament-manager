<?php

namespace App\Http\Controllers;

use App\Http\Requests\TournamentTransitionRequest;
use App\Models\Tournament;
use App\Services\TournamentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\ModelStates\Exceptions\TransitionNotFound;

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
    public function transitionState(TournamentService $service, TournamentTransitionRequest $request, Tournament $tournament): JsonResponse
    {
        try {
            /** @var string $state */
            $state = $request->validated()['state'];
            $tournament = $service->transitionTo($state, $tournament);
        } catch (TransitionNotFound $tnf) {
            return response()->json([
                'error' => 'Invalid state transition', // @pest-mutate-ignore
                'message' => $tnf->getMessage(), // @pest-mutate-ignore
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while transitioning the tournament state', // @pest-mutate-ignore
                'message' => $e->getMessage(), // @pest-mutate-ignore
            ], 422);
        }

        return response()->json([
            'data' => $tournament, // @pest-mutate-ignore
            'message' => 'Tournament state updated successfully', // @pest-mutate-ignore
        ]);
    }
}
