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
        'file'      => 'register.php',
        'directory' => env('MODULE_PATH', base_path('modules')),
        'namespace' => env('MODULE_NAMESPACE', '\\Modules'),
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
        'module'        => 'Module',
        'description'   => 'My Module',
        'author'        => 'Anonymous',
        'enabled'       => false,
        'paths'         => [
            'controllers'   => 'Controllers',
            'lang'          => 'Lang',
            'models'        => 'Models',
            'providers'     => 'Providers',
            'repositories'  => 'Repositories',
            'routes'        => 'Routes',
            'views'         => 'Views',
        ],
        'namespaces' => [],
    ],
];
