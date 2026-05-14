<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePartnerCategoryRequest;
use App\Http\Requests\Admin\UpdatePartnerCategoryRequest;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoEmpresa;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerCategoryController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {
        $this->authorizeResource(PartnerCategory::class, 'partner_category');
    }

    public function index(): View
    {
        $categories = PartnerCategory::query()
            ->orderBy('name')
            ->paginate(15);

        return view('admin.partner-categories.index', compact('categories'));
    }

    public function create(): View
    {
        $tiposDocumentoEmpresa = TipoDocumentoEmpresa::query()->orderBy('nome')->get();

        return view('admin.partner-categories.create', compact('tiposDocumentoEmpresa'));
    }

    public function store(StorePartnerCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tipoIds = $validated['tipo_documento_empresa_ids'] ?? [];
        unset($validated['tipo_documento_empresa_ids']);

        $validated['slug'] = $this->resolveUniqueSlug($validated['slug'] ?? null, $validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $category = PartnerCategory::query()->create($validated);
        $category->tiposDocumentoExigidos()->sync($tipoIds);

        return redirect()
            ->route('admin.partner-categories.index')
            ->with('status', 'Categoria criada com sucesso.');
    }

    public function edit(PartnerCategory $partner_category): View
    {
        $partner_category->load('tiposDocumentoExigidos');
        $tiposDocumentoEmpresa = TipoDocumentoEmpresa::query()->orderBy('nome')->get();

        return view('admin.partner-categories.edit', [
            'category' => $partner_category,
            'tiposDocumentoEmpresa' => $tiposDocumentoEmpresa,
        ]);
    }

    public function update(UpdatePartnerCategoryRequest $request, PartnerCategory $partner_category): RedirectResponse
    {
        $validated = $request->validated();
        $tipoIds = $validated['tipo_documento_empresa_ids'] ?? [];
        unset($validated['tipo_documento_empresa_ids']);

        $validated['slug'] = $this->resolveUniqueSlug($validated['slug'] ?: null, $validated['name'], $partner_category->id);
        $validated['is_active'] = $request->boolean('is_active');

        $partner_category->update($validated);
        $partner_category->tiposDocumentoExigidos()->sync($tipoIds);

        foreach ($partner_category->partners as $partner) {
            $this->geradorSlots->garantirSlotsEmpresa($partner);
        }

        return redirect()
            ->route('admin.partner-categories.index')
            ->with('status', 'Categoria atualizada com sucesso.');
    }

    public function destroy(PartnerCategory $partner_category): RedirectResponse
    {
        if ($partner_category->partners()->exists()) {
            return redirect()
                ->route('admin.partner-categories.index')
                ->withErrors(['delete' => 'Não é possível excluir: existem parceiros vinculados a esta categoria.']);
        }

        $partner_category->tiposDocumentoExigidos()->detach();
        $partner_category->delete();

        return redirect()
            ->route('admin.partner-categories.index')
            ->with('status', 'Categoria excluída.');
    }

    private function resolveUniqueSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = $slug !== null && $slug !== ''
            ? Str::slug($slug)
            : Str::slug($name);

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
            $candidate = $base.'-'.$suffix;
        }

        return $candidate;
    }
}
