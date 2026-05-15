<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuncaoFuncionario;
use App\Models\PartnerCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaFuncaoController extends Controller
{
    public function index(PartnerCategory $partner_category): View
    {
        $this->authorize('update', $partner_category);

        $funcoes = $partner_category->funcoesFuncionario()->orderBy('nome')->get();

        return view('admin.categorias.funcoes.index', [
            'category' => $partner_category,
            'funcoes' => $funcoes,
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
                Rule::unique('funcoes_funcionario', 'nome')->where('categoria_id', $partner_category->id),
            ],
        ]);

        $partner_category->funcoesFuncionario()->create([
            'nome' => $data['nome'],
            'ativo' => true,
        ]);

        return back()->with('status', 'Função cadastrada.');
    }

    public function edit(PartnerCategory $partner_category, FuncaoFuncionario $funcao): View
    {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);

        return view('admin.categorias.funcoes.edit', [
            'category' => $partner_category,
            'funcao' => $funcao,
        ]);
    }

    public function update(Request $request, PartnerCategory $partner_category, FuncaoFuncionario $funcao): RedirectResponse
    {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);

        $data = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('funcoes_funcionario', 'nome')
                    ->where('categoria_id', $partner_category->id)
                    ->ignore($funcao->id),
            ],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $funcao->update([
            'nome' => $data['nome'],
            'ativo' => $request->boolean('ativo'),
        ]);

        return redirect()
            ->route('admin.categorias.funcoes.index', $partner_category)
            ->with('status', 'Função atualizada.');
    }

    public function destroy(PartnerCategory $partner_category, FuncaoFuncionario $funcao): RedirectResponse
    {
        $this->authorize('update', $partner_category);
        $this->assertFuncaoBelongsToCategory($partner_category, $funcao);

        if ($funcao->funcionarios()->exists()) {
            return back()->withErrors(['delete' => 'Não é possível excluir: há funcionários utilizando esta função.']);
        }

        $funcao->tiposDocumentoFuncionario()->delete();
        $funcao->delete();

        return back()->with('status', 'Função excluída.');
    }

    private function assertFuncaoBelongsToCategory(PartnerCategory $category, FuncaoFuncionario $funcao): void
    {
        abort_if($funcao->categoria_id !== $category->id, 404);
    }
}
