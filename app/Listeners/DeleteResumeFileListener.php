<?php

namespace App\Listeners;

use App\Events\ResumeInfoChangeEvent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DeleteResumeFileListener
{
    public function handle(ResumeInfoChangeEvent $event): void
    {
        $resume = $event->resume;

        $pdfPath = $resume->getStoragePath();
        Log::info("Deleting resume from disk: $pdfPath");

        if (File::exists($pdfPath)) {
            Log::info("Resume found on disk, deleting...");
            File::delete($pdfPath);
        } else {
            Log::info("Resume not found on disk, skipping deletion.");
        }
    }
}
