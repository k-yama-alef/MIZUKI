<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AlefServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(
        'AlefCommon',
        'App\Library\AlefCommon'
      );
    }
}
