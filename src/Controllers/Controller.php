<?php

namespace Alangiacomin\LaravelBasePack\Controllers;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Commands\Command;
use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Execute {@see Command} over the bus
     *
     * @param  ICommand  $command
     * @return mixed
     */
    final public function execute(ICommand $command): mixed
    {
        return Bus::executeCommand($command);
    }

    /**
     * Send {@see Command} on the bus
     *
     * @param  ICommand  $command
     * @return void
     */
    final public function sendCommand(ICommand $command): void
    {
        Bus::sendCommand($command);
    }

}
