<?php

namespace App\Policies;

use App\Models\FuncaoFuncionario;
use App\Models\User;

class FuncaoFuncionarioPolicy
{
    private function allow(User $user): bool
    {
        return $user->isBackOffice();
    }

    public function viewAny(User $user): bool
    {
        return $this->allow($user);
    }

    public function view(User $user, FuncaoFuncionario $funcaoFuncionario): bool
    {
        return $this->allow($user);
    }

    public function create(User $user): bool
    {
        return $this->allow($user);
    }

    public function update(User $user, FuncaoFuncionario $funcaoFuncionario): bool
    {
        return $this->allow($user);
    }

    public function delete(User $user, FuncaoFuncionario $funcaoFuncionario): bool
    {
        return $this->allow($user);
    }

    public function restore(User $user, FuncaoFuncionario $funcaoFuncionario): bool
    {
        return false;
    }

    public function forceDelete(User $user, FuncaoFuncionario $funcaoFuncionario): bool
    {
        return false;
    }
}
