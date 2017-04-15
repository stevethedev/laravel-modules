<?php
namespace Orphan\Modules\Providers;

use Illuminate\Support\ServiceProvider;

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

    /**
     * Registers the service providers from this package with Laravel.
     */
    protected function registerProviders()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
