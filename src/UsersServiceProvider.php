<?php

namespace Pharaonic\Laravel\Users;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
            __DIR__ . '/database/migrations/2021_02_01_000019_create_devices_table.php'                 => database_path('migrations/2021_02_01_000019_create_devices_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000020_create_operation_systems_table.php'       => database_path('migrations/2021_02_01_000020_create_operation_systems_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000021_create_browsers_table.php'                => database_path('migrations/2021_02_01_000021_create_browsers_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000022_create_bots_table.php'                    => database_path('migrations/2021_02_01_000022_create_bots_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000023_create_agents_table.php'                  => database_path('migrations/2021_02_01_000023_create_agents_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000024_create_user_agents_table.php'             => database_path('migrations/2021_02_01_000024_create_user_agents_table.php'),

            __DIR__ . '/database/migrations/2021_02_01_000025_create_user_password_history_table.php'   => database_path('migrations/2021_02_01_000025_create_user_password_history_table.php'),
           
            __DIR__ . '/database/migrations/2021_02_01_000026_create_permissions_table.php'             => database_path('migrations/2021_02_01_000026_create_permissions_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000027_create_permissibles_table.php'            => database_path('migrations/2021_02_01_000027_create_permissibles_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000028_create_roles_table.php'                   => database_path('migrations/2021_02_01_000028_create_roles_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000029_create_roleables_table.php'               => database_path('migrations/2021_02_01_000029_create_roleables_table.php'),

        ], ['pharaonic', 'laravel-users']);
    }
}
