<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoEmpresa;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaDocumentoEmpresaController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(PartnerCategory $partner_category): View
    {
        $this->authorize('update', $partner_category);

        $tipos = $partner_category->tiposDocumentoEmpresa()->orderBy('nome')->get();

        return view('admin.partner-categories.documentos-empresa.index', [
            'category' => $partner_category,
            'tipos' => $tipos,
        ]);
    }

    public function store(Request $request, PartnerCategory $partner_category): RedirectResponse
    {
        $this->authorize('update', $partner_category);

        $data = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipos_documento_empresa', 'nome')->where('categoria_id', $partner_category->id),
            ],
        ]);

        $tipo = $partner_category->tiposDocumentoEmpresa()->create([
            'nome' => $data['nome'],
            'ativo' => true,
        ]);

        foreach ($partner_category->partners as $partner) {
            $this->geradorSlots->garantirSlotsEmpresa($partner);
        }

        return back()->with('status', "Documento \"{$tipo->nome}\" cadastrado.");
    }

    public function update(Request $request, PartnerCategory $partner_category, TipoDocumentoEmpresa $tipo_documento_empresa): RedirectResponse
    {
        $this->authorize('update', $partner_category);
        $this->assertTipoBelongsToCategory($partner_category, $tipo_documento_empresa);

        $data = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipos_documento_empresa', 'nome')
                    ->where('categoria_id', $partner_category->id)
                    ->ignore($tipo_documento_empresa->id),
            ],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $tipo_documento_empresa->update([
            'nome' => $data['nome'],
            'ativo' => $request->boolean('ativo'),
        ]);

        foreach ($partner_category->partners as $partner) {
            $this->geradorSlots->garantirSlotsEmpresa($partner);
        }

        return back()->with('status', 'Documento atualizado.');
    }

    public function destroy(PartnerCategory $partner_category, TipoDocumentoEmpresa $tipo_documento_empresa): RedirectResponse
    {
        $this->authorize('update', $partner_category);
        $this->assertTipoBelongsToCategory($partner_category, $tipo_documento_empresa);

        if ($tipo_documento_empresa->documentos()->exists()) {
            return back()->withErrors([
                'delete' => 'Não é possível excluir: há empresas utilizando este documento.',
            ]);
        }

        $tipo_documento_empresa->documentos()->delete();
        $tipo_documento_empresa->delete();

        return back()->with('status', 'Documento removido.');
    }

    private function assertTipoBelongsToCategory(PartnerCategory $category, TipoDocumentoEmpresa $tipo): void
    {
        abort_if($tipo->categoria_id !== $category->id, 404);
    }
}
