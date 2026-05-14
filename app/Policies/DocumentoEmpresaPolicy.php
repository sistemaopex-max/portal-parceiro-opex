<?php

namespace App\Policies;

use App\Models\DocumentoEmpresa;
use App\Models\User;

class DocumentoEmpresaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isBackOffice() || $user->isPartner();
    }

    public function view(User $user, DocumentoEmpresa $documento): bool
    {
        return $user->isBackOffice() || $this->possuiParceiro($user, $documento->parceiro_id);
    }

    public function download(User $user, DocumentoEmpresa $documento): bool
    {
        return $this->view($user, $documento);
    }

    public function upload(User $user, DocumentoEmpresa $documento): bool
    {
        return $this->possuiParceiro($user, $documento->parceiro_id);
    }

    public function validarDocumento(User $user, DocumentoEmpresa $documento): bool
    {
        return $user->isBackOffice();
    }

    private function possuiParceiro(User $user, int $parceiroId): bool
    {
        return $user->isPartner() && $user->partner?->id === $parceiroId;
    }
}
