<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Autogestión — ' . config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full">

{{-- Mobile-first bottom navigation --}}
<div class="min-h-full pb-16">
    {{-- Header mobile --}}
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between h-14 px-4">
            <span class="font-bold text-indigo-600 text-base">{{ config('app.name') }}</span>
            @auth('alumno')
                <span class="text-sm text-gray-600 truncate max-w-[160px]">
                    {{ auth('alumno')->user()->nombre ?? auth('alumno')->user()->dni }}
                </span>
            @endauth
        </div>
    </header>

    {{-- Contenido --}}
    <main class="px-4 py-6">
        {{ $slot }}
    </main>
</div>

{{-- Bottom navigation (mobile-first) --}}
@auth('alumno')
<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200">
    <div class="grid grid-cols-4 h-16">
        <a href="{{ route('alumno.dashboard') }}" class="flex flex-col items-center justify-center gap-1 text-xs {{ request()->routeIs('alumno.dashboard') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
            Inicio
        </a>
        <a href="{{ route('alumno.calificaciones') }}" class="flex flex-col items-center justify-center gap-1 text-xs {{ request()->routeIs('alumno.calificaciones') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
            Notas
        </a>
        <a href="{{ route('alumno.materias') }}" class="flex flex-col items-center justify-center gap-1 text-xs {{ request()->routeIs('alumno.materias') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
            Materias
        </a>
        <a href="{{ route('alumno.perfil') }}" class="flex flex-col items-center justify-center gap-1 text-xs {{ request()->routeIs('alumno.perfil') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
            Mi perfil
        </a>
    </div>
</nav>
@endauth

@livewireScripts
</body>
</html>
