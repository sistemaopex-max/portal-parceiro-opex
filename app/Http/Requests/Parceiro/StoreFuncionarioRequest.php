<?php

namespace App\Http\Requests\Parceiro;

use App\Models\Funcionario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPartner() === true && $this->user()->partner !== null;
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
        $parceiroId = $this->user()->partner->id;

        return [
            'nome' => ['required', 'string', 'max:255'],
            'funcao_funcionario_id' => ['required', Rule::exists('funcoes_funcionario', 'id')->where(fn ($q) => $q->where('ativo', true))],
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique('funcionarios', 'cpf')->where('parceiro_id', $parceiroId),
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
            'funcao_funcionario_id' => 'função',
            'cpf' => 'CPF',
        ];
    }
}
