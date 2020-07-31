<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function boot(){
        /*if (env('DB_LOGGING', false) === true) {
            DB::listen(function($query) {
                Log::info($query->sql, $query->bindings, $query->time);
            });
        }*/
    }
}
