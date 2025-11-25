<?php

namespace App\Models;

use App\Enums\Proficiency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'proficiency',
        'additional_evidence',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'proficiency' => Proficiency::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
