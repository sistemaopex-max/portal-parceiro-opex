<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePartnerCategoryRequest;
use App\Http\Requests\Admin\UpdatePartnerCategoryRequest;
use App\Models\PartnerCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerCategoryController extends Controller
{
    public function __construct()
    {
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
        return view('admin.partner-categories.create');
    }

    public function store(StorePartnerCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveUniqueSlug($data['slug'] ?? null, $data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        PartnerCategory::query()->create($data);

        return redirect()
            ->route('admin.partner-categories.index')
            ->with('status', 'Categoria criada com sucesso.');
    }

    public function edit(PartnerCategory $partner_category): View
    {
        return view('admin.partner-categories.edit', ['category' => $partner_category]);
    }

    public function update(UpdatePartnerCategoryRequest $request, PartnerCategory $partner_category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveUniqueSlug($data['slug'] ?: null, $data['name'], $partner_category->id);
        $data['is_active'] = $request->boolean('is_active');

        $partner_category->update($data);

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
