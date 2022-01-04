<?php

namespace Alangiacomin\LaravelBasePack\CommandHandlers;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Bus\BusHandler;
use Alangiacomin\LaravelBasePack\Bus\IBusObject;
use Alangiacomin\LaravelBasePack\Commands\Command;
use Alangiacomin\LaravelBasePack\Events\IEvent;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * The command to be handled
 *
 * @property IBusObject $command
 */
abstract class CommandHandler extends BusHandler implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * Command constructor
     *
     * @param  IBusObject  $busObject
     */
    public function __construct(IBusObject $busObject)
    {
        $this->busObject = $busObject;
    }

    /**
     * Publish event on the bus
     *
     * @param  IEvent  $event
     * @return void
     */
    final public function publish(IEvent $event): void
    {
        Bus::publishEvent($event);
    }

    /**
     * Handle the {@see Command}
     *
     * @return mixed|void
     * @throws Throwable
     */
    final public function handle()
    {
        $this->handleObject($this->busObject);
        if (!isset($this->job) || $this->job->getQueue() == 'sync')
        {
            return LaravelBasePackFacade::callWithInjection($this, 'getResult');
        }
    }

    /**
     * Gets default result, overridable
     *
     * @return null
     */
    public function getResult()
    {
        return null;
    }
}
