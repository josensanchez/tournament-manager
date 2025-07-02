<?php

namespace App\Models;

use App\Models\States\TournamentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Tournament extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentFactory> */
    use HasFactory, HasStates;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'location',
        'state',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'state' => TournamentState::class,
    ];
}
