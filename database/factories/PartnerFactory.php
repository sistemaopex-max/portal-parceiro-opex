<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'categoria_id' => PartnerCategory::factory(),
            'razao_social' => fake()->company(),
            'cnpj' => null,
            'telefone' => fake()->optional()->phoneNumber(),
            'endereco' => fake()->optional()->streetAddress(),
            'email' => fake()->unique()->safeEmail(),
            'cidade' => fake()->optional()->city(),
            'uf' => fake()->optional()->randomElement(['SP', 'RJ', 'MG', 'PR', 'RS', 'BA', 'SC']),
            'ativo' => true,
        ];
    }
}
