<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valida CUIT/CUIL argentino (XX-XXXXXXXX-X).
 * Acepta formato con o sin guiones.
 */
class CuitCuil implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cuit = preg_replace('/[^0-9]/', '', (string) $value);

        if (strlen($cuit) !== 11) {
            $fail('El :attribute debe tener 11 dígitos.');
            return;
        }

        $prefixosValidos = ['20', '23', '24', '25', '26', '27', '30', '33', '34'];
        $prefix = substr($cuit, 0, 2);

        if (!in_array($prefix, $prefixosValidos, true)) {
            $fail('El :attribute no tiene un prefijo válido.');
            return;
        }

        // Verificación dígito verificador
        $multiplicadores = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $suma = 0;
        for ($i = 0; $i < 10; $i++) {
            $suma += (int) $cuit[$i] * $multiplicadores[$i];
        }
        $resto = $suma % 11;

        $digitoVerificador = match($resto) {
            0 => 0,
            1 => 9,
            default => 11 - $resto,
        };

        if ((int) $cuit[10] !== $digitoVerificador) {
            $fail('El :attribute no es válido (dígito verificador incorrecto).');
        }
    }
}
