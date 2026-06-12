<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checkpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ghost_waiter_id',
    ];

    public function ghostWaiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ghost_waiter_id');
    }

    public function laps(): HasMany
    {
        return $this->hasMany(Lap::class);
    }
}
