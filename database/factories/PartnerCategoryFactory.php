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
        $name = fake()->unique()->company().' Categoria';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
