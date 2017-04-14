<?php

namespace Orphan\Modules\Managers;

use Illuminate\Foundation\Application;

class ModuleManager
{
    const CONFIG_NAME = 'orphan.modules';

    /**
     * Create a new ModuleManager object
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Loads the initial configuration.
     */
    public function load()
    {
        $this->loadConfig();
        $this->loadModuleConfig();
    }

    /**
     * Retrieves the configuration file array for registration
     * with the Laravel application.
     */
    public function getAssetRegistration()
    {
        return array(
            $this->getBaseConfigPath() => config_path(self::CONFIG_NAME . '.php')
        );
    }

    /**
     * Retrieves the base configuration path for this package.
     *
     * @return string
     */
    public function getBaseConfigPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR .
            'config' . DIRECTORY_SEPARATOR .
            self::CONFIG_NAME . '.php';
    }

    /**
     * Load Configuration data from the main configuration file
     *
     * @return array
     */
    private function loadConfig()
    {
        $config = $this->app['config'];
        if (null === $config->get(self::CONFIG_NAME)) {
            $config->set(self::CONFIG_NAME, require $this->getBaseConfigPath());
        }
        return $config->get(self::CONFIG_NAME);
    }

    /**
     * Retrieves a given key from the configuration file.
     *
     * @param  string   $key    period-delimited string for retrieving keys
     * @return mixed
     */
    protected function getConfig($key)
    {
        static $memo = array();
        if (isset($memo[$key])) {
            return $memo[$key];
        }

        $keyPath = explode('.', $key);
        $value = $this->loadConfig();

        foreach ($keyPath as $v) {
            if (!isset($value[$v])) {
                return null;
            }
            $value = $value[$v];
        }

        $memo[$key] = $value;
        return $value;
    }

    /**
     * Retrieves the module configuration data from the config files. This uses
     * procedural code because reading from the file system can be slow and
     * this function gets called often. Therefore, I opted for slightly
     * faster code, even if it meant breaking from OOP principles.
     *
     * @return array    Registration data per-module.
     */
    private function loadModuleConfig()
    {
        if (isset($this->app['modules'])) {
            return $this->app['modules'];
        }

        $this->moduleConfig = array();

        $path = app_path() . DIRECTORY_SEPARATOR . $this->getConfig('registration.directory');

        if (!file_exists($path)) {
            $this->app->log->warning("No modules were loaded because the module path could not be found:\n\tPath: $path");
            return $this->moduleConfig;
        }

        $defaultSettings = $this->getConfig('default');
        $registerFile = $this->getConfig('registration.file');

        // loop through all of the
        $handle = opendir($path);
        while (false !== ($moduleName = readdir($handle))) {
            $registerPath = implode(DIRECTORY_SEPARATOR, [$path, $moduleName, $registerFile]);

            // Check to see if a registration file exists
            if (!file_exists($registerPath)) {
                $this->app->log->warning(
                    "A module could not be loaded because the registration file does not exist:\n".
                    "\tModule: $moduleName\n\tRegistration File: $registerPath"
                );
                continue;
            }

            // Check to see if we can load this module's file
            if (!$thisModule = include($registerPath)) {
                $this->app->log->warning(
                    "A module could not be loaded because the registration file could not be executed:\n".
                    "\tModule: $moduleName\n\tRegistration File: $registerPath"
                );
                continue;
            }

            $overwrite = array(
                'module'    => $moduleName,
                'enabled'   => isset($thisModule['enabled']) && $thisModule['enabled'],
                'folder'    => dirname($registerPath),
                'namespace' => "\\{$this->getAppNamespace()}{$moduleName}",
            );

            // cast to string to give us some extra flexibility
            $this->moduleConfig["$moduleName"] = array_replace_recursive($defaultSettings, $thisModule, $overwrite);
        }

        closedir($path);

        return $this->app['modules'];
    }

    /**
     * Retrieves module configuration based on the provided inputs. If no module
     * is identified, then configuration for all registered modules will be
     * returned. Otherwise, configuration for the identified module is
     * returned. If no config-path is defined, then global config
     * will be returned. Otherwise, the identified path will
     * be returned. The default return value is NULL.
     *
     * @param  string|null $module The name of the module to load config from
     * @param  string|null $config The config path to load
     * @return mixed
     */
    public function getModuleConfig($module = null, $config = null)
    {
        $moduleConfig = $this->loadModuleConfig();
        if (null === $module) {
            return $moduleConfig;
        }

        if (!isset($moduleConfig["$module"])) {
            return null;
        }

        $moduleConfig = $moduleConfig["$module"];

        if (null !== $config) {
            $config = explode('.', $config);
            foreach ($config as $part) {
                if (!isset($moduleConfig[$part])) {
                    return null;
                }
                $moduleConfig = $moduleConfig[$part];
            }
        }

        return $moduleConfig;
    }

    /**
     * Retrieves the application namespace from the composer config file, or
     * else throws an error.
     *
     * @return string|null
     */
    private function getAppNamespace()
    {
        // memoize for increased performance
        static $memo = null;
        if ($memo) {
            return $memo;
        }

        // read from teh composer file
        $composer = json_decode(file_get_contents(base_path().'/composer.json'), true);
        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            // iterate the autoload paths for the main application directory
            foreach ((array) $path as $pathChoice) {
                // if this path points to the application path, then it must be our path
                if (realpath(app_path()) == realpath(base_path().'/'.$pathChoice)) {
                    $memo = $namespace;
                    return $namespace;
                }
            }
        }

        // otherwise, no path was defined and we should throw an error
        throw new RuntimeException("Unable to detect application namespace.");
    }
}
