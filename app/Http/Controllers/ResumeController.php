<?php

namespace App\Http\Controllers;

use App\Models\Analytic;
use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class ResumeController extends Controller
{
    public function __invoke(Resume $resume)
    {
        // TODO make this into a service!
        $fullPath = $resume->getStoragePath();
        $template = $resume->template->value;

        $directory = dirname($fullPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (!File::exists($fullPath)) {
            $resume->load('certificates', 'education', 'workExperiences', 'skills', 'references', 'summaries', 'projects');

            Pdf::loadView("pdf.resume.$template", ['resume' => $resume])
                ->save($fullPath);
        }

        // Track resume download
        Analytic::track('resume_download', $resume->user, $resume);

        return response()->file($fullPath, [
            'Content-Disposition' => "inline; filename=\"{$resume->user->first_name}_Resume.pdf\"",
        ]);
    }
}
