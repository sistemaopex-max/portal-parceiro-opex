<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePartnerCategoryRequest extends FormRequest
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
        return [
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:10000'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'descricao' => 'descrição',
        ];
    }
}
