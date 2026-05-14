<?php

namespace Database\Factories;

use App\Models\FuncaoFuncionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FuncaoFuncionario>
 */
class FuncaoFuncionarioFactory extends Factory
{
    protected $model = FuncaoFuncionario::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->jobTitle(),
            'ativo' => true,
        ];
    }
}
