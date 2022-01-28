<?php

namespace Alangiacomin\LaravelBasePack;

use Alangiacomin\LaravelBasePack\Console\Commands\CreateCommand;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateEvent;
use Alangiacomin\LaravelBasePack\Middleware\CommandResult;
use Alangiacomin\LaravelBasePack\Services\ILogger;
use Alangiacomin\LaravelBasePack\Services\Logger;
use Illuminate\Support\ServiceProvider;

class LaravelBasePackServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('LaravelBasePack', function () {
            return new LaravelBasePack();
        });

        $this->app->singleton(ILogger::class, Logger::class);
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/basepack.php' => config_path('basepack.php'),
        ]);

        // $this->publishes([
        //     __DIR__.'/../example/CommandEvent/app/' => app_path(),
        // ], 'basepack-example-command-event');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole())
        {
            $this->commands([
                CreateCommand::class,
                CreateEvent::class,
            ]);
        }

        app('router')->aliasMiddleware('CommandResult', CommandResult::class);
    }
}
