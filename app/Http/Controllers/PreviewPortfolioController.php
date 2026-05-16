<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Inertia\Inertia;

class PreviewPortfolioController extends Controller
{
    public function __invoke(Resume $resume)
    {
        $resume->load('skills', 'education', 'projects', 'references', 'summaries', 'user', 'workExperiences');
        return Inertia::render('Preview/Portfolio/Standard', [
            'resume' => $resume
        ]);
    }
}
