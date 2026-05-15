<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePartnerRequest;
use App\Models\Partner;
use App\Models\PartnerCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ParceiroController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Partner::class, 'partner');
    }

    public function index(Request $request): View
    {
        $query = Partner::query()->with('category');

        $busca = trim((string) $request->input('busca', ''));
        if ($busca !== '') {
            $like = '%' . addcslashes($busca, '%_\\') . '%';
            $query->where(function ($q) use ($like) {
                $q->where('razao_social', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('telefone', 'like', $like)
                    ->orWhere('endereco', 'like', $like)
                    ->orWhere('cidade', 'like', $like)
                    ->orWhere('cnpj', 'like', $like);
            });
        }

        $categoriaId = $request->input('categoria');
        if ($categoriaId !== null && $categoriaId !== '') {
            $id = (int) $categoriaId;
            if ($id > 0 && PartnerCategory::query()->whereKey($id)->exists()) {
                $query->where('categoria_id', $id);
            }
        }

        $partners = $query
            ->with([
                'category',
                'documentosEmpresa.tipo',
                'funcionarios',
            ])
            ->orderBy('razao_social')
            ->paginate(15)
            ->withQueryString();

        $filterCategories = PartnerCategory::query()->orderBy('nome')->get();

        return view('admin.partners.index', compact('partners', 'filterCategories'));
    }

    public function show(Partner $partner): View
    {
        $partner->load([
            'documentosEmpresa.tipo',
            'funcionarios.funcao',
            'funcionarios.documentos.tipo',
        ]);

        return view('admin.partners.show', compact('partner'));
    }

    public function edit(Partner $partner): View
    {
        $categories = PartnerCategory::query()
            ->where(function ($query) use ($partner) {
                $query->where('ativo', true)
                    ->orWhere('id', $partner->categoria_id);
            })
            ->orderBy('nome')
            ->get();

        $partner->load('user');

        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    public function update(UpdatePartnerRequest $request, Partner $partner): RedirectResponse
    {
        $data = $request->validated();

        $partner->user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $partner->user->password = Hash::make($data['password']);
        }

        $partner->user->save();

        $partner->update([
            'categoria_id' => $data['categoria_id'],
            'razao_social' => $data['razao_social'],
            'cnpj' => $data['cnpj'] ?? null,
            'telefone' => $data['telefone'] ?? null,
            'endereco' => $data['endereco'] ?? null,
            'email' => $data['email'],
            'cidade' => $data['cidade'] ?? null,
            'uf' => $data['uf'] ?? null,
            'ativo' => (bool) ($data['ativo'] ?? $partner->ativo),
        ]);

        return redirect()
            ->route('admin.parceiros.index')
            ->with('status', 'Parceiro atualizado com sucesso.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $partner->user?->delete();

        return redirect()
            ->route('admin.parceiros.index')
            ->with('status', 'Parceiro removido.');
    }
}
