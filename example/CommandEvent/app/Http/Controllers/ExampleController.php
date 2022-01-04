<?php

namespace App\Http\Controllers;

use Alangiacomin\LaravelBasePack\Controllers\Controller;
use App\Commands\ExampleAsyncCommand;
use App\Commands\ExampleCommand;
use App\Commands\ExampleSyncCommand;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function actionResult(Request $request)
    {
        $res = $this->execute(new ExampleCommand(array(
            'prop' => $request->query('myKey'),
        )));
        echo "actionResult\n";
        return $res;
    }

    public function actionCommand(Request $request)
    {
        $this->execute(new ExampleSyncCommand(array(
            'prop' => $request->query('myKey'),
        )));
        $this->sendCommand(new ExampleAsyncCommand(array(
            'prop' => $request->query('myKey'),
        )));
        echo "actionCommand\n";
    }

    public function actionAsyncEvent()
    {
        $res = $this->execute(new ExampleCommand(array(
            'prop' => 'asyncEvent',
        )));
        echo "actionAsyncEvent\n";
        return $res;
    }
}
