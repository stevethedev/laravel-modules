<?php
namespace Orphan\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

use Orphan\Modules\Managers\ModuleManager;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     *  Loads all of the modules from the base application directory.
     */
    public function register()
    {
        $this->app->singleton('modules', function ($app) {
            $modules = new ModuleManager($app);

            $modules->load();

            return $modules;
        });

        $this->publishes(
            $this->app->modules->getAssetRegistration()
        );

        $this->registerProviders();
    }

    public function registerProviders()
    {
        $this->app->registerDeferredProvider(RouteServiceProvider::class);
    }

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
