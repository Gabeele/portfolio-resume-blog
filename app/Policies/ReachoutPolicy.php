<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reachout;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ReachoutPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Reachout');
    }

    public function view(AuthUser $authUser, Reachout $reachout): bool
    {
        return $authUser->can('View:Reachout');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Reachout');
    }

    public function update(AuthUser $authUser, Reachout $reachout): bool
    {
        return $authUser->can('Update:Reachout');
    }

    public function delete(AuthUser $authUser, Reachout $reachout): bool
    {
        return $authUser->can('Delete:Reachout');
    }

    public function restore(AuthUser $authUser, Reachout $reachout): bool
    {
        return $authUser->can('Restore:Reachout');
    }

    public function forceDelete(AuthUser $authUser, Reachout $reachout): bool
    {
        return $authUser->can('ForceDelete:Reachout');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Reachout');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Reachout');
    }

    public function replicate(AuthUser $authUser, Reachout $reachout): bool
    {
        return $authUser->can('Replicate:Reachout');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Reachout');
    }

}
