<?php

namespace PhpSoft\ArrayView\Providers;

use ChickenCoder\ArrayView\Factory;
use Illuminate\View\ViewFinderInterface;

class ArrayView
{
    /**
     * The view finder implementation.
     *
     * @var \Illuminate\View\ViewFinderInterface
     */
    protected $finder;

    /**
     * The view factory instance.
     *
     * @var \ChickenCoder\ArrayView\Factory
     */
    protected $factory;

    /**
     * Array of registered view name aliases.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * Create a new database manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(ViewFinderInterface $finder)
    {
        $this->finder = $finder;
        $this->finder->addExtension('array.php');
        $this->finder->addExtension('helper.php');
        $this->factory = new Factory($this->finder->getPaths(), $this->finder);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    public function make($view, $data = [], $mergeData = [])
    {
        return $this->render($view, $data, $mergeData);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    public function render($view, $data = [], $mergeData = [])
    {
        if (isset($this->aliases[$view])) {
            $view = $this->aliases[$view];
        }

        $view = $this->normalizeName($view);

        return $this->factory->render($view, $data, $mergeData);
    }

    /**
     * Normalize a view name.
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeName($name)
    {
        $delimiter = ViewFinderInterface::HINT_PATH_DELIMITER;

        if (strpos($name, $delimiter) === false) {
            return str_replace('/', '.', $name);
        }

        list($namespace, $name) = explode($delimiter, $name);

        return $namespace.$delimiter.str_replace('/', '.', $name);
    }

    /**
     * Helper method
     * 
     * @param  string $helper
     * @return mix
     */
    public function helper($helper)
    {
        if (isset($this->aliases[$helper])) {
            $helper = $this->aliases[$helper];
        }

        $helper = $this->normalizeName($helper);

        $args = func_get_args();
        $args[0] = $helper;

        return call_user_func_array([$this->factory, 'helper'], $args);
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
