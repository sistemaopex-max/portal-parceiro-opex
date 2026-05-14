<?php

namespace Database\Factories;

use App\Models\TipoDocumentoFuncionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TipoDocumentoFuncionario>
 */
class TipoDocumentoFuncionarioFactory extends Factory
{
    protected $model = TipoDocumentoFuncionario::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->words(3, true),
            'ativo' => true,
        ];
    }
}
