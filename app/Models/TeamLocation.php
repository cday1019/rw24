<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['team_id', 'user_id', 'latitude', 'longitude', 'pinged_at'])]
class TeamLocation extends Model
{
    use HasFactory;

    /**
     * Get the team that owns the location.
     *
     * @return BelongsTo<Team, TeamLocation>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user that owns the location.
     *
     * @return BelongsTo<User, TeamLocation>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pinged_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }
}
