<?php

namespace Depotwarehouse\Neighbourhoods\Providers;

use Illuminate\Support\ServiceProvider;
use socrata\soda\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Client::class, function() {
            return new Client('https://data.edmonton.ca');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
