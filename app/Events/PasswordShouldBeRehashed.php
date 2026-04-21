<?php

namespace App\Events;

use Illuminate\Contracts\Auth\Authenticatable;

class PasswordShouldBeRehashed
{
    public function __construct(
        public readonly Authenticatable $user,
        public readonly string $plainTextPassword,
    ) {}
}
