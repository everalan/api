<?php namespace Everalan\Laravel\Helper;

use Everalan\Laravel\Helper\Http\Factory;

trait Helper
{
    public function response()
    {
        return new Factory();
    }
}
