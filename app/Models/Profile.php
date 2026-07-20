<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'academy_number', 'squadron', 'course',
    'phone', 'address',
    'date_of_birth', 'date_of_marriage', 'bio',
])]
class Profile extends Model
{
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'date_of_marriage' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
