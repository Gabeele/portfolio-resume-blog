<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Scopes\CurrentUserScope;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SlugController extends Controller
{
    use AuthorizesRequests;

    public function show(User $user)
    {
        $resumeQuery = Resume::withoutGlobalScope(CurrentUserScope::class)
            ->where('user_id', $user->id)
            ->with([
                'skills',
                'education',
                'projects',
                'references',
                'summaries',
                'user',
                'workExperiences',
                'certificates',
            ]);

        $resume = (clone $resumeQuery)
            ->visibleOnPortfolio()
            ->latest('updated_at')
            ->first();

        $resume ??= $resumeQuery->latest('updated_at')->first();

        abort_if(!$resume, 404);

        $template = Str::studly($user->template?->value ?? (string)$user->template ?? 'standard');

        return Inertia::render("Preview/Portfolio/{$template}", [
            'resume' => $resume,
        ]);
    }
}
