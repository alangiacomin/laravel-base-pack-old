<?php

namespace Alangiacomin\LaravelBasePack\Core\Enums;

enum BusObjectDirection: int
{
    case Sent = 1;
    case Executed = 2;
    case Received = 3;
}
