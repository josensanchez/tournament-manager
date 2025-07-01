<?php

namespace App\Models\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use App\Models\States\Tournament\{Created, Registering, Ready, InProgress};

class TournamentState extends State
{


    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Created::class)
            ->allowTransition(Created::class, Registering::class)
            ->allowTransition(Registering::class, Ready::class)
            ->allowTransition(Ready::class, InProgress::class);
    }
}
