<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimulateTournamentRequest;
use App\Services\SimulateService;
use Illuminate\Http\JsonResponse;

class TournamentSimulationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SimulateService $service, SimulateTournamentRequest $request): JsonResponse
    {
        /** @var SimulationPayload $data */
        $data = $request->validated();

        return response()->json($service->simulateTournament($data));
    }
}
