<?php

namespace App\EventHandlers;

use Alangiacomin\LaravelBasePack\EventHandlers\EventHandler;
use App\Commands\ExampleCommand;
use App\Events\ExampleEvent;

class ExampleEventHandler extends EventHandler
{
    /**
     * The event
     *
     * @var ExampleEvent
     */
    public ExampleEvent $event;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(ExampleEvent $event)
    {
        //
    }

    /**
     * Execute the event
     *
     * @return void
     */
    public function execute(): void
    {
        if ($this->event->prop == 'asyncCommand') {
            echo "ExampleEventHandler async command\n";
            $this->sendCommand(new ExampleCommand());
        }
        echo print_r($this->event, true) . "\n";
    }
}
