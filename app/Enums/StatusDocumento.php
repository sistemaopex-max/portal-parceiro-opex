<?php

namespace App\Enums;

enum StatusDocumento: string
{
    case FaltandoDocumento = 'faltando_documento';
    case Pendente = 'pendente';
    case Valido = 'valido';
    case Invalido = 'invalido';

    public function label(): string
    {
        return match ($this) {
            self::FaltandoDocumento => 'Faltando documento',
            self::Pendente => 'Pendente',
            self::Valido => 'Válido',
            self::Invalido => 'Inválido',
        };
    }

    /**
     * Classes Tailwind para badge (fundo + texto).
     */
    public function cor(): string
    {
        return match ($this) {
            self::FaltandoDocumento => 'bg-gray-100 text-gray-800 ring-gray-200',
            self::Pendente => 'bg-amber-100 text-amber-900 ring-amber-200',
            self::Valido => 'bg-green-100 text-green-800 ring-green-200',
            self::Invalido => 'bg-red-100 text-red-800 ring-red-200',
        };
    }
}
