<?php
namespace Orphan\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

use Orphan\Modules\Managers\ModuleManager;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of service
     */
    public function boot()
    {
        $this->app->singleton('modules', function ($app) {
            $modules = new ModuleManager($app);

            $modules->load();

            return $modules;
        });
    }

    /**
     *  Loads all of the modules from the base application directory.
     */
    public function register()
    {
        $this->publishes(
            $this->app->modules->getAssetRegistration()
        );
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

    /*
     |--------------------------------------------------------------------------
     | Register Module Routes
     |--------------------------------------------------------------------------
     |
     | Parses module configuration for route information and loads the data into
     | the main application.
     |
     */
    // protected function registerRoutes($module)
    // {
    //     $namespace = $module['namespaces']['controllers'];
    //     $files = glob($module['paths']['routes'] . DIRECTORY_SEPARATOR . '*.php');
    //     foreach ($files as $file) {
    //         switch (pathinfo($file)['basename']) {
    //             case 'web.php':
    //                 Route::middleware('web')
    //                     ->namespace($namespace)
    //                     ->group($file);
    //                 break;
    //             case 'api.php':
    //                 Route::prefix('api')
    //                      ->middleware('api')
    //                      ->namespace($namespace)
    //                      ->group($file);
    //                 break;
    //         }
    //     }
    // }

    /*
     |--------------------------------------------------------------------------
     | Register Module Views
     |--------------------------------------------------------------------------
     |
     | Parses module configuration for view path information and loads the path
     | data into the main application.
     |
     */
    // protected function registerViews($module)
    // {
    //     $this->app['config']['view.paths'] = array_merge(
    //         $this->app['config']['view.paths'],
    //         array($module['paths']['views'])
    //     );
    // }
}
