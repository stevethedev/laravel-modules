<?php

namespace Orphan\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class DistributedServiceProvider extends ServiceProvider
{
    /**
     * Register module service providers
     */
    public function register()
    {
        $this->registerProviders();
    }

    /**
     * Iterate the providers defined in the module configuration
     */
    protected function registerProviders()
    {
        foreach ($this->app->modules->getModules() as $module) {
            foreach ($module['providers'] as $provider) {
                $this->app->register($provider);
            }
        }
    }
}
