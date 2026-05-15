<?php

namespace Database\Factories;

use App\Models\PartnerCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PartnerCategory>
 */
class PartnerCategoryFactory extends Factory
{
    protected $model = PartnerCategory::class;

    public function definition(): array
    {
        $nome = fake()->unique()->company() . ' Categoria';

        return [
            'nome' => $nome,
            'slug' => Str::slug($nome) . '-' . fake()->unique()->numerify('###'),
            'descricao' => fake()->optional()->sentence(),
            'ativo' => true,
        ];
    }
}
