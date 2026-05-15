<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePartnerCategoryRequest;
use App\Http\Requests\Admin\UpdatePartnerCategoryRequest;
use App\Models\PartnerCategory;
use App\Services\CategoriaParceiroService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoriaParceiroController extends Controller
{
    public function __construct(
        private CategoriaParceiroService $service,
    ) {
        $this->authorizeResource(PartnerCategory::class, 'partner_category');
    }

    public function index(): View
    {
        $categories = PartnerCategory::query()
            ->withCount('partners')
            ->orderBy('nome')
            ->paginate(15);

        return view('admin.categorias.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categorias.create');
    }

    public function store(StorePartnerCategoryRequest $request): RedirectResponse
    {
        $category = $this->service->criar($request->validated());

        return redirect()
            ->route('admin.categorias.show', $category)
            ->with('status', 'Categoria criada. Configure documentos e funções abaixo.');
    }

    public function show(PartnerCategory $partner_category): View
    {
        $partner_category->load([
            'tiposDocumentoEmpresa' => fn ($q) => $q->ativos()->orderBy('nome'),
            'funcoesFuncionario' => fn ($q) => $q->ativos()->orderBy('nome'),
        ]);
        $partner_category->loadCount('partners');

        return view('admin.categorias.show', [
            'category' => $partner_category,
        ]);
    }

    public function edit(PartnerCategory $partner_category): View
    {
        return view('admin.categorias.edit', [
            'category' => $partner_category,
        ]);
    }

    public function update(UpdatePartnerCategoryRequest $request, PartnerCategory $partner_category): RedirectResponse
    {
        $this->service->atualizar($partner_category, $request->validated());

        return redirect()
            ->route('admin.categorias.show', $partner_category)
            ->with('status', 'Categoria atualizada com sucesso.');
    }

    public function destroy(PartnerCategory $partner_category): RedirectResponse
    {
        if ($partner_category->partners()->exists()) {
            return redirect()
                ->route('admin.categorias.index')
                ->withErrors(['delete' => 'Não posso excluir pois há empresas com a categoria associada']);
        }

        $partner_category->delete();

        return redirect()
            ->route('admin.categorias.index')
            ->with('status', 'Categoria excluída.');
    }
}
