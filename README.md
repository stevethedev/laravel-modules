# theorphan/laravel-modules
A Laravel package for modular, organized projects.

## Package Configuration
Two files need to be updated to install this package:

1. `config/app.php`
```
#!php
    'providers' => [
        // ...
        Orphan\Modules\Providers\ModuleServiceProvider::class,
    ]
```

2. `composer.json`
```
#!json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/theorphan/laravel-modules.git"
        }
    ],
    "require": {
        "theorphan/laravel-modules": "*"
    },
    "autoload": {
        "psr-4": {
            "Modules\\": "modules/"
        }
    }
}
```
Please note that, for now, the minimum stability for your Laravel installation
should be set to "dev" in order to use this package. Once I have a stable
release, then you'll be able to turn off this requirement.

## Adding Modules
In order to register a new module with this package, an array must be returned
from a file named `register.php` in that module's directory. For example, to
register a module named "MyModule", paste the following file contents to
`modules/MyModule/register.php`:

```
#!php
<?php
return array(
    'enabled' => true,
);
```

## Configuring Modules
The above file provides the basic information necessary to register and enabled
a module with this package, but it's using a lot of default information. There
are several configuration options available to tune modules to your specific
needs:

```
#!php
<?php
return array(
    'enabled'   => true,
    'author'    => 'Steven Jimenez',
    'paths'     => array(
        // These paths define both the file structure and the namespace
        // structure for these parts of the project. These paths are
        // used to register a module's contents with core Laravel
        'controllers'   => 'Http/Controllers',  //< HTTP Controllers
        'lang'          => 'Lang',              //< Localization files
        'providers'     => 'Providers',         //< Service Providers
        'routes'        => 'Http/Routes',       //< HTTP Routes
        'views'         => 'Http/Views',        //< HTTP Views
    ),
    'providers' => array(
        // This array is used to enable/disable specific service providers.
    ),
);
```
