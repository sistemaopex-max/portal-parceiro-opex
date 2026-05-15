<?php

namespace App\Http\Requests\Admin;

use App\Models\Partner;
use App\Models\User;
use App\Rules\ValidCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdatePartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBackOffice() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $partner = $this->route('partner');
        $userId = $partner->user_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'razao_social' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'size:14', new ValidCnpj, Rule::unique('parceiros', 'cnpj')->ignore($partner->id)],
            'telefone' => ['nullable', 'string', 'max:32'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'cidade' => ['nullable', 'string', 'max:120'],
            'uf' => ['nullable', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'categoria_id' => ['required', Rule::exists('categorias_parceiro', 'id')],
            'ativo' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome do contato',
            'email' => 'e-mail',
            'password' => 'senha',
            'razao_social' => 'razão social',
            'cnpj' => 'CNPJ',
            'telefone' => 'telefone',
            'endereco' => 'endereço',
            'cidade' => 'cidade',
            'uf' => 'UF',
            'categoria_id' => 'categoria',
            'ativo' => 'parceiro ativo',
        ];
    }

    protected function prepareForValidation(): void
    {
        $uf = $this->input('uf');
        if ($uf !== null && is_string($uf)) {
            $t = strtoupper(trim($uf));
            $this->merge(['uf' => $t === '' ? null : $t]);
        }

        $partner = $this->route('partner');
        $this->merge([
            'ativo' => $this->has('ativo')
                ? $this->boolean('ativo')
                : (bool) $partner->ativo,
        ]);

        $cnpj = $this->input('cnpj');
        if ($cnpj !== null) {
            $this->merge(['cnpj' => Partner::normalizarCnpj($cnpj)]);
        }
    }
}
