<?php

namespace App\Observers;

use App\Events\ResumeInfoChangeEvent;
use App\Models\Resume;
use App\Models\Scopes\CurrentUserScope;

class ResumeObserver
{
    public function saved(Resume $resume): void
    {
        if ($resume->wasChanged('is_public') && $resume->is_public) {
            Resume::withoutGlobalScope(CurrentUserScope::class)
                ->where('user_id', $resume->user_id)
                ->where('id', '!=', $resume->id)
                ->where('is_public', true)
                ->update(['is_public' => false]);
        }

        ResumeInfoChangeEvent::dispatch($resume);
    }

    public function deleting(Resume $resume): void
    {
        ResumeInfoChangeEvent::dispatch($resume);
    }

}
