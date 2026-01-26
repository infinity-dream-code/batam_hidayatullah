<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use App\Database\MySqlConnection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override MySQL connection untuk kompatibilitas dengan MySQL versi lama
        Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
            return new MySqlConnection($connection, $database, $prefix, $config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
