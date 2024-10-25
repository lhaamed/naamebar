<?php

namespace lhaamed\NaameBar;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;


class NaameBarServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->app->singleton('NaameBar', function ($app) {
            return new NaameBarService();
        });
    }

    public function boot(): void
    {


        $this->mergeConfigFrom(__DIR__ . '/config/NaameBar.php','NaameBar');


        $this->publishes([
            __DIR__ . '/config/NaameBar.php' => config_path('NaameBar.php'),
        ],['naame-bar','config','nb:config']);

    }


}
