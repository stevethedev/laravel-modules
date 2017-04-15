<?php
namespace Orphan\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        foreach ($this->app->modules->getModules() as $module) {
            if (file_exists($module['paths']['views'])) {
                $this->registerViews($module);
            }
        }
    }

    /**
     * Parses module configuration for view path information and loads the path
     * data into the main application.
     *
     * @param   array   $module
     */
    protected function registerViews($module)
    {
        $this->app['config']['view.paths'] = array_merge(
            $this->app['config']['view.paths'],
            array($module['paths']['views'])
        );
    }
}
