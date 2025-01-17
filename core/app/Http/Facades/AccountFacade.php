<?php

namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class AccountFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'accounts'; // This should match the alias you registered in your service provider
    }
}
