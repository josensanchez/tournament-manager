<?php

namespace App\Models;

use App\Models\States\Tournament\TournamentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\ModelStates\HasStates;

class Tournament extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\TournamentFactory> */
    use AuditableTrait, HasFactory, HasStates;

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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'state' => TournamentState::class,
    ];

    /**
     * The players that belong to the tournament.
     *
     * @return HasMany<Player, $this>
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
