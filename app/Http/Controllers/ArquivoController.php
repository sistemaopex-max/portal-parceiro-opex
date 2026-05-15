<?php

namespace App\Http\Controllers;

use App\Models\DocumentoEmpresa;
use App\Models\DocumentoFuncionario;
use App\Services\UploadArquivoService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArquivoController extends Controller
{
    public function __construct(
        private UploadArquivoService $upload,
    ) {}

    public function servir(Request $request, string $filename): StreamedResponse
    {
        $user = $request->user();

        $doc = DocumentoEmpresa::query()
            ->where('arquivo_caminho', 'like', '%/' . $filename)
            ->orWhere('arquivo_caminho', $filename)
            ->first();

        if ($doc === null) {
            $doc = DocumentoFuncionario::query()
                ->where('arquivo_caminho', 'like', '%/' . $filename)
                ->orWhere('arquivo_caminho', $filename)
                ->first();
        }

        abort_if($doc === null, 404);

        if (! $user->isBackOffice()) {
            $partner = $user->currentPartner();

            if ($doc instanceof DocumentoEmpresa) {
                abort_if($partner === null || $doc->parceiro_id !== $partner->id, 403);
            } elseif ($doc instanceof DocumentoFuncionario) {
                abort_if($partner === null || $doc->funcionario->parceiro_id !== $partner->id, 403);
            }
        }

        abort_if($doc->arquivo_caminho === null || $doc->arquivo_disco === null, 404);

        return $this->upload->servir(
            caminho: $doc->arquivo_caminho,
            disco: $doc->arquivo_disco,
            mimeHint: $doc->arquivo_mime,
        );
    }
}
