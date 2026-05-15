<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ValidarDocumentoEmpresaRequest;
use App\Models\DocumentoEmpresa;
use App\Models\Partner;
use App\Services\Documentos\GeradorSlots;
use App\Services\UploadArquivoService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParceiroDocumentoEmpresaController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
        private UploadArquivoService $upload,
    ) {}

    public function index(Partner $partner): View
    {
        $this->authorize('view', $partner);

        $this->geradorSlots->garantirSlotsEmpresa($partner);

        $documentos = $partner->documentosEmpresa()
            ->doTipoAtivo()
            ->with(['tipo', 'validadoPor'])
            ->orderBy('id')
            ->get();

        return view('admin.partners.documentos-empresa.index', compact('partner', 'documentos'));
    }

    public function download(DocumentoEmpresa $documento_empresa): StreamedResponse
    {
        $this->authorize('download', $documento_empresa);

        abort_if($documento_empresa->arquivo_caminho === null || $documento_empresa->arquivo_disco === null, 404);

        abort_unless(
            Storage::disk($documento_empresa->arquivo_disco)->exists($documento_empresa->arquivo_caminho),
            404,
        );

        return $this->upload->servir(
            caminho: $documento_empresa->arquivo_caminho,
            disco: $documento_empresa->arquivo_disco,
            mimeHint: $documento_empresa->arquivo_mime,
        );
    }

    public function validar(ValidarDocumentoEmpresaRequest $request, DocumentoEmpresa $documento_empresa): RedirectResponse
    {
        $data = $request->validated();

        if ($data['decisao'] === 'valido') {
            $documento_empresa->marcarValido($request->user(), Carbon::parse($data['validade']));
        } else {
            $documento_empresa->marcarInvalido($request->user(), (string) $data['observacoes']);
        }

        return redirect()
            ->route('admin.parceiros.documentos-empresa.index', $documento_empresa->parceiro)
            ->with('status', 'Documento atualizado.');
    }
}
