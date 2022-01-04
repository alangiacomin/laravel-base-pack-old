<?php

namespace Alangiacomin\LaravelBasePack\Bus;

use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Events\IEvent;
use Alangiacomin\PhpUtils\DateTime;
use Illuminate\Support\Facades\DB;
use Throwable;

class Bus
{
    /**
     * Send and execute command synchronously
     *
     * @param  ICommand  $command
     * @return mixed
     */
    final public static function executeCommand(ICommand $command): mixed
    {
        $handlerClass = config('basepack.namespaces.commandHandlers').'\\'.$command->handlerName();
        return (new $handlerClass($command))->handle($command);
    }

    /**
     * Send and execute command asynchronously
     *
     * @param  ICommand  $command
     */
    final public static function sendCommand(ICommand $command)
    {
        $handlerClass = config('basepack.namespaces.commandHandlers').'\\'.$command->handlerName();
        call_user_func(array($handlerClass, 'dispatch'), $command);
    }

    /**
     * Send event which will be handled asynchronously
     *
     * @param  IEvent  $event
     * @return void
     */
    final public static function publishEvent(IEvent $event): void
    {
        $classNameWithNamespace = $event->class();
        call_user_func(array($classNameWithNamespace, 'dispatch'), $event->props());
    }

    /**
     * Send event which will be handled synchronously
     *
     * @param  IEvent  $event
     * @return void
     */
    final public static function raiseEvent(IEvent $event): void
    {
        $classNameWithNamespace = $event->class();
        (new $classNameWithNamespace())->handle($event);
    }

    /**
     * Log new bus object
     *
     * @param  IBusObject  $object
     * @return void
     */
    final public static function logNew(IBusObject $object): void
    {
        if (!isset($object))
        {
            return;
        }

        DB::table(config('basepack.bus.table'))->insert(
            [
                'object_id' => $object->id,
                'correlation_id' => $object->correlationId,
                'class' => $object->class(),
                'payload' => $object->payload(),
                'created_at' => DateTime::now()
            ]
        );
    }

    /**
     * Log executed bus object
     *
     * @param  IBusObject  $object
     * @return void
     */
    final public static function logDone(IBusObject $object): void
    {
        if (!isset($object))
        {
            return;
        }

        DB::table(config('basepack.bus.table'))
            ->where('object_id', $object->id)
            ->update(
                [
                    'done_at' => DateTime::now()
                ]
            );
    }

    /**
     * Log failed bus object execution
     *
     * @param  IBusObject  $object
     * @param  Throwable  $exception
     * @return void
     */
    final public static function logFailed(IBusObject $object, Throwable $exception): void
    {
        if (!isset($object))
        {
            return;
        }

        DB::table(config('basepack.bus.table'))
            ->where('object_id', $object->id)
            ->update(
                [
                    'done_at' => DateTime::now(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace()
                ]
            );
    }
}
