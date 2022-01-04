<?php

namespace Alangiacomin\LaravelBasePack\Models;

use Alangiacomin\LaravelBasePack\Events\IEvent;
use Illuminate\Database\Eloquent\Model;

abstract class Aggregate extends Model implements IAggregate
{
    /**
     * @var IEvent[]
     */
    public array $events = [];

    /**
     * @inheritDoc
     */
    final public function raise(IEvent $event)
    {
        $this->events[] = $event;
    }
}
