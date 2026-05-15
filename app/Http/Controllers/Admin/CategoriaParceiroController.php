<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePartnerCategoryRequest;
use App\Http\Requests\Admin\UpdatePartnerCategoryRequest;
use App\Models\PartnerCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoriaParceiroController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PartnerCategory::class, 'partner_category');
    }

    public function index(): View
    {
        $categories = PartnerCategory::query()
            ->withCount('partners')
            ->orderBy('nome')
            ->paginate(15);

        return view('admin.partner-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.partner-categories.create');
    }

    public function store(StorePartnerCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->resolveUniqueSlug(null, $validated['nome']);
        $validated['ativo'] = $request->boolean('ativo', true);

        $category = PartnerCategory::query()->create($validated);

        return redirect()
            ->route('admin.partner-categories.show', $category)
            ->with('status', 'Categoria criada. Configure documentos e funções abaixo.');
    }

    public function show(PartnerCategory $partner_category): View
    {
        $partner_category->load([
            'tiposDocumentoEmpresa' => fn ($q) => $q->ativos()->orderBy('nome'),
            'funcoesFuncionario' => fn ($q) => $q->ativos()->orderBy('nome'),
        ]);
        $partner_category->loadCount('partners');

        return view('admin.partner-categories.show', [
            'category' => $partner_category,
        ]);
    }

    public function edit(PartnerCategory $partner_category): View
    {
        return view('admin.partner-categories.edit', [
            'category' => $partner_category,
        ]);
    }

    public function update(UpdatePartnerCategoryRequest $request, PartnerCategory $partner_category): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->resolveUniqueSlug(null, $validated['nome'], $partner_category->id);

        $partner_category->update($validated);
        $partner_category->refresh();

        return redirect()
            ->route('admin.partner-categories.show', $partner_category)
            ->with('status', 'Categoria atualizada com sucesso.');
    }

    public function destroy(PartnerCategory $partner_category): RedirectResponse
    {
        if ($partner_category->partners()->exists()) {
            return redirect()
                ->route('admin.partner-categories.index')
                ->withErrors(['delete' => 'Não posso excluir pois há empresas com a categoria associada']);
        }

        $partner_category->delete();

        return redirect()
            ->route('admin.partner-categories.index')
            ->with('status', 'Categoria excluída.');
    }

    private function resolveUniqueSlug(?string $slug, ?string $name, ?int $ignoreId = null): string
    {
        $base = $slug !== null && $slug !== ''
            ? Str::slug($slug)
            : Str::slug($name ?? '');

        if ($base === '') {
            $base = 'categoria';
        }

        $candidate = $base;
        $suffix = 1;

        while (
            PartnerCategory::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $suffix++;
            $candidate = $base . '-' . $suffix;
        }

        return $candidate;
    }
}
