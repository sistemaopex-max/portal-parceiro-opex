<?php

namespace App\Policies;

use App\Models\DocumentoFuncionario;
use App\Models\User;

class DocumentoFuncionarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isBackOffice() || $user->isPartner();
    }

    public function view(User $user, DocumentoFuncionario $documento): bool
    {
        $parceiroId = $documento->funcionario?->parceiro_id;

        if ($parceiroId === null) {
            return false;
        }

        return $user->isBackOffice() || $this->possuiParceiro($user, $parceiroId);
    }

    public function download(User $user, DocumentoFuncionario $documento): bool
    {
        return $this->view($user, $documento);
    }

    public function upload(User $user, DocumentoFuncionario $documento): bool
    {
        $parceiroId = $documento->funcionario?->parceiro_id;

        if ($parceiroId === null) {
            return false;
        }

        return $this->possuiParceiro($user, $parceiroId);
    }

    public function validarDocumento(User $user, DocumentoFuncionario $documento): bool
    {
        return $user->isBackOffice();
    }

    private function possuiParceiro(User $user, int $parceiroId): bool
    {
        return $user->isPartner() && $user->partner?->id === $parceiroId;
    }
}
