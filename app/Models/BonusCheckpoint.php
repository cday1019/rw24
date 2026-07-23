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

    // app/Models/BonusCheckpoint.php

    /**
     * Scope checkpoints that are open now, opening in <2 hrs, or closed in <2 hrs.
     */
    public function scopeActiveWindow($query)
    {
        $now = now();
        $twoHoursFromNow = now()->addHours(2);
        $twoHoursAgo = now()->subHours(2);

        return $query->where(function ($q) use ($now, $twoHoursFromNow, $twoHoursAgo) {
            // 1. Currently Open
            $q->where(function ($q1) use ($now) {
                $q1->where('opens_at', '<=', $now)
                    ->where('closes_at', '>=', $now);
            })
                // 2. Opening within the next 2 hours
                ->orWhere(function ($q2) use ($now, $twoHoursFromNow) {
                    $q2->where('opens_at', '>=', $now)
                        ->where('opens_at', '<=', $twoHoursFromNow);
                })
                // 3. Closed within the last 2 hours
                ->orWhere(function ($q3) use ($now, $twoHoursAgo) {
                    $q3->where('closes_at', '<=', $now)
                        ->where('closes_at', '>=', $twoHoursAgo);
                });
        });
    }

    /**
     * Returns marker status: 'open', 'upcoming', or 'recent'
     */
    public function getWindowStatusAttribute(): string
    {
        $now = now();

        if ($this->isOpenNow()) {
            return 'open';
        }

        if ($this->opens_at && $this->opens_at->isFuture()) {
            return 'upcoming';
        }

        return 'recent';
    }
}
