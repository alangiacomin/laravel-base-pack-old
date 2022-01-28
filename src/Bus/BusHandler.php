<?php

namespace Alangiacomin\LaravelBasePack\Bus;

use Alangiacomin\LaravelBasePack\CommandHandlers\CommandHandler;
use Alangiacomin\LaravelBasePack\EventHandlers\EventHandler;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Services\ILogger;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

abstract class BusHandler implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * {@see Command} or {@see Event} to handle
     *
     * @var IBusObject
     */
    protected IBusObject $busObject;

    /**
     * Maximum retries if handling fails
     *
     * @var int
     */
    protected int $maxRetries = 3;

    /**
     * Defines if handling is active, used by retries algorithm
     *
     * @var bool
     */
    protected bool $isActive = true;

    /**
     * Handle the job
     *
     * @param  IBusObject|null  $busObject
     * @return void
     * @throws Throwable
     */
    final protected function handleObject(IBusObject $busObject = null): void
    {
        if (isset($busObject))
        {
            $this->busObject = $busObject;
        }

        $retry = 0;

        while ($this->isActive)
        {
            try
            {
                if (++$retry > $this->maxRetries)
                {
                    break;
                }

                $this->handleObjectExecution();
                $this->isActive = false;
            }
            catch (Throwable $ex)
            {
                LaravelBasePackFacade::callWithInjection($this, 'failed', ['ex' => $ex]);

                // if sync, re-throws exception
                if (!$this->isJob() || $this->job->getQueue() == 'sync')
                {
                    throw $ex;
                }

                // if async, handle the failure
                $this->notifyFailures($this->busObject, $ex);
            }
        }
    }

    /**
     * Entry point of object handling execution
     *
     * @param  ILogger  $logger
     * @return void
     * @throws Exception
     */
    final public function handleObjectExecution(): void
    {
        $this->setTypedObject();

        LaravelBasePackFacade::callWithInjection($this, 'execute');

        $logger = LaravelBasePackFacade::injectedInstance(ILogger::class);
        $logger->received($this->busObject);
    }

    /**
     * Execute the command or event body
     */
    abstract protected function execute();

    /**
     * Sets generic bus object as the specific typed object managed by the handler
     *
     * @return void
     * @throws Exception
     */
    private function setTypedObject(): void
    {
        if ($this instanceof CommandHandler)
        {
            if (!property_exists($this, "command"))
            {
                throw new Exception("'command' property must be defined");
            }
            $this->command = $this->busObject;
        }
        elseif ($this instanceof EventHandler)
        {
            if (!property_exists($this, "event"))
            {
                throw new Exception("'event' property must be defined");
            }
            $this->event = $this->busObject;
        }
        else
        {
            throw new Exception("Handler must be a 'CommandHandler' or an 'EventHandler'");
        }
    }

    /**
     * Sets handling definitely failed
     * Must be public because can be called from outside scope (by Laravel)
     *
     * @param  Throwable  $ex
     * @param  ILogger  $logger
     * @return void
     */
    final public function failed(Throwable $ex, ILogger $logger): void
    {
        $this->fail($ex);
        $this->isActive = false;
        $logger->exception($this->busObject, $ex);
    }

    /**
     * Manage the failed handling.
     * Default keeps handling active, can be overridden.
     *
     * @param  IBusObject  $busObject
     * @param  Throwable  $ex
     * @return void
     * @noinspection PhpUnusedParameterInspection
     */
    protected function notifyFailures(IBusObject $busObject, Throwable $ex): void
    {
        $this->isActive = true;
        if (isset($this->busObject))
        {
            $this->busObject = $this->busObject->clone();
        }
    }

    /**
     * Detects if handling a Laravel Job
     *
     * @return bool
     */
    final protected function isJob(): bool
    {
        return isset($this->job);
    }
}
