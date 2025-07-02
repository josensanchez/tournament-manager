<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchGameTransitionRequest;
use App\Models\MatchGame;
use App\Models\Tournament;
use App\Services\MatchGameService;
use Illuminate\Http\JsonResponse;

class MatchGameController extends Controller
{
    public function transitionState(MatchGameService $service, MatchGameTransitionRequest $request, Tournament $tournament, MatchGame $matchGame): JsonResponse
    {
        $service->transitionTo($request->state, $request->score, $matchGame);

        return response()->json($matchGame);
    }
}
