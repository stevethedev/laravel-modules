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
            if (file_exists($module['path'])) {
                $this->registerWebRoutes($module['paths']['routes'], $module['namespaces']['routes']);
                $this->registerApiRoutes($module['paths']['routes'], $module['namespaces']['routes']);
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
