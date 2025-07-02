<?php

namespace App\Models\States;

use App\Models\States\Tournament\Created;
use App\Models\States\Tournament\InProgress;
use App\Models\States\Tournament\Ready;
use App\Models\States\Tournament\Registering;
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
            ->registerState(Created::class)
            ->default(Created::class)
            ->allowTransition(Created::class, Registering::class)
            ->allowTransition(Registering::class, Ready::class)
            ->allowTransition(Ready::class, InProgress::class);
    }
}
