<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-brand-jet">

    {{-- Skip-to-content --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50
              focus:px-4 focus:py-2 focus:bg-white focus:text-primary-700 focus:rounded-lg focus:shadow-modal
              focus:text-sm focus:font-medium">
        Ir al contenido principal
    </a>

    <main id="main-content" class="min-h-full flex flex-col items-center justify-center px-4 py-12">

        {{-- Logo y marca --}}
        <div class="mb-8 flex flex-col items-center gap-3">
            <x-brand.logo size="lg" mode="dark" />
            <x-brand.wordmark mode="dark" />
        </div>

        {{-- Card de login --}}
        <div class="w-full max-w-md bg-white rounded-2xl shadow-modal overflow-hidden">
            {{ $slot }}
        </div>

        {{-- Link alternativo (slot opcional) --}}
        @isset($footer)
            <div class="mt-6 text-sm text-neutral-400">
                {{ $footer }}
            </div>
        @endisset

    </main>

    <x-ui.toast />

    @livewireScripts
</body>
</html>
