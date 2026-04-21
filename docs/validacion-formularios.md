# Estándar de validación de formularios — SistemasEscolares

## Dónde validar

En componentes Livewire: atributos `#[Validate(...)]` por propiedad.
Para reglas condicionales: método `rules(): array`.

```php
use Livewire\Attributes\Validate;

#[Validate('required|digits_between:7,10')]
public ?int $dni = null;

#[Validate(['nullable', 'email:rfc'])]
public string $email = '';
```

## Cuándo validar

1. `updatedDni()` → `$this->validateOnly('dni')` — feedback en tiempo real (blur)
2. `guardar()` / `login()` → `$this->validate()` completo
3. Nunca validar en `mount()`

```php
public function updatedDni(): void
{
    $this->validateOnly('dni');
}

public function guardar(): void
{
    $this->validate();
    // ...
    $this->dispatch('toast', message: 'Guardado', type: 'success');
}
```

## Mensajes en español

Definidos en `lang/es/validation.php`. No duplicar en el componente salvo para
mensajes muy específicos:

```php
#[Validate(['required', 'digits_between:7,10'], message: [
    'required'       => 'El DNI es obligatorio.',
    'digits_between' => 'Ingrese un DNI válido (7 a 10 dígitos).',
])]
public ?int $dni = null;
```

## Reglas estándar por tipo de campo

| Campo | Regla |
|---|---|
| DNI | `required\|digits_between:7,10` |
| Email | `nullable\|email:rfc` |
| Email (DNS check) | `nullable\|email:rfc,dns` (solo en prod) |
| Teléfono | `nullable\|regex:/^[\d\s\-\+\(\)]{6,20}$/` |
| Fecha nacimiento / aprobación | `nullable\|date\|before_or_equal:today` |
| Año (terlec.ano) | `required\|integer\|min:1990\|max:2100` |
| FK (ID foráneo) | `required\|integer\|exists:tabla,id` |
| Nombre / Apellido | `required\|string\|max:50` |
| CUIT / CUIL | `nullable\|new App\Rules\CuitCuil` |
| Permisos (profesores) | `required\|regex:/^[01]+$/\|size:50` |
| Texto libre | `nullable\|string\|max:255` |

## Estilo visual de errores

Los campos con error reciben `input-error` (borde rojo, ring danger).
El mensaje de error aparece debajo del campo con clase `error-msg`:

```blade
<input type="text" wire:model.blur="dni"
       class="input @error('dni') input-error @enderror"
       @error('dni') aria-invalid="true" aria-describedby="dni-error" @enderror />

@error('dni')
    <p id="dni-error" class="error-msg" role="alert">
        <x-icons.exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
        {{ $message }}
    </p>
@enderror
```

## UX de validación

- **`wire:model.blur`** por defecto (no `live` salvo búsquedas).
- **Botón submit deshabilitado** durante carga: `wire:loading.attr="disabled"`.
- **Scroll al primer campo inválido** en submit fallido:
  ```php
  $this->dispatch('scroll-to-error');
  ```
  El listener está en el layout `staff.blade.php`.
- **Resumen de errores** si ≥ 3 campos tienen error:
  ```blade
  @if ($errors->count() >= 3)
      <x-ui.alert variant="danger" class="mb-4">
          Hay {{ $errors->count() }} campos con errores. Revise el formulario.
      </x-ui.alert>
  @endif
  ```

## Confirmación destructiva

Para bajas críticas usar el patrón de modal con confirmación:

```blade
<div x-data="{ open: false, confirm: '' }">
    <x-ui.button variant="danger" @click="open = true; confirm = ''">Eliminar</x-ui.button>

    <x-ui.modal title="Eliminar ciclo lectivo" :danger="true" x-show="open">
        <x-ui.alert variant="danger" class="mb-4">
            Esta acción eliminará permanentemente el ciclo lectivo y sus datos asociados.
        </x-ui.alert>
        <x-ui.form-field label="Escribí BORRAR para confirmar">
            <input type="text" x-model="confirm" class="input" placeholder="BORRAR" />
        </x-ui.form-field>
        <x-slot:footer>
            <x-ui.button variant="ghost" @click="open = false">Cancelar</x-ui.button>
            <x-ui.button variant="danger" wire:click="borrar" @click="open = false"
                         :disabled="confirm !== 'BORRAR'" x-bind:disabled="confirm !== 'BORRAR'">
                Confirmar eliminación
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
```
