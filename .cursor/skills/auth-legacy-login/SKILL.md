# Skill: Autenticación legacy (staff y alumno)

## Contexto

El sistema usa dos guards separados sobre tablas existentes (`profesores` y `legajos`).
Las contraseñas pueden estar en texto plano (legacy) o en bcrypt (nuevas/blanqueadas).

## config/auth.php

```php
'guards' => [
    'staff' => [
        'driver'   => 'session',
        'provider' => 'profesores',
    ],
    'alumno' => [
        'driver'   => 'session',
        'provider' => 'legajos',
    ],
],

'providers' => [
    'profesores' => [
        'driver' => 'legacy',  // ← custom provider
        'model'  => App\Models\Auth\Profesor::class,
    ],
    'legajos' => [
        'driver' => 'legacy-legajos',  // ← custom provider
        'model'  => App\Models\Auth\LegajoAuth::class,
    ],
],
```

## LegacyUserProvider

```php
<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Events\PasswordShouldBeRehashed;

class LegacyUserProvider extends EloquentUserProvider
{
    /**
     * Busca usuario por campo 'dni' (no 'email').
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $model = $this->createModel();
        return $model->where('dni', $credentials['dni'])->first();
    }

    /**
     * Validación híbrida: acepta plain text legacy y bcrypt nuevo.
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain  = $credentials['password'];
        $stored = $user->getAuthPassword();

        if ($stored === null || $stored === '') {
            return false;
        }

        // Hash moderno (bcrypt, argon2)
        if (Str::startsWith($stored, ['$2y$', '$argon2i$', '$argon2id$'])) {
            return Hash::check($plain, $stored);
        }

        // Plain text legacy → acepta y dispara rehash progresivo
        if (hash_equals($stored, $plain)) {
            event(new PasswordShouldBeRehashed($user, $plain));
            return true;
        }

        return false;
    }
}
```

## Modelo Profesor (Authenticatable)

```php
<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profesor extends Authenticatable
{
    protected $table = 'profesores';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $hidden = ['pwrd'];  // ← nunca exponer

    protected $fillable = ['pwrd', 'permisos', 'ult_idTerlec', 'ult_idNivel'];

    // Campo de contraseña para Authenticatable
    public function getAuthPassword(): string
    {
        return $this->pwrd ?? '';
    }

    // Campo de username para Authenticatable
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    // Permiso por orden
    public function tienePermiso(int $orden): bool
    {
        return ($this->permisos[$orden] ?? '0') === '1';
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(ProfesorTipo::class, 'IdTipoProf', 'id');
    }
}
```

## Evento y Listener PasswordShouldBeRehashed

```php
// app/Events/PasswordShouldBeRehashed.php
class PasswordShouldBeRehashed
{
    public function __construct(
        public readonly Authenticatable $user,
        public readonly string $plainTextPassword,
    ) {}
}

// app/Listeners/RehashPasswordListener.php
class RehashPasswordListener
{
    public function handle(PasswordShouldBeRehashed $event): void
    {
        $event->user->forceFill([
            'pwrd' => Hash::make($event->plainTextPassword),
        ])->save();
    }
}
```

## StaffLoginForm (Livewire)

```php
<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StaffLoginForm extends Component
{
    #[Rule('required|integer')]
    public int $dni = 0;

    #[Rule('required|string')]
    public string $password = '';

    #[Rule('required|integer')]
    public int $nivelId = 0;

    #[Rule('required|integer')]
    public int $terlecId = 0;

    public ?string $error = null;

    public function render()
    {
        return view('livewire.auth.staff-login-form', [
            'niveles' => \App\Models\Core\Nivel::whereHas('ento')->get(),
            'ciclos'  => \App\Models\Core\Terlec::orderBy('orden')->get(),
        ])->layout('layouts.auth');
    }

    public function login(): void
    {
        $this->validate();
        $this->error = null;

        $autenticado = Auth::guard('staff')->attempt([
            'dni'      => $this->dni,
            'password' => $this->password,
        ]);

        if (! $autenticado) {
            $this->error = 'DNI o contraseña incorrectos.';
            return;
        }

        // Guardar contexto en sesión
        session([
            'nivel_id'  => $this->nivelId,
            'terlec_id' => $this->terlecId,
        ]);

        $this->redirect(route('staff.dashboard'), navigate: true);
    }
}
```

## AlumnoLoginForm (Livewire)

```php
<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AlumnoLoginForm extends Component
{
    #[Rule('required|integer')]
    public int $dni = 0;

    #[Rule('required|string')]
    public string $password = '';

    public ?string $error = null;

    public function render()
    {
        return view('livewire.auth.alumno-login-form')->layout('layouts.auth');
    }

    public function login(): void
    {
        $this->validate();
        $this->error = null;

        $autenticado = Auth::guard('alumno')->attempt([
            'dni'      => $this->dni,
            'password' => $this->password,
        ]);

        if (! $autenticado) {
            $this->error = 'DNI o contraseña incorrectos.';
            return;
        }

        $this->redirect(route('alumno.dashboard'), navigate: true);
    }
}
```

## Registrar el provider custom (AppServiceProvider)

```php
Auth::provider('legacy', function ($app, array $config) {
    return new LegacyUserProvider($app['hash'], $config['model']);
});

Auth::provider('legacy-legajos', function ($app, array $config) {
    return new LegacyUserProvider($app['hash'], $config['model']);
});
```
