<?php

namespace App\Models;

use App\Models\Scopes\CurrentUserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy(CurrentUserScope::class)]
class WorkExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business',
        'role',
        'description',
        'start',
        'end',
        'location',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'start' => 'date',
            'end' => 'date',
        ];
    }
}
