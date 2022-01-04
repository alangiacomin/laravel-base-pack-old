<?php

namespace App\CommandHandlers;

use Alangiacomin\LaravelBasePack\CommandHandlers\CommandHandler;
use App\Commands\ExampleAsyncCommand;

class ExampleAsyncCommandHandler extends CommandHandler
{
    /**
     * The command
     *
     * @return  void
     */
    public ExampleAsyncCommand $command;

    /**
     * Execute the command
     *
     * @return  void
     */
    public function execute(): void
    {
        echo "async command\n";
    }
}
