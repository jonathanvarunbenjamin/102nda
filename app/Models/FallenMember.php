<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name', 'academy_number', 'squadron', 'course',
    'date_of_passing', 'biography', 'portrait',
])]
class FallenMember extends Model
{
    protected function casts(): array
    {
        return [
            'date_of_passing' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tributes(): HasMany
    {
        return $this->hasMany(Tribute::class)->latest();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(MemorialPhoto::class)->latest();
    }

    public function portraitUrl(): ?string
    {
        return $this->portrait ? Storage::disk('public')->url($this->portrait) : null;
    }
}
