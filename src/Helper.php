<?php namespace Everalan\Api;

use Everalan\Api\Http\Factory;

trait Helper
{
    public function response()
    {
        return new Factory();
    }
}
