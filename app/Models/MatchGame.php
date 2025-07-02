<?php

namespace App\Models;

use App\Models\States\Match\MatchState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

class MatchGame extends Model
{
    /** @use HasFactory<\Database\Factories\MatchGameFactory> */
    use HasFactory, HasStates;

    protected $fillable = [
        'first_player_id',
        'second_player_id',
        'score',
        'state',
    ];

    protected $casts = [
        'state' => MatchState::class,
    ];

    /**
     * The players that belong to the match.
     *
     * @return BelongsTo<Player, $this>
     */
    public function firstPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'first_player_id');
    }

    /**
     * The second player that belongs to the match.
     *
     * @return BelongsTo<Player, $this>
     */
    public function secondPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'second_player_id');
    }
}
