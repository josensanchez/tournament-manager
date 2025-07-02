<?php

namespace App\Models;

use App\Models\States\Tournament\Registering;
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
        'gender',
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

    /**
     * The players that belong to the tournament.
     *
     * @return HasMany<MatchGame, $this>
     */
    public function matches(): HasMany
    {
        return $this->hasMany(MatchGame::class);
    }

    public function canAddPlayers(): bool
    {
        return $this->state == Registering::$name;
    }

    /**
     * Generate matches for the tournament.
     */
    public function generateMatches(): bool
    {
        if ($this->matches()->exists()) {
            return false; // Matches already generated
        }

        $players = $this->players->shuffle();

        for ($i = 0; $i < $players->count(); $i += 2) {

            $this->matches()->create([
                'first_player_id' => $players[$i]->id, // @phpstan-ignore property.nonObject
                'second_player_id' => $players[$i + 1]->id, // @phpstan-ignore property.nonObject
            ]);
        }

        $this->refresh();

        return true;
    }
}
