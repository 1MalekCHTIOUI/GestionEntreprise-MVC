<?php

namespace App\Providers;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        // if (App::environment('local')) {
        //     // Register a listener for database queries
        //     DB::listen(function ($query) {
        //         // Check if debugging is enabled
        //         if (config('app.debug')) {
        //             // Generate the SQL query string
        //             $sql = Str::replaceArray('?', $query->bindings, $query->sql);
        //             // Log the query to a specific channel named 'database'
        //             Log::channel('database')->debug($sql);
        //         }
        //     });
        // }
    }
}
