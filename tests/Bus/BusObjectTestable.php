<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use ReflectionMethod;

class BusObjectTestable extends BusObject
{
    public function assignNewId(): void
    {
        $method = new ReflectionMethod(BusObject::class, 'assignNewId');
        $method->setAccessible(true);
        echo $method->invoke($this);
    }
}
