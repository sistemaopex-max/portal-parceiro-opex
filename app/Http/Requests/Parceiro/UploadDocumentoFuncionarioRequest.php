<?php

namespace App\Http\Requests\Parceiro;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentoFuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        $documento = $this->route('documento_funcionario');

        return $documento !== null && $this->user()?->can('upload', $documento);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'arquivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'validade' => ['required', 'date', 'after:today'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'arquivo' => 'arquivo',
            'validade' => 'validade',
        ];
    }
}
