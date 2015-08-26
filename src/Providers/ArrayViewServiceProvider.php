<?php

namespace PhpSoft\ArrayView\Providers;

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

            $finder = $app['view']->getFinder();
            return new ArrayView($finder);
        });
    }
}
