<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Configuration Information
     |--------------------------------------------------------------------------
     |
     | This variable is used to determine the file-name of the module's
     | configuration file, relative to the module's base directory.
     |
     */

    // Configuration file information
    'registration' => [
        /*
         |----------------------------------------------------------------------
         | Configuration file information
         |----------------------------------------------------------------------
         |
         | This section is used to explain how to load and configure modules
         |
         */

        // Name of the file to look for in each module folder
        'file'      => env('MODULE_REGISTATION', 'register.php'),

        // File path to the directory where all modules are stored
        'directory' => env('MODULE_PATH', base_path('modules')),

        // Base namespace to use for modules
        'namespace' => env('MODULE_NAMESPACE', 'Modules'),
    ],
    'default' => [
        /*
         |----------------------------------------------------------------------
         | Default Configuration Options
         |----------------------------------------------------------------------
         |
         | These are the default configration options used when reading from
         | a module's config file. If a path is not set in the module's
         | configuration, then these paths will be used instead.
         |
         */

        // Module Name (Overwritten by this package, corresponds to the folder name)
        'module'        => '',

        // A brief description
        'brief'         => '',

        // A more detailed description
        'description'   => '',

        // The author who wrote the module
        'author'        => '',

        // Whether the module is enabled or disabled
        'enabled'       => true,

        // Folder and namespace structure for the module
        'paths'         => [
            // HTTP Controllers
            'controllers'   => 'Http/Controllers',

            // Console Commands and Configuration
            'console'       => 'Console',

            // HTTP Middleware Layers
            'middleware'    => 'Http/Middleware',

            // Database Migrations
            'migrations'    => 'Database/Migrations',

            // Translations
            'lang'          => 'Lang',

            // Eloquent Models
            'models'        => 'Models',

            // Service Providers
            'providers'     => 'Providers',

            // Repositories for the Repository Pattern
            'repositories'  => 'Repositories',

            // HTTP Request Classes
            'requests'      => 'Http/Requests',

            // HTTP Routers
            'routes'        => 'Http/Routes',

            // Database Seeders
            'seeders'       => 'Database/Seeders',

            // PHPUnit Tests
            'tests'         => 'Tests',

            // HTTP Views (e.g. Blade Templates)
            'views'         => 'Http/Views',
        ],

        // Namespace overrides
        'namespaces'    => [],

        // Providers
        'providers'     => [],
    ],
];
