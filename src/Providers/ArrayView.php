<?php

namespace PhpSoft\Illuminate\ArrayView\Providers;

use ChickenCoder\ArrayView\Factory;

class ArrayView
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The view factory instance.
     *
     * @var \ChickenCoder\ArrayView\Factory
     */
    protected $factory;

    /**
     * Create a new database manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app, $viewPaths = [])
    {
        $this->app = $app;
        $this->factory = new Factory($viewPaths);
    }

    /**
     * Dynamically pass methods to the factory.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->factory, $method], $parameters);
    }
}
