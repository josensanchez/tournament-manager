<?php

namespace App\Models\States\Tournament;

use App\Models\States\Tournament\Handlers\ToInProgress;
use App\Models\States\Tournament\Handlers\ToReady;
use App\Models\Tournament;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<Tournament>
 */
abstract class TournamentState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Created::class)
            ->allowTransition(Created::class, Registering::class)
            ->allowTransition(Registering::class, Ready::class, ToReady::class)
            ->allowTransition(Ready::class, InProgress::class, ToInProgress::class);
    }
}
