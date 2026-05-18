<?php

namespace App\Http\Controllers;

use App\Models\Analytic;
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
                'workExperiences',
                'certificates',
            ]);

        $resume = (clone $resumeQuery)
            ->visibleOnPortfolio()
            ->latest('updated_at')
            ->first();

        $resume ??= $resumeQuery->latest('updated_at')->first();

        abort_if(!$resume, 404);

        Analytic::track('portfolio_view', $user, $resume);

        // Get recent published blog posts
        $recentPosts = $user->posts()
            ->where('publish', true)
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'published_at']);

        // Get active links
        $links = $user->links()
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'url', 'description']);

        $template = Str::studly($user->template?->value ?? (string)$user->template ?? 'standard');

        return Inertia::render("templates/{$user->template->value}/Portfolio/Index", [
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'bio' => $user->bio,
                'avatar_url' => $user->avatar_url,
                'slug' => $user->slug,
                'public_resume_id' => $user->public_resume_id,
            ],
            'resume' => $resume,
            'recentPosts' => $recentPosts,
            'links' => $links,
            'hasBlog' => $user->posts()->where('publish', true)->exists(),
            'hasLinks' => $user->links()->where('is_active', true)->exists(),
        ]);
    }
}
