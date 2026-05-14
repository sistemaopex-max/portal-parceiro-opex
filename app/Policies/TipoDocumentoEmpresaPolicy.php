<?php

namespace App\Policies;

use App\Models\TipoDocumentoEmpresa;
use App\Models\User;

class TipoDocumentoEmpresaPolicy
{
    private function allow(User $user): bool
    {
        return $user->isBackOffice();
    }

    public function viewAny(User $user): bool
    {
        return $this->allow($user);
    }

    public function view(User $user, TipoDocumentoEmpresa $tipoDocumentoEmpresa): bool
    {
        return $this->allow($user);
    }

    public function create(User $user): bool
    {
        return $this->allow($user);
    }

    public function update(User $user, TipoDocumentoEmpresa $tipoDocumentoEmpresa): bool
    {
        return $this->allow($user);
    }

    public function delete(User $user, TipoDocumentoEmpresa $tipoDocumentoEmpresa): bool
    {
        return $this->allow($user);
    }

    public function restore(User $user, TipoDocumentoEmpresa $tipoDocumentoEmpresa): bool
    {
        return false;
    }

    public function forceDelete(User $user, TipoDocumentoEmpresa $tipoDocumentoEmpresa): bool
    {
        return false;
    }
}
