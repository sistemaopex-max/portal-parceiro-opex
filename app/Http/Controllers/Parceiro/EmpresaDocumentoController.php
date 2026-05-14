<?php

namespace App\Http\Controllers\Parceiro;

use App\Enums\StatusDocumento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Parceiro\UploadDocumentoEmpresaRequest;
use App\Models\DocumentoEmpresa;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmpresaDocumentoController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(): View
    {
        $partner = auth()->user()->partner;
        abort_if($partner === null, 404);

        $this->geradorSlots->garantirSlotsEmpresa($partner);

        $documentos = $partner->documentosEmpresa()
            ->with('tipo')
            ->orderBy('id')
            ->get();

        return view('parceiro.empresa.documentos.index', compact('partner', 'documentos'));
    }

    public function upload(UploadDocumentoEmpresaRequest $request, DocumentoEmpresa $documento_empresa): RedirectResponse
    {
        $partner = auth()->user()->partner;
        abort_if($partner === null || $documento_empresa->parceiro_id !== $partner->id, 404);

        $disk = 'local';
        $file = $request->file('arquivo');
        $ext = $file->getClientOriginalExtension() ?: 'bin';
        $basename = (string) Str::uuid();
        $relativeDir = "parceiros/{$partner->id}/empresa/{$documento_empresa->id}";

        if ($documento_empresa->arquivo_caminho) {
            Storage::disk($documento_empresa->arquivo_disco ?? $disk)->delete($documento_empresa->arquivo_caminho);
        }

        $storedPath = $file->storeAs($relativeDir, "{$basename}.{$ext}", $disk);

        $documento_empresa->update([
            'arquivo_disco' => $disk,
            'arquivo_caminho' => $storedPath,
            'arquivo_nome_original' => $file->getClientOriginalName(),
            'arquivo_mime' => $file->getClientMimeType(),
            'arquivo_tamanho' => $file->getSize(),
            'validade' => $request->date('validade'),
            'status' => StatusDocumento::Pendente,
            'validado_por_id' => null,
            'validado_em' => null,
            'observacoes' => null,
        ]);

        return redirect()
            ->route('parceiro.empresa.documentos.index')
            ->with('status', 'Documento enviado e aguardando validação.');
    }

    public function download(DocumentoEmpresa $documento_empresa): StreamedResponse
    {
        $this->authorize('download', $documento_empresa);

        abort_if($documento_empresa->arquivo_caminho === null || $documento_empresa->arquivo_disco === null, 404);

        return Storage::disk($documento_empresa->arquivo_disco)->download(
            $documento_empresa->arquivo_caminho,
            $documento_empresa->arquivo_nome_original ?? 'documento'
        );
    }
}
