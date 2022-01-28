<?php

namespace Alangiacomin\LaravelBasePack\Services;

use Alangiacomin\LaravelBasePack\Bus\IBusObject;

interface ILogger
{
    function sent(IBusObject $obj);

    function received(IBusObject $obj);

}
