<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('ver-administracion', function ($user) {
            return $user && $user->tienePermiso('Administracion', 'Consultar');
        });
        Gate::define('ver-seguridad', function ($user) {
            return $user && $user->tienePermiso('Seguridad', 'Consultar');
        });
        Gate::define('ver-mantenimiento', function ($user) {
            return $user && $user->tienePermiso('Mantenimiento', 'Consultar');
        });
    }
}
