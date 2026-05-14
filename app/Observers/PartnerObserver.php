<?php

namespace App\Observers;

use App\Models\Partner;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Support\Str;

class PartnerObserver
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function saving(Partner $partner): void
    {
        if ($partner->razao_social === null || $partner->razao_social === '') {
            return;
        }

        if (! $partner->exists || ! $partner->slug || $partner->isDirty(['razao_social', 'cnpj'])) {
            $partner->slug = $this->uniqueSlug($partner);
        }
    }

    public function saved(Partner $partner): void
    {
        if ($partner->wasRecentlyCreated || $partner->wasChanged('categoria_id')) {
            $this->geradorSlots->garantirSlotsEmpresa($partner->fresh(['category']));
        }
    }

    private function uniqueSlug(Partner $partner): string
    {
        $base = Str::slug($partner->razao_social);
        if ($base === '') {
            $base = 'parceiro';
        }

        $digits = preg_replace('/\D/', '', (string) ($partner->cnpj ?? ''));
        $candidate = $digits !== '' ? "{$base}-{$digits}" : $base;

        $suffix = 0;
        do {
            $slug = $suffix === 0 ? $candidate : "{$candidate}-{$suffix}";
            $exists = Partner::query()
                ->where('slug', $slug)
                ->when($partner->exists, fn ($q) => $q->where('id', '!=', $partner->id))
                ->exists();
            $suffix++;
        } while ($exists);

        return $slug;
    }
}
