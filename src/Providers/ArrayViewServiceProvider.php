<?php

namespace PhpSoft\Illuminate\ArrayView\Providers;

use Illuminate\Support\ServiceProvider;

class ArrayViewServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('phpsoft.arrayview', function ($app) {

            $finder = $app['view.finder'];
            $viewPaths = $app['view']->getFinder()->getPaths();
            return new ArrayView($app, $finder, $viewPaths);
        });
    }
}
