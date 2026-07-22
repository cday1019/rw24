<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'latitude',
        'longitude',
        'speed',
        'battery',
        'pinged_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
