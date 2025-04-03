<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(){
        $this->app->bind('request', function ($app) {
            return Request::capture();
        });
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void{
        // require_once app_path('CustomHelper.php');
    }
}
