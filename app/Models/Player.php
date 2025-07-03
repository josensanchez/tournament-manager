<?php

namespace App\Models;

use App\Models\States\Player\Eliminated;
use App\Models\States\Player\PlayerState;
use App\Models\States\Player\Playing;
use App\Models\States\Player\Registered;
use App\Models\States\Player\Winner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\ModelStates\HasStates;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int|null $team_id
 * @property int|null $rank
 * @property Registered | Playing | Eliminated | Winner $state
 *
 * @method static \Database\Factories\PlayerFactory factory(...$parameters)
 */
class Player extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use AuditableTrait, HasFactory, HasStates;

    protected $fillable = [
        'name',
        'email',
        'team_id',
        'rank',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'state' => PlayerState::class,
    ];

    /**
     * The tournament that the player belongs to.
     *
     * @return BelongsTo<Tournament, $this>
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
}
