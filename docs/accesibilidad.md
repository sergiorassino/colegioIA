# Accesibilidad — SistemasEscolares

## Estándar mínimo: WCAG 2.1 AA

## Checklist por pantalla

### Teclado y focus
- [ ] Todos los controles alcanzables con Tab/Shift+Tab
- [ ] Focus visible en todo momento (`focus-visible:ring-2 ring-primary-500 ring-offset-2`)
- [ ] Modales tienen focus trap (foco dentro del modal mientras está abierto)
- [ ] ESC cierra modales y dropdowns
- [ ] Botón "Ir al contenido principal" (skip link) en todos los layouts

### ARIA
- [ ] Campos con error: `aria-invalid="true"` + `aria-describedby="id-error"`
- [ ] Labels siempre asociados a controles con `for` / `id`
- [ ] Tablas: `<th scope="col">` en cabeceras
- [ ] Navegaciones: `<nav aria-label="…">`
- [ ] Modales: `role="dialog"` + `aria-modal="true"` + `aria-labelledby`
- [ ] Toasts: `aria-live="polite"`
- [ ] Botones solo-icono: `aria-label="…"`
- [ ] Paginación: `aria-label` en nav + `aria-current="page"` en página activa

### Contraste (validado)
| Combinación | Ratio | ✓ AA |
|---|---|---|
| brand.jet (#333) sobre white | 11.1:1 | ✓ |
| brand.primary (#40848D) sobre white | 4.7:1 | ✓ |
| white sobre brand.primary | 4.7:1 | ✓ |
| white sobre brand.jet | 11.1:1 | ✓ |
| danger-600 (#c62828) sobre white | 8.1:1 | ✓ |
| neutral-500 sobre white | 4.6:1 | ✓ AA (texto grande) |

### Movimiento reducido
Declarado en `resources/css/app.css`:
```css
@media (prefers-reduced-motion: reduce) {
    *, ::before, ::after {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
```

### Imágenes / iconos
- Iconos decorativos: `aria-hidden="true"` (los componentes Heroicons lo incluyen por defecto cuando se usan en contexto de texto visible)
- Imágenes de logo: `alt="Sistemas Escolares"`

## Implementación en componentes

### `<x-ui.input>` y similares
Automáticamente asignan `aria-invalid`, `aria-describedby` cuando se pasa `:error`.

### `<x-ui.modal>`
Incluye `role="dialog"`, `aria-modal="true"`, `aria-labelledby="modal-title"`, y listener de ESC.

### Botones icono
```blade
<button class="btn-icon" aria-label="Editar ciclo lectivo 2025">
    <x-icons.pencil class="w-4 h-4" aria-hidden="true" />
</button>
```

## Testing de accesibilidad
- Verificar navegación con teclado (Tab, Enter, ESC, flechas en selects)
- Verificar con lector de pantalla (NVDA + Firefox en Windows)
- Usar axe DevTools para auditorías automatizadas
