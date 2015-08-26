<?php

namespace PhpSoft\ArrayView\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Session\SessionManager
 * @see \Illuminate\Session\Store
 */
class ArrayView extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'phpsoft.arrayview';
    }
}
