<?php

namespace App\Observers;

use App\Enums\PortfolioTemplate;
use App\Events\ResumeInfoChangeEvent;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user): void
    {
        $this->setDefaultPortfolioDataForUser($user);
        $this->createDefaultSlugForUser($user);

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

    //    Todo maybe put this i a service
    private function setDefaultPortfolioDataForUser(User $user): void
    {
        $user->update([
            'bio' => "Hi, I am $user->first_name",
            'template' => PortfolioTemplate::Standard,
        ]);
    }

    //    todo maybe put this into a service
    private function createDefaultSlugForUser(User $user): void
    {
        $base = Str::slug(
            trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                ?: ($user->name ?? 'user')
        );

        $slug = $base;
        $count = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        $user->update([
            'slug' => $slug,
        ]);
    }
}
