<?php

namespace App\Observers;

use App\Models\DocumentoFuncionario;

class DocumentoFuncionarioObserver
{
    public function saved(DocumentoFuncionario $documentoFuncionario): void
    {
        $documentoFuncionario->funcionario?->refreshDocumentacaoEmDia();
    }
}
