<?php

namespace App\Models;

use App\Models\States\Player\PlayerState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\ModelStates\HasStates;

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
