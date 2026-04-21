<div class="flex grow flex-col overflow-y-auto bg-brand-jet pb-4">

    {{-- Logo / marca --}}
    <div class="flex h-16 shrink-0 items-center gap-3 px-4 border-b border-white/10">
        <x-brand.logo size="sm" mode="dark" />
    </div>

    {{-- Contexto activo (nivel + año lectivo) --}}
    @if(session('nivel_id') && session('terlec_id'))
        @php
            $ctxNivel  = \App\Models\Core\Nivel::find(session('nivel_id'));
            $ctxTerlec = \App\Models\Core\Terlec::find(session('terlec_id'));
        @endphp
        <div class="mx-4 mt-4 flex items-center gap-2 rounded-lg bg-white/5 px-3 py-2">
            <x-icons.academic-cap class="w-4 h-4 text-brand-secondary shrink-0" />
            <div class="min-w-0">
                <p class="text-2xs font-semibold text-brand-secondary uppercase tracking-wide truncate">
                    {{ $ctxNivel?->nivel }}
                </p>
                <p class="text-xs text-neutral-400 truncate">Ciclo {{ $ctxTerlec?->ano }}</p>
            </div>
        </div>
    @endif

    {{-- Navegación --}}
    <nav class="flex flex-1 flex-col mt-4 px-3" aria-label="Menú principal">
        @auth('staff')
            @php
                $resolver  = app(\App\Auth\MenuResolver::class);
                $menuItems = $resolver->resolver(auth('staff')->user());
            @endphp

            <ul role="list" class="flex flex-col gap-0.5">
                @foreach($menuItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}"
                           wire:navigate
                           @class([
                               'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-150',
                               'nav-item-active' => request()->routeIs($item['route']),
                               'nav-item'        => !request()->routeIs($item['route']),
                           ])>
                            <x-dynamic-component
                                :component="'icons.' . ($item['icon'] ?? 'document-text')"
                                class="w-4 h-4 shrink-0"
                            />
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endauth
    </nav>

    {{-- Info del usuario en la parte baja --}}
    @auth('staff')
        <div class="mt-auto mx-3 border-t border-white/10 pt-4">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-white">
                        {{ strtoupper(substr(auth('staff')->user()->nombre ?? auth('staff')->user()->dni, 0, 2)) }}
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">
                        {{ auth('staff')->user()->nombre ?? auth('staff')->user()->dni }}
                    </p>
                    <p class="text-2xs text-neutral-400 truncate">DNI {{ auth('staff')->user()->dni }}</p>
                </div>
            </div>
        </div>
    @endauth
</div>
