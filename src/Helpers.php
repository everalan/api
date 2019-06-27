<?php namespace Everalan\Api;

use Everalan\Api\Http\Factory;

trait Helpers
{
    public function response()
    {
        return new Factory();
    }
}
