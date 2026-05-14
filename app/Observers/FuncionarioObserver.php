<?php

namespace App\Observers;

use App\Models\Funcionario;
use App\Services\Documentos\GeradorSlots;

class FuncionarioObserver
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function saved(Funcionario $funcionario): void
    {
        if ($funcionario->wasRecentlyCreated || $funcionario->wasChanged('funcao_funcionario_id')) {
            $this->geradorSlots->garantirSlotsFuncionario($funcionario->fresh(['funcao']));
        }
    }
}
