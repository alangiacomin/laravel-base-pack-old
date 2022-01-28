<?php

namespace Alangiacomin\LaravelBasePack\Services;

use Alangiacomin\LaravelBasePack\Bus\IBusObject;
use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Core\Enums\LogType;
use Alangiacomin\LaravelBasePack\Events\IEvent;
use Alangiacomin\PhpUtils\DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class Logger implements ILogger
{
    /**
     * @var string Table name
     */
    private string $table_name;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->table_name = config('basepack.log.table_prefix').'logs';
    }

    /**
     * @param  IBusObject  $obj
     * @return void
     * @throws Exception
     */
    public function sent(IBusObject $obj)
    {
        $this->logObject(
            match ($this->busObjectType($obj))
            {
                ICommand::class => LogType::CommandSent,
                IEvent::class => LogType::EventSent,
                default => throw new Exception("Object not valid"),
            },
            $obj
        );
    }

    /**
     * @param  IBusObject  $obj
     * @return void
     * @throws Exception
     */
    public function received(IBusObject $obj)
    {
        $this->logObject(
            match ($this->busObjectType($obj))
            {
                ICommand::class => LogType::CommandReceived,
                IEvent::class => LogType::EventReceived,
                default => throw new Exception("Object not valid"),
            },
            $obj
        );
    }

    /**
     * @param  IBusObject  $obj
     * @param  Throwable  $ex
     * @return void
     */
    public function exception(IBusObject $obj, Throwable $ex)
    {
        $this->logObject(LogType::Exception, $obj, $ex->getMessage());
    }

    /**
     * @param  LogType  $logType
     * @param  IBusObject  $obj
     * @param  string|null  $message
     * @return void
     */
    private function logObject(LogType $logType, IBusObject $obj, string $message = null)
    {
        DB::table($this->table_name)->insert(
            [
                'correlation_id' => $obj->correlationId,
                'type' => $logType,
                'object_id' => $obj->id,
                'class' => $obj->class(),
                'payload' => is_null($message) ? $obj->payload() : $message,
                'timestamp' => DateTime::now()
            ]
        );
    }

    /**
     * @param  IBusObject  $obj
     * @return string
     */
    private function busObjectType(IBusObject $obj): string
    {
        $types = [
            ICommand::class,
            IEvent::class
        ];
        foreach ($types as $type)
        {
            if (is_a($obj, $type))
            {
                return $type;
            }
        }
        return '';
    }
}
