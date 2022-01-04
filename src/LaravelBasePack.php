<?php

namespace Alangiacomin\LaravelBasePack;

use Exception;
use Illuminate\Support\Facades\App;

class LaravelBasePack
{
    public function callWithInjection(object $obj, string $method, ...$params)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return App::call([$obj, $method], $params);
    }

    /**
     * @throws Exception
     */
    public function checkObjectType(mixed $object, string $type)
    {
        if (gettype($object) != $type)
        {
            throw new Exception("Object should be '$type', but '".gettype($object)."' found.");
        }
    }

    /**
     * @throws Exception
     */
    public function checkObject(mixed $object, callable $callback)
    {
        if (!$callback($object))
        {
            throw new Exception("Object check failed.");
        }
    }
}
