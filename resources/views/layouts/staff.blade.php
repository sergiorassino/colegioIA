<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Gestión — ' . config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full" x-data="{ sidebarOpen: false }" @keydown.escape="sidebarOpen = false">

{{-- Sidebar móvil overlay --}}
<div class="relative z-50 lg:hidden" x-show="sidebarOpen" x-cloak>
    <div class="fixed inset-0 bg-gray-900/80" @click="sidebarOpen = false"></div>
    <div class="fixed inset-0 flex">
        <div class="relative mr-16 flex w-full max-w-xs flex-1">
            @include('layouts.partials.staff-sidebar')
        </div>
    </div>
</div>

{{-- Sidebar desktop --}}
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
    @include('layouts.partials.staff-sidebar')
</div>

{{-- Contenido principal --}}
<div class="lg:pl-64">
    {{-- Topbar --}}
    <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
        {{-- Botón menú móvil --}}
        <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
            <span class="sr-only">Abrir menú</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        {{-- Separador --}}
        <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
            <div class="flex flex-1"></div>
            <div class="flex items-center gap-x-4 lg:gap-x-6">
                {{-- Contexto activo: Nivel / Ciclo lectivo --}}
                @if(session('nivel_id') && session('terlec_id'))
                    @php
                        $nivel  = \App\Models\Core\Nivel::find(session('nivel_id'));
                        $terlec = \App\Models\Core\Terlec::find(session('terlec_id'));
                    @endphp
                    <span class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        {{ $nivel?->nombre }} &mdash; {{ $terlec?->ano }}
                    </span>
                @endif

                {{-- Usuario --}}
                <div class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                    @auth('staff')
                        {{ auth('staff')->user()->nombre ?? auth('staff')->user()->dni }}
                    @endauth
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Contenido de la página --}}
    <main class="py-8 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>
