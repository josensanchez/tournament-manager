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
 * @property int $hability
 * @property int $strength
 * @property int $speed
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
        'gender',
        'hability',
        'strength',
        'speed',
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

    public function stats(): int
    {
        if ($this->gender === 'female') {
            return $this->hability + $this->speed;
        }

        return $this->hability + $this->strength + $this->speed;
    }
}
