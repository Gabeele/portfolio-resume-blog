<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class SlugController extends Controller
{
    use AuthorizesRequests;

    public function show(User $user)
    {
        $portfolio = $user->template;
        $resume->load('skills', 'education', 'projects', 'references', 'summaries', 'user', 'workExperiences');
        return Inertia::render('Preview/Portfolio/Standard', [
            'resume' => $resume
        ]);
    }
}
