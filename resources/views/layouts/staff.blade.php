<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Gestión — ' . config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-neutral-50" x-data="{ sidebarOpen: false }" @keydown.escape="sidebarOpen = false">

    {{-- Skip to content --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[9999]
              focus:px-4 focus:py-2 focus:bg-primary-500 focus:text-white focus:rounded-lg focus:shadow-modal
              focus:text-sm focus:font-medium">
        Ir al contenido principal
    </a>

    {{-- Barra de progreso Livewire --}}
    <div
        class="fixed top-0 left-0 right-0 z-[9999] h-[2px] bg-primary-500 origin-left scale-x-0 transition-transform duration-300"
        wire:loading.class.remove="scale-x-0"
        wire:loading.class="scale-x-100"
    ></div>

    {{-- ── SIDEBAR MÓVIL ── --}}
    <div class="relative z-50 lg:hidden" x-show="sidebarOpen" x-cloak
         x-transition:enter="transition ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in-out duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="sidebarOpen = false" aria-hidden="true"></div>
        <div class="fixed inset-0 flex">
            <div class="relative mr-16 flex w-full max-w-[280px] flex-1 sidebar-transition"
                 x-transition:enter="transition ease-in-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                <div class="absolute right-0 top-0 -mr-12 pt-4">
                    <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false" aria-label="Cerrar menú">
                        <x-icons.x-mark class="w-6 h-6 text-white" />
                    </button>
                </div>
                @include('layouts.partials.staff-sidebar')
            </div>
        </div>
    </div>

    {{-- ── SIDEBAR DESKTOP ── --}}
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col" aria-label="Sidebar">
        @include('layouts.partials.staff-sidebar')
    </div>

    {{-- ── CONTENIDO PRINCIPAL ── --}}
    <div class="lg:pl-64 flex flex-col min-h-full">

        {{-- Topbar --}}
        <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-4 border-b border-neutral-200 bg-white px-4 shadow-sm sm:px-6 lg:px-8">

            {{-- Hamburger móvil --}}
            <button
                type="button"
                class="-m-2.5 p-2.5 text-neutral-600 lg:hidden hover:text-primary-600 transition-colors"
                @click="sidebarOpen = true"
                aria-label="Abrir menú"
            >
                <x-icons.bars-3 class="h-6 w-6" />
            </button>

            <div class="h-6 w-px bg-neutral-200 lg:hidden" aria-hidden="true"></div>

            {{-- Breadcrumbs (inyectados por cada página via $breadcrumbs) --}}
            <div class="flex-1 min-w-0">
                @isset($breadcrumbs)
                    {{ $breadcrumbs }}
                @endisset
            </div>

            {{-- Acciones del usuario --}}
            <div class="flex items-center gap-3" x-data="{ userMenuOpen: false }">

                @auth('staff')
                    {{-- User menu dropdown --}}
                    <div class="relative" @click.outside="userMenuOpen = false">
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition-colors"
                            @click="userMenuOpen = !userMenuOpen"
                            aria-haspopup="menu"
                            :aria-expanded="userMenuOpen"
                        >
                            <div class="h-7 w-7 rounded-full bg-primary-500 flex items-center justify-center shrink-0">
                                <span class="text-2xs font-bold text-white">
                                    {{ strtoupper(substr(auth('staff')->user()->nombre ?? auth('staff')->user()->dni, 0, 2)) }}
                                </span>
                            </div>
                            <span class="hidden sm:block truncate max-w-[120px]">
                                {{ auth('staff')->user()->nombre ?? auth('staff')->user()->dni }}
                            </span>
                            <x-icons.chevron-down class="w-4 h-4 text-neutral-400" />
                        </button>

                        {{-- Dropdown --}}
                        <div
                            x-show="userMenuOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-modal border border-neutral-100 py-1 z-50 animate-fade-in"
                            role="menu"
                        >
                            <form method="POST" action="{{ route('staff.logout') }}" class="px-1">
                                @csrf
                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-danger-600 hover:bg-danger-50 transition-colors"
                                    role="menuitem"
                                >
                                    <x-icons.arrow-right-on-rectangle class="w-4 h-4" />
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

            </div>
        </header>

        {{-- Página --}}
        <main id="main-content" class="flex-1 px-4 py-6 sm:px-6 lg:px-8 max-w-7xl w-full mx-auto">
            {{ $slot }}
        </main>

    </div>

    {{-- Toast global --}}
    <x-ui.toast />

    @livewireScripts

    {{-- Listener Alpine para scroll-to-error --}}
    <script>
        document.addEventListener('scroll-to-error', () => {
            const el = document.querySelector('[aria-invalid="true"]');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                el.focus({ preventScroll: true });
            }
        });
    </script>

</body>
</html>
