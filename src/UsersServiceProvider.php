<?php

namespace Pharaonic\Laravel\Users;

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
        // Views
        // $this->loadViewsFrom(__DIR__ . '/views', 'laravel-menus');

        // Publishes
        $this->publishes([
        //     __DIR__ . '/views'  => resource_path('views/vendor/laravel-menus'),

            __DIR__ . '/database/migrations/2021_02_01_000019_create_devices_table.php'             => database_path('migrations/2021_02_01_000019_create_devices_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000020_create_operation_systems_table.php'   => database_path('migrations/2021_02_01_000020_create_operation_systems_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000021_create_browsers_table.php'            => database_path('migrations/2021_02_01_000021_create_browsers_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000022_create_bots_table.php'                => database_path('migrations/2021_02_01_000022_create_bots_table.php'),
            __DIR__ . '/database/migrations/2021_02_01_000023_create_agents_table.php'              => database_path('migrations/2021_02_01_000023_create_agents_table.php'),
        ], ['pharaonic', 'laravel-users']);

        // Blade - Directive
        // Blade::directive('menu', function ($section) {
        //     $section = Menu::section(trim($section, '\'"'))->get();
        //     if ($section->isEmpty()) return;

        //     return view('laravel-menus::section', ['section' => $section])->render();
        // });
    }
}
