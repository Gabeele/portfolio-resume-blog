<?php

namespace App\Observers;

use App\Models\Resume;
use Illuminate\Support\Facades\File;
use Log;

class ResumeObserver
{
    public function saved(Resume $resume): void
    {
        $this->deleteResumeFromDisk($resume);
    }

    private function deleteResumeFromDisk(Resume $resume): void
    {
        $pdfPath = $resume->getStoragePath();
        Log::info("Deleting resume from disk: $pdfPath");

        if (File::exists($pdfPath)) {
            Log::info("Resume found on disk, deleting...");
            File::delete($pdfPath);
        } else {
            Log::info("Resume not found on disk, skipping deletion.");
        }
    }

    public function deleting(Resume $resume): void
    {
        $this->deleteResumeFromDisk($resume);
    }

}
