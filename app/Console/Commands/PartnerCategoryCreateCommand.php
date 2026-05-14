<?php

namespace App\Console\Commands;

use App\Models\PartnerCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'partner:category')]
class PartnerCategoryCreateCommand extends Command
{
    protected $signature = 'partner:category
                            {name : Nome exibido da categoria}
                            {--slug= : Slug único (padrão: derivado do nome)}
                            {--description= : Descrição opcional}
                            {--inactive : Cria como inativa}';

    protected $description = 'Cadastra uma categoria de parceiro (slug único).';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $slug = $this->option('slug') ?: Str::slug($name);

        if ($slug === '') {
            $this->error('Slug vazio: informe --slug= com um valor válido.');

            return self::FAILURE;
        }

        if (PartnerCategory::query()->where('slug', $slug)->exists()) {
            $this->error("Já existe categoria com slug [{$slug}].");

            return self::FAILURE;
        }

        PartnerCategory::query()->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $this->option('description') ?: null,
            'is_active' => ! $this->option('inactive'),
        ]);

        $this->info("Categoria criada: {$name} ({$slug}).");

        return self::SUCCESS;
    }
}
