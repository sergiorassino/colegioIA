<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-indigo-600 px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center">
        <span class="text-white font-bold text-lg">{{ config('app.name') }}</span>
    </div>

    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    @auth('staff')
                        @php
                            $resolver = app(\App\Auth\MenuResolver::class);
                            $menuItems = $resolver->resolver(auth('staff')->user());
                        @endphp
                        @foreach($menuItems as $item)
                            <li>
                                <a href="{{ route($item['route']) }}"
                                   @class([
                                       'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                       'bg-indigo-700 text-white' => request()->routeIs($item['route']),
                                       'text-indigo-200 hover:text-white hover:bg-indigo-700' => !request()->routeIs($item['route']),
                                   ])>
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                    @endauth
                </ul>
            </li>
        </ul>
    </nav>
</div>
