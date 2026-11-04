<?php

namespace lhaamed\NaameBar;

use Illuminate\Support\ServiceProvider;


class NaameBarServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/NaameBar.php', 'NaameBar');

        $this->app->singleton(NaameBarService::class, function ($app) {
            return new NaameBarService();
        });
        $this->app->alias(NaameBarService::class, 'NaameBar');
    }

    public function boot(): void
    {




        $this->publishes([
            __DIR__ . '/config/NaameBar.php' => config_path('NaameBar.php'),
        ],['naame-bar','config','nb:config']);

    }


}
