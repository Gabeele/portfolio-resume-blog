<?php

namespace App\Models;

use App\Models\Scopes\CurrentUserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy(CurrentUserScope::class)]
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'role',
        'description',
        'url',
        'repo',
        'image',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resumes(): BelongsToMany
    {
        return $this->belongsToMany(Resume::class, 'resume_project', 'project_id', 'resume_id');
    }
}
