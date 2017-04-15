<?php

namespace Orphan\Modules\Providers;

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
        foreach ($this->app->modules->getModules() as $module) {
            $namespace = "{$module['namespace']}\\{$module['path']['controllers']}";
            $directory = "{$module['paths']['routes']}";
            if (file_exists($directory)) {
                $this->registerWebRoutes($directory, $namespace);
                $this->registerApiRoutes($directory, $namespace);
            }
        }
    }

    /**
     * Registers module web-routes.
     *
     * @param  string $routeDirectory
     * @param  string $namespace
     */
    protected function registerWebRoutes($routeDirectory, $namespace)
    {
        $routeDirectory .= DIRECTORY_SEPARATOR . 'web.php';
        if (file_exists($routeDirectory)) {
            Route::middleware('web')
                ->namespace($namespace)
                ->group($routeDirectory);
        }
    }

    /**
     * Registers module api-routes.
     *
     * @param  string $routeDirectory
     * @param  string $namespace
     */
    protected function registerApiRoutes($routeDirectory, $namespace)
    {
        $routeFile .= DIRECTORY_SEPARATOR . 'api.php';
        if (file_exists($routeDirectory)) {
            Route::middleware('api')
                ->middleware('api')
                ->namespace($namespace)
                ->group($routeDirectory);
        }
    }
}
