<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceState extends Model
{
    use HasFactory;

    protected $table = 'race_state';

    protected $fillable = [
        'manifest_holder_id',
    ];

    public function manifestHolder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manifest_holder_id');
    }
}
