<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCnpj implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/\D/', '', (string) $value);

        if (strlen($cnpj) !== 14) {
            $fail('O CNPJ deve conter 14 dígitos.');
            return;
        }

        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            $fail('O CNPJ informado é inválido.');
            return;
        }

        // Primeiro dígito verificador
        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $cnpj[12] !== $digit1) {
            $fail('O CNPJ informado é inválido.');
            return;
        }

        // Segundo dígito verificador
        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $cnpj[13] !== $digit2) {
            $fail('O CNPJ informado é inválido.');
        }
    }
}
