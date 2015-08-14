<?php

namespace PhpSoft\Illuminate\ArrayView\Providers;

use Illuminate\Support\ServiceProvider;

class ArrayViewServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        //
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('phpsoft.arrayview', function ($app) {

            $viewPaths = $app['view']->getFinder()->getPaths();
            return new ArrayView($app, $viewPaths);
        });
    }
}
