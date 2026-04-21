<?php

namespace App\Providers;

use App\Auth\ContextoAutogestion;
use App\Auth\ContextoStaff;
use App\Auth\LegacyUserProvider;
use App\Auth\MenuResolver;
use App\Auth\PermisoSlugMap;
use App\Auth\PermisosService;
use App\Listeners\RehashPasswordListener;
use App\Events\PasswordShouldBeRehashed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PermisoSlugMap::class);
        $this->app->singleton(PermisosService::class);
        $this->app->singleton(ContextoStaff::class);
        $this->app->singleton(ContextoAutogestion::class);
        $this->app->singleton(MenuResolver::class);
    }

    public function boot(): void
    {
        // Registrar el driver de autenticación legacy
        Auth::provider('legacy', function ($app, array $config) {
            return new LegacyUserProvider(
                $app['hash'],
                $config['model'],
            );
        });

        // Registrar Gates para cada permiso en permisosusuarios
        try {
            $this->app->make(PermisosService::class)->registrarGates();
        } catch (\Exception) {
            // BD no disponible durante migraciones o tests iniciales
        }

        // Listener para rehasheo progresivo de contraseñas
        Event::listen(PasswordShouldBeRehashed::class, RehashPasswordListener::class);
    }
}
