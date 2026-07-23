<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
            return true;
        }

        $now = Carbon::now()->format('H:i:s');

        // Handles windows that cross over midnight (e.g., 23:00 to 02:00)
        if ($this->opens_at > $this->closes_at) {
            return $now >= $this->opens_at || $now <= $this->closes_at;
        }

        return $now >= $this->opens_at && $now <= $this->closes_at;
    }
}
