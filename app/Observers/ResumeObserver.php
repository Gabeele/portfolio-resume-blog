<?php

namespace App\Observers;

use App\Events\ResumeInfoChangeEvent;
use App\Models\Resume;

class ResumeObserver
{
    public function saved(Resume $resume): void
    {
        ResumeInfoChangeEvent::dispatch($resume);
    }

    public function deleting(Resume $resume): void
    {
        ResumeInfoChangeEvent::dispatch($resume);
    }

}
