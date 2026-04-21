# Design System — SistemasEscolares

## Paleta de marca

| Token | Hex | Uso |
|---|---|---|
| `brand.jet` | `#333333` | Texto principal, fondo sidebar |
| `brand.primary` | `#40848D` | Acciones primarias, focus ring, activos en sidebar |
| `brand.secondary` | `#739FA5` | Botones secundarios, badges informativos |
| `brand.surface` | `#C1D7DA` | Fondos sutiles, hover de filas, chips |
| `brand.white` | `#FFFFFF` | Fondo de cards, modales |

### Escala primary (hover, disabled, pressed)

```
primary-50:  #f0f8f9   ← fondos hover muy sutiles
primary-100: #d9eff2   ← badges light
primary-500: #40848D   ← base
primary-600: #346f77   ← hover botones
primary-700: #2c5c63   ← active/pressed
```

### Semánticos

| Token | Uso |
|---|---|
| `success-500` | Alertas de éxito, toasts OK |
| `warning-500` | Alertas de advertencia |
| `danger-600` | Errores de validación, botones destructivos |
| `info-500` | Información neutral |

## Tipografía

Sistema sin fuentes externas (System UI stack):

```css
font-family: -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
```

### Escalas de texto usadas

| Clase | Tamaño | Uso |
|---|---|---|
| `text-2xs` | 0.625rem | Badges, sub-labels, indicadores |
| `text-xs` | 0.75rem | Labels de tabla, hints |
| `text-sm` | 0.875rem | Contenido principal de formularios y listas |
| `text-base` | 1rem | Títulos de cards, wordmark |
| `text-xl`  | 1.25rem | Títulos de pantalla (`page-header`) |
| `text-2xl` | 1.5rem | Títulos grandes (headings H1) |

## Radios, sombras, espaciado

```
rounded-lg  (8px)  → campos de formulario, botones
rounded-xl  (12px) → cards, modales
rounded-full       → avatars, badges redondos

shadow-card  → cards en reposo
shadow-modal → modales y dropdowns

padding de página: px-4 py-6 sm:px-6 lg:px-8
max-width contenido staff: max-w-7xl
```

## Breakpoints

| Nombre | Valor | Uso |
|---|---|---|
| xs | 380px | Autogestión mobile-first |
| sm | 640px | Responsive estándar |
| md | 768px | Columnas extras en tablas |
| lg | 1024px | Sidebar visible, layouts de 2 col |
| xl | 1280px | Contenido amplio |

## Identidad visual

- Logo: `public/img/logo-se.png` (PNG real del cliente)
- Logo SVG dark: `public/img/logo-se-dark.svg` (sidebar sobre fondo oscuro)
- Logo SVG light: `public/img/logo-se-light.svg` (topbar sobre blanco)
- Favicon: `public/favicon.ico`

### Componentes de marca

```blade
{{-- En sidebar (fondo oscuro) --}}
<x-brand.logo size="sm" mode="dark" />

{{-- En layout auth (fondo oscuro) --}}
<x-brand.logo size="lg" mode="dark" />

{{-- Wordmark con tagline --}}
<x-brand.wordmark mode="dark" />
<x-brand.wordmark mode="light" :tagline="false" />
```
