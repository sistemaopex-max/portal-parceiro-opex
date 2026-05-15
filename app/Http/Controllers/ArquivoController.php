<?php

namespace App\Http\Controllers;

use App\Models\DocumentoEmpresa;
use App\Models\DocumentoFuncionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArquivoController extends Controller
{
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

        // Verificação de autorização
        if ($user->isBackOffice()) {
            // Admin sempre pode visualizar
        } elseif ($doc instanceof DocumentoEmpresa) {
            $partner = $user->currentPartner();
            abort_if($partner === null || $doc->parceiro_id !== $partner->id, 403);
        } elseif ($doc instanceof DocumentoFuncionario) {
            $partner = $user->currentPartner();
            $funcionario = $doc->funcionario;
            abort_if($partner === null || $funcionario->parceiro_id !== $partner->id, 403);
        }

        abort_if($doc->arquivo_caminho === null || $doc->arquivo_disco === null, 404);

        $disk = Storage::disk($doc->arquivo_disco);

        abort_unless($disk->exists($doc->arquivo_caminho), 404);

        $mime = $doc->arquivo_mime ?: ($disk->mimeType($doc->arquivo_caminho) ?: 'application/octet-stream');

        return response()->stream(
            fn () => fpassthru($disk->readStream($doc->arquivo_caminho)),
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }
}
