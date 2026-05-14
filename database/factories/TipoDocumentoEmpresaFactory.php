<?php

namespace Database\Factories;

use App\Models\TipoDocumentoEmpresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TipoDocumentoEmpresa>
 */
class TipoDocumentoEmpresaFactory extends Factory
{
    protected $model = TipoDocumentoEmpresa::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->words(3, true),
            'ativo' => true,
        ];
    }
}
