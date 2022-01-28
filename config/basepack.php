<?php

use Alangiacomin\LaravelBasePack\Core\Enums\BindingType;
use Alangiacomin\LaravelBasePack\Repositories\IUserRepository;

return [

    /**
     * Namespaces
     */
    'namespaces' => [
        'commands' => 'App\Commands',
        'commandHandlers' => 'App\CommandHandlers',
        'events' => 'App\Events',
        'eventHandlers' => 'App\EventHandlers',
        'repositories' => 'App\Repositories',
    ],

    /**
     * Event listener configuration
     */
    'eventListener' => [
        // Overrides the default value
        'shouldDiscoverEvents' => true,
        // Additional directories
        'directories' => [
            'EventHandlers',
        ],
    ],

    /**
     * Bus configuration
     */
    'bus' => [
        // Table name
        'table' => 'bus_jobs',
    ],

    /**
     * Repositories configuration
     * List Repositories to bind for automatic injection
     */
    'repositories' => [
        [
            'interface' => IUserRepository::class,
            'bindingType' => BindingType::Singleton,
        ],
    ],

    /**
     * Log configuration
     */
    'log' => [
        // Table prefix
        'table_prefix' => '',
    ],

];
