<?php

namespace App\Observers;

use App\Events\ResumeInfoChangeEvent;
use App\Models\Resume;
use App\Models\User;

class UserObserver
{
    public function saved(User $user): void
    {
        Resume::where('user_id', $user->id)
            ->each(fn(Resume $resume) => ResumeInfoChangeEvent::dispatch($resume));
    }

    public function deleting(User $user): void
    {
        Resume::where('user_id', $user->id)
            ->each(fn(Resume $resume) => ResumeInfoChangeEvent::dispatch($resume));
    }
}
