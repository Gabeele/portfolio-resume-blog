<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PreviewPortfolioController extends Controller
{
    public function __invoke(Resume $resume)
    {
        $resume->load('skills', 'education', 'projects', 'references', 'summaries', 'user', 'workExperiences');

        $template = Str::studly($resume->user?->template?->value ?? (string)$resume->user?->template ?? 'standard');

        return Inertia::render("Preview/Portfolio/{$template}", [
            'resume' => $resume
        ]);
    }
}
