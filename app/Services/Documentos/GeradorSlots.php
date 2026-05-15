<?php

namespace App\Services\Documentos;

use App\Enums\StatusDocumento;
use App\Models\DocumentoEmpresa;
use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use App\Models\Partner;

class GeradorSlots
{
    public function garantirSlotsEmpresa(Partner $partner): void
    {
        $partner->loadMissing('category');

        if (! $partner->category) {
            return;
        }

        $tipos = $partner->category
            ->tiposDocumentoEmpresa()
            ->where('ativo', true)
            ->pluck('id');

        foreach ($tipos as $tipoId) {
            DocumentoEmpresa::query()->firstOrCreate(
                [
                    'parceiro_id' => $partner->id,
                    'tipo_documento_empresa_id' => $tipoId,
                ],
                [
                    'status' => StatusDocumento::FaltandoDocumento,
                ],
            );
        }
    }

    public function garantirSlotsFuncionario(Funcionario $funcionario): void
    {
        $funcionario->loadMissing('funcao');

        if (! $funcionario->funcao) {
            return;
        }

        $tipos = $funcionario->funcao
            ->tiposDocumentoFuncionario()
            ->where('ativo', true)
            ->pluck('id');

        foreach ($tipos as $tipoId) {
            DocumentoFuncionario::query()->firstOrCreate(
                [
                    'funcionario_id' => $funcionario->id,
                    'tipo_documento_funcionario_id' => $tipoId,
                ],
                [
                    'status' => StatusDocumento::FaltandoDocumento,
                ],
            );
        }

        $funcionario->refreshDocumentacaoEmDia();
    }
}
