<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'category', 'pinned', 'locked', 'last_posted_at'])]
class ForumThread extends Model
{
    protected function casts(): array
    {
        return [
            'pinned' => 'boolean',
            'locked' => 'boolean',
            'last_posted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class)->oldest();
    }
}
