<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('partner_categories', 'slug')],
            'description' => ['nullable', 'string', 'max:10000'],
            'tipo_documento_empresa_ids' => ['nullable', 'array'],
            'tipo_documento_empresa_ids.*' => ['integer', 'exists:tipos_documento_empresa,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'slug' => 'slug',
            'description' => 'descrição',
            'tipo_documento_empresa_ids' => 'tipos de documento da empresa',
        ];
    }
}
