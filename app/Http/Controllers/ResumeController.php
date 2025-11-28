<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    public function __invoke(Resume $resume)
    {
        $user = auth()->user();
        $resume->load('certificates', 'education', 'workExperiences', 'skills', 'references', 'summaries', 'projects');

        $pdf = Pdf::loadView('pdf.resume.standard', ['resume' => $resume]);
        return $pdf->stream("{$user->name}'s Resume.pdf");
    }
}
