<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel_id',
        'body',
        'image_path',
    ];

    protected $casts = [
        'channel_id' => 'integer',
    ];

    public function getChannelAttribute(): ?string
    {
        return match ($this->channel_id) {
            1 => 'war_room',
            2 => 'sos',
            3 => 'vibe_ward',
            default => null,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
