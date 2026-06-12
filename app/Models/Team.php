<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'category', 'invite_code'])]
class Team extends Model
{
    use HasFactory;

    /**
     * Get the members of the team.
     *
     * @return HasMany<User, Team>
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the location of the team.
     *
     * @return HasOne<TeamLocation, Team>
     */
    public function location(): HasOne
    {
        return $this->hasOne(TeamLocation::class);
    }
}
