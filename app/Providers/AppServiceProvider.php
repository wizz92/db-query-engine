<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\ResponseInterface;
use App\Helpers\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResponseInterface::class, function () {
            return new Response();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
