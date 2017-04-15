<?php

namespace Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoute()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /*
     |--------------------------------------------------------------------------
     | Register Modules
     |--------------------------------------------------------------------------
     |
     | Loads all of the modules from the base application directory.
     |
     */
    // public function loadModules()
    // {
    //
    //     // walk through all of the module configuration files
    //     $configFiles = glob(app_path() . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . $this->moduleConfigFile);
    //
    //     $modules = [];
    //
    //     array_walk($configFiles, function ($path) use (&$modules) {
    //         $config = require_once($path);
    //
    //         $folder = dirname($path);
    //         $moduleName = pathinfo($folder)['basename'];
    //
    //         $moduleData = [
    //             'module'     => $moduleName,
    //             'enabled'    => isset($config['enabled']) && $config['enabled'],
    //             'folder'     => $folder,
    //             'namespace'  => "\\{$this->getAppNamespace()}{$moduleName}",
    //             'paths'      => [],
    //             'namespaces' => [],
    //         ];
    //
    //         $paths = isset($config['paths']) ? $config['paths'] : [];
    //
    //         foreach ($this->defaultConfig as $key => $section) {
    //             $sectionFolder = (isset($paths[$key]) ? $paths[$key] : $section['path']);
    //             $moduleData['paths'][$key] = $folder . DIRECTORY_SEPARATOR . $sectionFolder;
    //             $moduleData['namespaces'][$key] = "{$moduleData['namespace']}\\{$sectionFolder}";
    //
    //             if ($moduleData['enabled'] && isset($section['register'])) {
    //                 $this->{$section['register']}($moduleData);
    //             }
    //         }
    //
    //         $modules[$moduleName] = $moduleData;
    //     });
    //
    //     $this->app['modules'] = $modules;
    // }

    // protected function registerProviders($module)
    // {
    //     foreach (glob($module['paths']['providers'].DIRECTORY_SEPARATOR.'*') as $file) {
    //         $this->app->registerDeferredProvider($module['namespaces']['providers'].'\\'.pathinfo($file)['filename']);
    //     }
    // }
}
