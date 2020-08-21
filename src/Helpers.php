<?php namespace Everalan\Api;

use Everalan\Api\Http\Response;
use Illuminate\Support\Facades\Auth;

trait Helpers
{
    public function response()
    {
        return new Response();
    }

    public function user()
    {
        return Auth::user();
    }
}
