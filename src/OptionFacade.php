<?php

namespace StrongNguyen\Options;

use Illuminate\Support\Facades\Facade;

class OptionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'option';
    }
}
