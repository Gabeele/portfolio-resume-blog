<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class ResumeController extends Controller
{
    public function __invoke(Resume $resume)
    {
        // TODO make this into a service!
        $fullPath = $resume->getStoragePath();

        $directory = dirname($fullPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (!File::exists($fullPath)) {
            $resume->load('certificates', 'education', 'workExperiences', 'skills', 'references', 'summaries', 'projects');

            Pdf::loadView('pdf.resume.standard', ['resume' => $resume])
                ->save($fullPath);
        }

        return response()->file($fullPath, [
            'Content-Disposition' => "inline; filename=\"{$resume->user->name}_Resume.pdf\""
        ]);
    }
}
