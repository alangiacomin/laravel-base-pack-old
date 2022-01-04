<?php

namespace App\CommandHandlers;

use Alangiacomin\LaravelBasePack\CommandHandlers\CommandHandler;
use App\Commands\ExampleSyncCommand;

class ExampleSyncCommandHandler extends CommandHandler
{
    /**
     * The command
     *
     * @return  void
     */
    public ExampleSyncCommand $command;

    /**
     * Execute the command
     *
     * @return  void
     */
    public function execute(): void
    {
        echo "sync command\n";
    }
}
