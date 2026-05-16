<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Summary;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SummaryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Summary');
    }

    public function view(AuthUser $authUser, Summary $summary): bool
    {
        return $authUser->can('View:Summary');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Summary');
    }

    public function update(AuthUser $authUser, Summary $summary): bool
    {
        return $authUser->can('Update:Summary');
    }

    public function delete(AuthUser $authUser, Summary $summary): bool
    {
        return $authUser->can('Delete:Summary');
    }

    public function restore(AuthUser $authUser, Summary $summary): bool
    {
        return $authUser->can('Restore:Summary');
    }

    public function forceDelete(AuthUser $authUser, Summary $summary): bool
    {
        return $authUser->can('ForceDelete:Summary');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Summary');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Summary');
    }

    public function replicate(AuthUser $authUser, Summary $summary): bool
    {
        return $authUser->can('Replicate:Summary');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Summary');
    }

}
