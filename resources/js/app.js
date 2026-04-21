/**
 * Punto de entrada Vite.
 * No importar Alpine aquí: Livewire 3 ya lo incluye y registra plugins (p. ej. navigate).
 * Importar alpinejs aparte provoca "Detected multiple instances of Alpine" y
 * Alpine.navigate is not a function en redirects con navigate: true.
 */
import './bootstrap';
