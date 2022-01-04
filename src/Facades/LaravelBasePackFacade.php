<?php

namespace Alangiacomin\LaravelBasePack\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed callWithInjection(object $obj, string $method, ...$params)
 * @method static checkObjectType(mixed $object, string $string)
 * @method static checkObject(mixed $object, callable $callback)
 */
class LaravelBasePackFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'LaravelBasePack';
    }
}
