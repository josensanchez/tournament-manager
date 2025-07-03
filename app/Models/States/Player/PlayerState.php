<?php

namespace App\Models\States\Player;

use App\Models\Player;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<Player>
 */
abstract class PlayerState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Registered::class)
            ->allowTransition(Registered::class, Playing::class)
            ->allowTransition(Playing::class, Playing::class)
            ->allowTransition(Playing::class, Eliminated::class)
            ->allowTransition(Playing::class, Winner::class);
    }
}
