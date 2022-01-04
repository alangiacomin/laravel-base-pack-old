<?php

namespace App\CommandHandlers;

use Alangiacomin\LaravelBasePack\CommandHandlers\CommandHandler;
use App\Commands\ExampleCommand;
use App\Events\ExampleEvent;

class ExampleCommandHandler extends CommandHandler
{
    /**
     * The command
     *
     * @return  void
     */
    public ExampleCommand $command;

    /**
     * Execute the command
     *
     * @return  void
     */
    public function execute(): void
    {
        switch ($this->command->prop)
        {
            case 'asyncEvent':
                $this->publish(new ExampleEvent(['prop'=>'asyncEvent']));
                break;
            case 'asyncCommand':
                $this->publish(new ExampleEvent(['prop'=>'asyncCommand']));
                break;
        }

        echo "command\n";
    }

    /**
     * @return int[]
     */
    public function getResult(): array
    {
        return [1, 2, 3];
    }
}
