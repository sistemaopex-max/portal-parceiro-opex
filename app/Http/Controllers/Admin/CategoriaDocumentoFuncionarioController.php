<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuncaoFuncionario;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoFuncionario;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaDocumentoFuncionarioController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(PartnerCategory $partner_category, FuncaoFuncionario $funcao): View
    {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);

        $tipos = $funcao->tiposDocumentoFuncionario()->orderBy('nome')->get();

        return view('admin.categorias.funcoes.documentos.index', [
            'category' => $partner_category,
            'funcao' => $funcao,
            'tipos' => $tipos,
        ]);
    }

    public function edit(
        PartnerCategory $partner_category,
        FuncaoFuncionario $funcao,
        TipoDocumentoFuncionario $tipo_documento_funcionario,
    ): View {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);
        $this->assertTipoBelongsToFuncao($funcao, $tipo_documento_funcionario);

        return view('admin.categorias.funcoes.documentos.edit', [
            'category' => $partner_category,
            'funcao' => $funcao,
            'tipo' => $tipo_documento_funcionario,
        ]);
    }

    public function store(Request $request, PartnerCategory $partner_category, FuncaoFuncionario $funcao): RedirectResponse
    {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);

        $data = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipos_documento_funcionario', 'nome')->where('funcao_funcionario_id', $funcao->id),
            ],
        ]);

        $tipo = $funcao->tiposDocumentoFuncionario()->create([
            'nome' => $data['nome'],
            'ativo' => true,
        ]);

        foreach ($funcao->funcionarios as $funcionario) {
            $this->geradorSlots->garantirSlotsFuncionario($funcionario);
        }

        return back()->with('status', "Documento \"{$tipo->nome}\" cadastrado.");
    }

    public function update(
        Request $request,
        PartnerCategory $partner_category,
        FuncaoFuncionario $funcao,
        TipoDocumentoFuncionario $tipo_documento_funcionario,
    ): RedirectResponse {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);
        $this->assertTipoBelongsToFuncao($funcao, $tipo_documento_funcionario);

        $data = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipos_documento_funcionario', 'nome')
                    ->where('funcao_funcionario_id', $funcao->id)
                    ->ignore($tipo_documento_funcionario->id),
            ],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'nome' => $data['nome'],
            'ativo' => $request->boolean('ativo'),
        ];

        if ($tipo_documento_funcionario->nome !== $data['nome']) {
            $payload['slug'] = TipoDocumentoFuncionario::gerarSlugUnico($data['nome'], $tipo_documento_funcionario->id);
        }

        $tipo_documento_funcionario->update($payload);

        foreach ($funcao->funcionarios as $funcionario) {
            $this->geradorSlots->garantirSlotsFuncionario($funcionario);
        }

        return redirect()
            ->route('admin.categorias.funcoes.docs.index', [$partner_category, $funcao])
            ->with('status', 'Documento atualizado.');
    }

    public function destroy(
        PartnerCategory $partner_category,
        FuncaoFuncionario $funcao,
        TipoDocumentoFuncionario $tipo_documento_funcionario,
    ): RedirectResponse {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);
        $this->assertTipoBelongsToFuncao($funcao, $tipo_documento_funcionario);

        if ($tipo_documento_funcionario->documentos()->exists()) {
            return back()->withErrors([
                'delete' => 'Não é possível excluir: há funcionários utilizando este documento.',
            ]);
        }

        $tipo_documento_funcionario->documentos()->delete();
        $tipo_documento_funcionario->delete();

        return back()->with('status', 'Documento removido.');
    }

    private function assertFuncaoBelongsToCategory(PartnerCategory $category, FuncaoFuncionario $funcao): void
    {
        abort_if($funcao->categoria_id !== $category->id, 404);
    }

    private function assertTipoBelongsToFuncao(FuncaoFuncionario $funcao, TipoDocumentoFuncionario $tipo): void
    {
        abort_if($tipo->funcao_funcionario_id !== $funcao->id, 404);
    }
}
