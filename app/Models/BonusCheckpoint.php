<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusCheckpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'location',
        'opens_at',
        'closes_at',
        'points',
        'status',
        'assigned_user_id',
        'notes',
    ];

    /**
     * Cast opens_at and closes_at as Carbon datetime objects.
     */
    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Checks if the checkpoint window is currently open right now.
     */
    public function isOpenNow(): bool
    {
        if (! $this->opens_at || ! $this->closes_at) {
            return false;
        }

        // Carbon handles date + time + midnight crossing automatically!
        return now()->between($this->opens_at, $this->closes_at);
    }
}
