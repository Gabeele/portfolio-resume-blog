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
class Resume extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'tags',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'resume_skill', 'resume_id', 'skill_id');
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class, 'resume_certificate', 'resume_id', 'certificate_id');
    }

    public function education(): BelongsToMany
    {
        return $this->belongsToMany(Education::class, 'resume_education', 'resume_id', 'education_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'resume_project', 'resume_id', 'project_id');
    }

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class, 'resume_reference', 'resume_id', 'reference_id');
    }

    public function summaries(): BelongsToMany
    {
        return $this->belongsToMany(Summary::class, 'resume_summary', 'resume_id', 'summary_id');
    }

    public function workExperiences(): BelongsToMany
    {
        return $this->belongsToMany(WorkExperience::class, 'resume_work_experience', 'resume_id', 'work_experience_id');
    }

    protected function casts(): array
    {
        return [
            'tags' => 'array'
        ];
    }
}
