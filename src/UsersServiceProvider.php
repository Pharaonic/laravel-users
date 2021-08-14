<?php

namespace Pharaonic\Laravel\Users;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Users\Middleware\Entrusted;
use Pharaonic\Laravel\Users\Middleware\EntrustedAny;
use Pharaonic\Laravel\Users\Middleware\Permitted;
use Pharaonic\Laravel\Users\Middleware\PermittedAny;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Migration Loading
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishes
        $this->publishes([
            __DIR__ . '/database/migrations/2021_02_01_000024_create_user_agents_table.php'             => database_path('migrations/2021_02_01_000024_create_user_agents_table.php'),

            __DIR__ . '/database/migrations/2021_02_01_000025_create_user_password_history_table.php'   => database_path('migrations/2021_02_01_000025_create_user_password_history_table.php'),

            __DIR__ . '/database/migrations/2021_02_01_000026_create_permissions_table.php'             => database_path('migrations/2021_02_01_000026_create_permissions_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000027_create_permission_translations_table.php' => database_path('migrations/2021_02_01_000027_create_permission_translations_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000028_create_permissibles_table.php'            => database_path('migrations/2021_02_01_000028_create_permissibles_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000029_create_roles_table.php'                   => database_path('migrations/2021_02_01_000029_create_roles_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000030_create_role_translations_table.php'       => database_path('migrations/2021_02_01_000030_create_role_translations_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000031_create_roleables_table.php'               => database_path('migrations/2021_02_01_000031_create_roleables_table.php'),

        ], ['pharaonic', 'laravel-users']);


        $this->initPermissionsAndRoles();
    }

    /**
     * Set Blade directives & Router Middlewares.
     *
     * @return void
     */
    private function initPermissionsAndRoles()
    {
        // Router
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('permitted', Permitted::class);
        $router->aliasMiddleware('permittedAny', PermittedAny::class);
        $router->aliasMiddleware('entrusted', Entrusted::class);
        $router->aliasMiddleware('entrustedAny', EntrustedAny::class);

        // ROLES
        Blade::if('entrusted', function (...$roles) {
            if (empty($roles)) return;

            return !auth()->check() ? false : auth()->user()->entrusted($this->prepareParamsArray($roles));
        });

        Blade::if('entrustedAny', function (...$roles) {
            if (empty($roles)) return;

            return !auth()->check() ? false : auth()->user()->entrustedAny($this->prepareParamsArray($roles));
        });

        Blade::if('distrusted', function (...$roles) {
            if (empty($roles)) return;

            return !auth()->check() ? false : auth()->user()->distrusted($this->prepareParamsArray($roles));
        });

        Blade::if('distrustedAny', function (...$roles) {
            if (empty($roles)) return;

            return !auth()->check() ? false : auth()->user()->distrustedAny($this->prepareParamsArray($roles));
        });

        // PERMISSIONS
        Blade::if('permitted', function (...$permissions) {
            if (empty($permissions)) return;

            return !auth()->check() ? false : auth()->user()->permitted($this->prepareParamsArray($permissions));
        });

        Blade::if('permittedAny', function (...$permissions) {
            if (empty($permissions)) return;

            return !auth()->check() ? false : auth()->user()->permittedAny($this->prepareParamsArray($permissions));
        });

        Blade::if('forbad', function (...$permissions) {
            if (empty($permissions)) return;

            return !auth()->check() ? false : auth()->user()->forbad($this->prepareParamsArray($permissions));
        });

        Blade::if('forbadAny', function (...$permissions) {
            if (empty($permissions)) return;

            return !auth()->check() ? false : auth()->user()->forbadAny($this->prepareParamsArray($permissions));
        });
    }

    /**
     * Prepare Roles & Permissions
     *
     * @param array $params
     * @return array
     */
    private function prepareParamsArray($params)
    {
        if (is_array($params[0])) $params = $params[0];
        if (is_string($params)) $params = explode(',', $params);

        return $params;
    }
}
