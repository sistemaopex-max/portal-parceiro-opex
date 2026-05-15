<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ValidarDocumentoEmpresaRequest;
use App\Models\DocumentoEmpresa;
use App\Models\Partner;
use App\Services\Documentos\GeradorSlots;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParceiroDocumentoEmpresaController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
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
            'Arquivo não encontrado no armazenamento.'
        );

        $disk = Storage::disk($documento_empresa->arquivo_disco);
        $mime = $documento_empresa->arquivo_mime ?: ($disk->mimeType($documento_empresa->arquivo_caminho) ?: 'application/octet-stream');
        $filename = basename($documento_empresa->arquivo_caminho);

        return response()->stream(
            fn () => fpassthru($disk->readStream($documento_empresa->arquivo_caminho)),
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
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
