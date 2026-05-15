<?php

namespace App\Http\Controllers\Parceiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parceiro\UploadDocumentoEmpresaRequest;
use App\Models\DocumentoEmpresa;
use App\Services\Documentos\GeradorSlots;
use App\Services\UploadArquivoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmpresaDocumentoController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
        private UploadArquivoService $upload,
    ) {}

    public function index(): View
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null, 404);

        $this->geradorSlots->garantirSlotsEmpresa($partner);

        $documentos = $partner->documentosEmpresa()
            ->doTipoAtivo()
            ->with('tipo')
            ->orderBy('id')
            ->get();

        return view('parceiro.empresa.documentos.index', compact('partner', 'documentos'));
    }

    public function upload(UploadDocumentoEmpresaRequest $request, DocumentoEmpresa $documento_empresa): RedirectResponse
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null || $documento_empresa->parceiro_id !== $partner->id, 404);

        $this->upload->uploadEmpresa(
            file: $request->file('arquivo'),
            documento: $documento_empresa,
            partner: $partner,
            validade: $request->date('validade'),
        );

        return redirect()
            ->route('parceiro.empresa.documentos.index')
            ->with('status', 'Documento enviado e aguardando validação.');
    }

    public function download(DocumentoEmpresa $documento_empresa): StreamedResponse
    {
        $this->authorize('download', $documento_empresa);

        abort_if($documento_empresa->arquivo_caminho === null || $documento_empresa->arquivo_disco === null, 404);

        return $this->upload->servir(
            caminho: $documento_empresa->arquivo_caminho,
            disco: $documento_empresa->arquivo_disco,
            mimeHint: $documento_empresa->arquivo_mime,
        );
    }
}
