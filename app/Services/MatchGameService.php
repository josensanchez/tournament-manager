<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\Player;
use InvalidArgumentException;

class MatchGameService
{
    /**
     * Transition the match game to a new state.
     *
     * @param  array<int, string>|null  $score
     *
     * @throws InvalidArgumentException
     */
    public function transitionTo(string $state, ?array $score, MatchGame $match): MatchGame
    {
        if (! is_null($score) && ! $this->validScore($score)) {
            throw new InvalidArgumentException('Invalid score provided. ' . implode(', ', $score));
        }
        $match->state->transitionTo($state, $score);

        // Save the game
        $match->save();

        return $match;
    }

    /**
     * Validate the score for a match.
     *
     * @param  array<int, string>|null  $score
     */
    public function validScore(?array $score): bool
    {
        if (is_null($score) || count($score) === 0) {
            return false; // No score provided
        }
        foreach ($score as $s) {

            [$left, $right] = explode('-', $s);
            if ((int) $left == 6 && (int) $right == 6) {
                return false; // Invalid score: 6-6 is not allowed
            }
            if ((int) $left == 7 && (int) $right == 7) {
                return false; // Invalid score: 7-7 is not allowed
            }
            if ((int) $left < 6 && (int) $right < 6) {
                return false; // Invalid score: one of the scores must be at least 6
            }
        }

        return true;
    }

    /**
     * Determine the winner of the match based on the score.
     *
     * @param  array<int, string>  $score
     */
    public function getWinner(array $score, MatchGame $match): Player
    {
        $first = 0;
        $second = 0;

        foreach ($score as $s) {
            [$l, $r] = explode('-', $s);
            $first += (int) $l > (int) $r ? 1 : 0;
            $second += (int) $r > (int) $l ? 1 : 0;
        }
        /** @var Player $firstPlayer */
        $firstPlayer = $match->firstPlayer;
        /** @var Player $secondPlayer */
        $secondPlayer = $match->secondPlayer;

        return $first > $second ? $firstPlayer : $secondPlayer;
    }

    public function createNextMatchGame(MatchGame $match, MatchGame $counterPart): MatchGame
    {
        // Create a new match game for the next stage
        $nextMatch = new MatchGame;
        $nextMatch->stage = $match->stage / 2;
        $nextMatch->tournament_id = $match->tournament_id;

        // Set players for the next match
        $nextMatch->firstPlayer()->associate($match->winner);
        $nextMatch->secondPlayer()->associate($counterPart->winner);

        // Save the new match game
        $nextMatch->save();

        return $nextMatch;
    }
}
