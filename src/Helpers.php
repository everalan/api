<?php namespace Everalan\Api;

use Everalan\Api\Http\Factory;
use Illuminate\Support\Facades\Auth;

trait Helpers
{
    public function response()
    {
        return new Factory();
    }

    public function user()
    {
        return Auth::user();
    }
}
