<?php

namespace App\Observers;

use App\Models\atlas;
use App\Models\User;

class UesrObserver
{
    public function created(User $user): void
    {
        Atlas::create([
            'bio' => "Hi, I am $user->first_name",
            'user_id' => $user->id
        ]);
    }
}
