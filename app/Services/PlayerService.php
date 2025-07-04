<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Tournament;
use Spatie\ModelStates\Exceptions\TransitionNotAllowed;

class PlayerService
{
    /**
     * Create a new player for the given tournament.
     *
     * @param  PlayerPayload  $data
     *
     * @throws TransitionNotAllowed
     */
    public function createPlayer(array $data, Tournament $tournament): Player
    {
        // chek if the tournament is in a state that allows players to be added
        if (! $tournament->canAddPlayers()) {
            throw new TransitionNotAllowed('Cannot add players to this tournament.');
        }
        if ($tournament->gender !== $data['gender']) {
            throw new TransitionNotAllowed('Player cannot be added to this tournament.');
        }
        $player = $tournament->players()->create($data);

        return $player;
    }
}
