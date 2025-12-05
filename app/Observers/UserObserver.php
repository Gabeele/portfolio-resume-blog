<?php

namespace App\Observers;

use App\Enums\PortfolioTemplate;
use App\Events\ResumeInfoChangeEvent;
use App\Models\Atlas;
use App\Models\Resume;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        Atlas::create([
            'bio' => "Hi, I am $user->first_name",
            'template' => PortfolioTemplate::Standard,
            'user_id' => $user->id
        ]);
    }
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
