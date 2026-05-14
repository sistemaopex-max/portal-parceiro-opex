<?php

namespace App\Http\Requests\Admin;

use App\Models\DocumentoEmpresa;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidarDocumentoEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $documento = $this->route('documento_empresa');

        return $documento !== null && $this->user()?->can('validarDocumento', $documento);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            /** @var DocumentoEmpresa|null $doc */
            $doc = $this->route('documento_empresa');
            if ($this->input('decisao') === 'valido' && $doc instanceof DocumentoEmpresa && ! $doc->arquivo_caminho) {
                $v->errors()->add('decisao', 'Não é possível marcar como válido sem arquivo enviado.');
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'decisao' => ['required', Rule::in(['valido', 'invalido'])],
            'validade' => ['required_if:decisao,valido', 'nullable', 'date'],
            'observacoes' => ['required_if:decisao,invalido', 'nullable', 'string', 'max:10000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'decisao' => 'decisão',
            'validade' => 'validade',
            'observacoes' => 'observações',
        ];
    }
}
