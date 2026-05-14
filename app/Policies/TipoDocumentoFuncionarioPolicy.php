<?php

namespace App\Policies;

use App\Models\TipoDocumentoFuncionario;
use App\Models\User;

class TipoDocumentoFuncionarioPolicy
{
    private function allow(User $user): bool
    {
        return $user->isBackOffice();
    }

    public function viewAny(User $user): bool
    {
        return $this->allow($user);
    }

    public function view(User $user, TipoDocumentoFuncionario $tipoDocumentoFuncionario): bool
    {
        return $this->allow($user);
    }

    public function create(User $user): bool
    {
        return $this->allow($user);
    }

    public function update(User $user, TipoDocumentoFuncionario $tipoDocumentoFuncionario): bool
    {
        return $this->allow($user);
    }

    public function delete(User $user, TipoDocumentoFuncionario $tipoDocumentoFuncionario): bool
    {
        return $this->allow($user);
    }

    public function restore(User $user, TipoDocumentoFuncionario $tipoDocumentoFuncionario): bool
    {
        return false;
    }

    public function forceDelete(User $user, TipoDocumentoFuncionario $tipoDocumentoFuncionario): bool
    {
        return false;
    }
}
