<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['body'])]
class Tribute extends Model
{
    public function fallenMember(): BelongsTo
    {
        return $this->belongsTo(FallenMember::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
