<?php

namespace App\Policies;

use App\Models\Atlas;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtlasPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Atlas $Atlas): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Atlas $Atlas): bool
    {
    }

    public function delete(User $user, Atlas $Atlas): bool
    {
    }

    public function restore(User $user, Atlas $Atlas): bool
    {
    }

    public function forceDelete(User $user, Atlas $Atlas): bool
    {
    }
}
