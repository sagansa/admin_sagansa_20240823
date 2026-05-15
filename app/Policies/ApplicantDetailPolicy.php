<?php

namespace App\Policies;

use App\Models\ApplicantDetail;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicantDetailPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function view(User $user, ApplicantDetail $applicantDetail): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function update(User $user, ApplicantDetail $applicantDetail): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function delete(User $user, ApplicantDetail $applicantDetail): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function restore(User $user, ApplicantDetail $applicantDetail): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function forceDelete(User $user, ApplicantDetail $applicantDetail): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }
}
