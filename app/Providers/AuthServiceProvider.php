<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Policies\ContainerPolicy;
use App\Policies\ContainerVpnPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('administrator') ? true : null;
        });

        // Container
        Gate::define('view-any-container', [ContainerPolicy::class, 'viewAny']);

        // Container VPN
        Gate::define('manage-container-vpn', [ContainerVpnPolicy::class, 'manage']);
        Gate::define('check-status-container-vpn', [ContainerVpnPolicy::class, 'checkStatus']);
        Gate::define('check-pid-numbers-container-vpn', [ContainerVpnPolicy::class, 'checkPidNumbers']);
        Gate::define('start-container-vpn', [ContainerVpnPolicy::class, 'start']);
        Gate::define('restart-container-vpn', [ContainerVpnPolicy::class, 'restart']);
        Gate::define('stop-container-vpn', [ContainerVpnPolicy::class, 'stop']);
        Gate::define('toggle-start-on-boot-container-vpn', [ContainerVpnPolicy::class, 'toggleStartOnBoot']);
        Gate::define('download-config-container-vpn', [ContainerVpnPolicy::class, 'downloadConfig']);

        // Container Samba

        // Container Nginx
        Gate::define('check-status-container-nginx', [ContainerNginxPolicy::class, 'checkStatus']);
        Gate::define('check-pid-numbers-container-nginx', [ContainerNginxPolicy::class, 'checkPidNumbers']);
        Gate::define('create-location-container-nginx', [ContainerNginxPolicy::class, 'createLocation']);
        Gate::define('remove-location-container-nginx', [ContainerNginxPolicy::class, 'removeLocation']);
        Gate::define('start-container-nginx', [ContainerNginxPolicy::class, 'start']);
        Gate::define('restart-container-nginx', [ContainerNginxPolicy::class, 'restart']);
        Gate::define('reload-container-nginx', [ContainerNginxPolicy::class, 'reload']);
        Gate::define('stop-container-nginx', [ContainerNginxPolicy::class, 'stop']);
    }
}
