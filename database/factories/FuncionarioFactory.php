<?php

namespace Database\Factories;

use App\Models\Funcionario;
use App\Models\FuncaoFuncionario;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Funcionario>
 */
class FuncionarioFactory extends Factory
{
    protected $model = Funcionario::class;

    public function definition(): array
    {
        return [
            'parceiro_id' => Partner::factory(),
            'funcao_funcionario_id' => FuncaoFuncionario::factory(),
            'nome' => fake()->name(),
            'cpf' => fake()->unique()->numerify('###########'),
            'documentacao_em_dia' => false,
        ];
    }
}
