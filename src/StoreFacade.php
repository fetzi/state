<?php

namespace Karriere\State;

use Illuminate\Support\Facades\Facade;

class StoreFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'store';
    }
}
