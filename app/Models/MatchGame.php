<?php

namespace App\Models;

use App\Models\States\MatchGame\Finished;
use App\Models\States\MatchGame\InProgress;
use App\Models\States\MatchGame\MatchState;
use App\Models\States\MatchGame\Pending;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

/**
 * @property int $id
 * @property int $first_player_id
 * @property int $second_player_id
 * @property int $stage
 * @property string $stage_name
 * @property string $score
 * @property Pending | InProgress | Finished $state
 * @property int|null $winner_id
 *
 * @method static \Database\Factories\MatchGameFactory factory(...$parameters)
 */
class MatchGame extends Model
{
    /** @use HasFactory<\Database\Factories\MatchGameFactory> */
    use HasFactory, HasStates;

    /** @var array<int,string> */
    protected static array $stage_names = [
        2 => 'Final',
        4 => 'Semifinals',
        8 => 'Quarterfinals',
    ];

    protected $fillable = [
        'first_player_id',
        'second_player_id',
        'stage',
        'score',
        'state',
    ];

    protected $casts = [
        'state' => MatchState::class,
    ];

    protected $appends = ['stage_name'];

    /** @var array<int,string> */
    protected $hiddens = ['stage'];

    /**
     * The players that belong to the match.
     *
     * @return BelongsTo<Player, $this>
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

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

    /**
     * The tournament that the player belongs to.
     *
     * @return BelongsTo<Tournament, $this>
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Show the round simplified name
     *
     * @return Attribute<string,mixed>
     */
    protected function stageName(): Attribute
    {
        // @phpstan-ignore return.type
        return new Attribute(
            get: fn (): string => isset(self::$stage_names[$this->stage]) ? self::$stage_names[$this->stage] : "Round of {$this->stage}",
        );
    }
}
