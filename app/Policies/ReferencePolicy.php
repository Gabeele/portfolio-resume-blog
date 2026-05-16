<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reference;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ReferencePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Reference');
    }

    public function view(AuthUser $authUser, Reference $reference): bool
    {
        return $authUser->can('View:Reference');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Reference');
    }

    public function update(AuthUser $authUser, Reference $reference): bool
    {
        return $authUser->can('Update:Reference');
    }

    public function delete(AuthUser $authUser, Reference $reference): bool
    {
        return $authUser->can('Delete:Reference');
    }

    public function restore(AuthUser $authUser, Reference $reference): bool
    {
        return $authUser->can('Restore:Reference');
    }

    public function forceDelete(AuthUser $authUser, Reference $reference): bool
    {
        return $authUser->can('ForceDelete:Reference');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Reference');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Reference');
    }

    public function replicate(AuthUser $authUser, Reference $reference): bool
    {
        return $authUser->can('Replicate:Reference');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Reference');
    }

}
