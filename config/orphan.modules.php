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
        // TODO: Let users override this with the ENV variables
        'directory' => 'modules',
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
        'enabled' => false,
        'paths' => [
            'controllers'   => 'Controllers',
            'lang'          => 'Lang',
            'models'        => 'Models',
            'providers'     => 'Providers',
            'repositories'  => 'Repositories',
            'routes'        => 'Routes',
            'views'         => 'Views',
        ],
    ],
];
