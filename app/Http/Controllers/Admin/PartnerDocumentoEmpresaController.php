<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ValidarDocumentoEmpresaRequest;
use App\Models\DocumentoEmpresa;
use App\Models\Partner;
use App\Services\Documentos\GeradorSlots;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PartnerDocumentoEmpresaController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(Partner $partner): View
    {
        $this->authorize('view', $partner);

        $this->geradorSlots->garantirSlotsEmpresa($partner);

        $documentos = $partner->documentosEmpresa()
            ->with(['tipo', 'validadoPor'])
            ->orderBy('id')
            ->get();

        return view('admin.partners.documentos-empresa.index', compact('partner', 'documentos'));
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
