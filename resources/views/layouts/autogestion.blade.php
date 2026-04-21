<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#40848D">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $title ?? 'Autogestión — ' . config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-neutral-50">

    {{-- Barra de progreso Livewire --}}
    <div
        class="fixed top-0 left-0 right-0 z-[9999] h-[2px] bg-primary-500 origin-left scale-x-0 transition-transform duration-300"
        wire:loading.class.remove="scale-x-0"
        wire:loading.class="scale-x-100"
    ></div>

    {{-- ── HEADER MOBILE ── --}}
    <header class="sticky top-0 z-40 bg-brand-jet border-b border-white/10 shadow-sm"
            style="padding-top: env(safe-area-inset-top)">
        <div class="flex items-center justify-between h-14 px-4">
            <x-brand.logo size="sm" mode="dark" />

            @auth('alumno')
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-primary-500 flex items-center justify-center shrink-0">
                        <span class="text-2xs font-bold text-white">
                            {{ strtoupper(substr(auth('alumno')->user()->nombre ?? auth('alumno')->user()->dni, 0, 2)) }}
                        </span>
                    </div>
                    <span class="text-sm text-neutral-300 truncate max-w-[140px] xs:max-w-[180px]">
                        {{ auth('alumno')->user()->nombre ?? ('DNI ' . auth('alumno')->user()->dni) }}
                    </span>
                </div>
            @endauth
        </div>
    </header>

    {{-- Contenido —  espacio inferior para bottom nav --}}
    <main id="main-content" class="px-4 py-6 pb-24 max-w-xl mx-auto"
          style="padding-bottom: calc(5rem + env(safe-area-inset-bottom))">
        {{ $slot }}
    </main>

    {{-- ── BOTTOM NAVIGATION ── --}}
    @auth('alumno')
        <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-neutral-200 shadow-[0_-1px_4px_rgb(0,0,0/0.06)]"
             aria-label="Navegación principal"
             style="padding-bottom: env(safe-area-inset-bottom)">
            <div class="grid grid-cols-4 h-16">

                <a href="{{ route('alumno.dashboard') }}"
                   wire:navigate
                   class="flex flex-col items-center justify-center gap-1 text-2xs font-medium transition-colors
                          {{ request()->routeIs('alumno.dashboard') ? 'text-primary-600' : 'text-neutral-500 hover:text-primary-500' }}"
                   aria-label="Inicio">
                    <x-icons.home class="h-5 w-5" />
                    <span>Inicio</span>
                </a>

                <a href="{{ route('alumno.calificaciones') }}"
                   wire:navigate
                   class="flex flex-col items-center justify-center gap-1 text-2xs font-medium transition-colors
                          {{ request()->routeIs('alumno.calificaciones') ? 'text-primary-600' : 'text-neutral-500 hover:text-primary-500' }}"
                   aria-label="Calificaciones">
                    <x-icons.document-text class="h-5 w-5" />
                    <span>Notas</span>
                </a>

                <a href="{{ route('alumno.materias') }}"
                   wire:navigate
                   class="flex flex-col items-center justify-center gap-1 text-2xs font-medium transition-colors
                          {{ request()->routeIs('alumno.materias') ? 'text-primary-600' : 'text-neutral-500 hover:text-primary-500' }}"
                   aria-label="Materias">
                    <x-icons.academic-cap class="h-5 w-5" />
                    <span>Materias</span>
                </a>

                <a href="{{ route('alumno.perfil') }}"
                   wire:navigate
                   class="flex flex-col items-center justify-center gap-1 text-2xs font-medium transition-colors
                          {{ request()->routeIs('alumno.perfil') ? 'text-primary-600' : 'text-neutral-500 hover:text-primary-500' }}"
                   aria-label="Mi perfil">
                    <x-icons.user class="h-5 w-5" />
                    <span>Mi perfil</span>
                </a>

            </div>
        </nav>
    @endauth

    {{-- Toast global --}}
    <x-ui.toast />

    @livewireScripts

</body>
</html>
