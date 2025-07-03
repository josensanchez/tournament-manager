<?php

namespace App\Models\States\MatchGame;

use App\Models\MatchGame;
use App\Models\States\MatchGame\Handlers\ToFinished;
use App\Models\States\MatchGame\Handlers\ToInProgress;
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
            ->allowTransition(Pending::class, InProgress::class, ToInProgress::class)
            ->allowTransition(InProgress::class, Finished::class, ToFinished::class);
    }
}
