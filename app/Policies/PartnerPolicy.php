<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

class PartnerPolicy
{
    private function allow(User $user): bool
    {
        return $user->isBackOffice();
    }

    public function viewAny(User $user): bool
    {
        return $this->allow($user);
    }

    public function view(User $user, Partner $partner): bool
    {
        return $this->allow($user);
    }

    public function create(User $user): bool
    {
        return $this->allow($user);
    }

    public function update(User $user, Partner $partner): bool
    {
        return $this->allow($user);
    }

    public function delete(User $user, Partner $partner): bool
    {
        return $this->allow($user);
    }

    public function restore(User $user, Partner $partner): bool
    {
        return false;
    }

    public function forceDelete(User $user, Partner $partner): bool
    {
        return false;
    }
}
