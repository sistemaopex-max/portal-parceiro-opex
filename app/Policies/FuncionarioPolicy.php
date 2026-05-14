<?php

namespace App\Policies;

use App\Models\Funcionario;
use App\Models\User;

class FuncionarioPolicy
{
    public function viewAny(User $user): bool
    {
        return ($user->isPartner() && $user->partner !== null) || $user->isBackOffice();
    }

    public function view(User $user, Funcionario $funcionario): bool
    {
        return $user->isBackOffice() || $this->possui($user, $funcionario);
    }

    public function create(User $user): bool
    {
        return $user->isPartner() && $user->partner !== null;
    }

    public function update(User $user, Funcionario $funcionario): bool
    {
        return $this->possui($user, $funcionario);
    }

    public function delete(User $user, Funcionario $funcionario): bool
    {
        return $this->possui($user, $funcionario);
    }

    public function restore(User $user, Funcionario $funcionario): bool
    {
        return false;
    }

    public function forceDelete(User $user, Funcionario $funcionario): bool
    {
        return false;
    }

    private function possui(User $user, Funcionario $funcionario): bool
    {
        return $user->isPartner() && $user->partner?->id === $funcionario->parceiro_id;
    }
}
