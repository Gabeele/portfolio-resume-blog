<?php

namespace App\Policies;

use App\Models\atlas;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class atlasPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, atlas $atlas): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, atlas $atlas): bool
    {
    }

    public function delete(User $user, atlas $atlas): bool
    {
    }

    public function restore(User $user, atlas $atlas): bool
    {
    }

    public function forceDelete(User $user, atlas $atlas): bool
    {
    }
}
