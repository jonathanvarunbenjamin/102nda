<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['relation', 'name', 'date_of_birth', 'notes'])]
class FamilyMember extends Model
{
    public const RELATION_SPOUSE = 'spouse';
    public const RELATION_CHILD = 'child';

    /** Maximum number of children a member may add. */
    public const MAX_CHILDREN = 3;

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
