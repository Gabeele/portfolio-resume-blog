<?php

namespace App\Models;

use App\Enums\Proficiency;
use App\Models\Scopes\CurrentUserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([CurrentUserScope::class])]
class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'proficiency',
        'additional_evidence',
        'user_id',
    ];

    public function resumes(): BelongsToMany
    {
        return $this->belongsToMany(Resume::class, 'resume_skill', 'skill_id', 'resume_id');
    }

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
