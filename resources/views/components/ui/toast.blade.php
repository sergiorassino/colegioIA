{{--
    Toast container (ARIA live region).
    Incluir una sola vez en el layout principal.
    Los componentes Livewire disparan: $this->dispatch('toast', message: '…', type: 'success')
--}}
<div
    x-data="{
        toasts: [],
        add(toast) {
            toast.id = Date.now();
            this.toasts.push(toast);
            if (toast.duration !== 0) {
                setTimeout(() => this.remove(toast.id), toast.duration ?? 4000);
            }
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @toast.window="add({ message: $event.detail.message, type: $event.detail.type ?? 'success', duration: $event.detail.duration })"
    class="fixed bottom-4 right-4 z-[9998] flex flex-col gap-2 max-w-sm w-full pointer-events-none"
    aria-live="polite"
    aria-atomic="false"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            class="pointer-events-auto flex items-start gap-3 rounded-xl px-4 py-3 shadow-modal text-sm
                   animate-slide-in-right"
            :class="{
                'bg-success-500 text-white': toast.type === 'success',
                'bg-danger-600 text-white':  toast.type === 'danger' || toast.type === 'error',
                'bg-warning-500 text-white': toast.type === 'warning',
                'bg-primary-500 text-white': toast.type === 'info' || !toast.type,
            }"
            role="status"
        >
            {{-- Icon --}}
            <template x-if="toast.type === 'success'">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </template>
            <template x-if="toast.type === 'danger' || toast.type === 'error' || toast.type === 'warning'">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </template>

            <p class="flex-1" x-text="toast.message"></p>

            <button
                @click="remove(toast.id)"
                class="opacity-70 hover:opacity-100 transition-opacity"
                aria-label="Cerrar notificación"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
