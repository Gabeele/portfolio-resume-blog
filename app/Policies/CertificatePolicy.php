<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertificatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Certificate $certificate): bool
    {
        return $user->hasCertificate($certificate);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Certificate $certificate): bool
    {
        return $user->hasCertificate($certificate);

    }

    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->hasCertificate($certificate);

    }

    public function deleteAny(User $user): bool
    {
        return true;
    }

    public function restore(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }
}
