<?php

namespace App\Auth;

use App\Events\PasswordShouldBeRehashed;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LegacyUserProvider extends EloquentUserProvider
{
    /**
     * Busca usuario por DNI (no por email como haría el provider estándar).
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials) || ! isset($credentials['dni'])) {
            return null;
        }

        $model = $this->createModel();

        return $model->newQuery()
            ->where('dni', $credentials['dni'])
            ->first();
    }

    /**
     * Valida la contraseña con soporte híbrido:
     * - Si el hash está en bcrypt/argon → usa Hash::check()
     * - Si está en texto plano (legacy) → compara directamente y dispara rehash
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain  = $credentials['password'];
        $stored = $user->getAuthPassword();

        if ($stored === null || $stored === '') {
            return false;
        }

        // Contraseña ya hasheada (alta nueva o blanqueo previo)
        if (Str::startsWith($stored, ['$2y$', '$2b$', '$argon2i$', '$argon2id$'])) {
            return Hash::check($plain, $stored);
        }

        // Contraseña en texto plano legacy → aceptar y disparar migración progresiva
        if (hash_equals($stored, $plain)) {
            event(new PasswordShouldBeRehashed($user, $plain));
            return true;
        }

        return false;
    }
}
