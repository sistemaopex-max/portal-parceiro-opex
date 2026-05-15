<?php

namespace App\Http\Requests\Parceiro;

use App\Models\Funcionario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        $funcionario = $this->route('funcionario');

        return $funcionario instanceof Funcionario
            && $this->user()?->isPartner()
            && $this->user()->currentPartner()?->id === $funcionario->parceiro_id;
    }

    protected function prepareForValidation(): void
    {
        $cpf = $this->input('cpf');
        if ($cpf !== null && is_string($cpf)) {
            $this->merge(['cpf' => Funcionario::normalizarCpf($cpf)]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Funcionario $funcionario */
        $funcionario = $this->route('funcionario');
        $parceiroId = $funcionario->parceiro_id;
        $categoriaId = $funcionario->parceiro->categoria_id;

        return [
            'nome' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'funcao_funcionario_id' => [
                'required',
                Rule::exists('funcoes_funcionario', 'id')->where(fn ($q) => $q
                    ->where('ativo', true)
                    ->where('categoria_id', $categoriaId)),
            ],
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique('funcionarios', 'cpf')
                    ->where('parceiro_id', $parceiroId)
                    ->ignore($funcionario->id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'data_nascimento' => 'data de nascimento',
            'funcao_funcionario_id' => 'função',
            'cpf' => 'CPF',
        ];
    }
}
