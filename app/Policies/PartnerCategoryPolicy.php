<?php

namespace App\Policies;

use App\Models\PartnerCategory;
use App\Models\User;

class PartnerCategoryPolicy
{
    private function allow(User $user): bool
    {
        return $user->isBackOffice();
    }

    public function viewAny(User $user): bool
    {
        return $this->allow($user);
    }

    public function view(User $user, PartnerCategory $partnerCategory): bool
    {
        return $this->allow($user);
    }

    public function create(User $user): bool
    {
        return $this->allow($user);
    }

    public function update(User $user, PartnerCategory $partnerCategory): bool
    {
        return $this->allow($user);
    }

    public function delete(User $user, PartnerCategory $partnerCategory): bool
    {
        return $this->allow($user);
    }

    public function restore(User $user, PartnerCategory $partnerCategory): bool
    {
        return false;
    }

    public function forceDelete(User $user, PartnerCategory $partnerCategory): bool
    {
        return false;
    }
}
