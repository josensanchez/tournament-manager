<?php

namespace App\Models\States\Match;

use App\Models\MatchGame;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<MatchGame>
 */
abstract class MatchState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, InProgress::class)
            ->allowTransition(InProgress::class, Finished::class);
    }
}
