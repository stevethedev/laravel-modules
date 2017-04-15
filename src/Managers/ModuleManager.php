<?php

namespace Orphan\Modules\Managers;

use Illuminate\Foundation\Application;

class ModuleManager
{
    const CONFIG_NAME = 'orphan.modules';

    protected $modules = null;

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
            '..' . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR .
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
        if (!is_null($this->modules)) {
            return $this->modules;
        }

        $this->modules = array();

        $path = $this->getConfig('registration.directory');

        if (!file_exists($path)) {
            $this->app->log->warning("No modules were loaded because the module path could not be found:\n".
                "\tPath: $path");
            return $this->modules;
        }

        $defaultSettings = $this->getConfig('default');
        $namespace = $this->getConfig('registration.namespace');

        // loop through all of the
        $handle = opendir($path);
        while (false !== ($moduleName = readdir($handle))) {
            if (!$registerPath = $this->getRegisterPath($path, $moduleName)) {
                continue;
            }

            // Check to see if we can load this module's file
            $thisModule = include($registerPath);
            if (!is_array($thisModule)) {
                $this->app->log->warning(
                    "A module could not be loaded because the registration file did not return an array:\n".
                    "\tModule: $moduleName\n\tRegistration File: $registerPath"
                );
                return false;
            }

            $overwrite = array(
                'module'    => "$moduleName",
                'enabled'   => isset($thisModule['enabled']) && $thisModule['enabled'],
                'path'      => dirname($registerPath),
                'namespace' => isset($thisModule['namespace']) //< allow users to overwrite the namespace
                    ? "{$thisModule['namespace']}"
                    : "{$namespace}\\{$moduleName}",
            );

            $this->modules["$moduleName"] = $this->compileModule(
                $defaultSettings,
                $thisModule,
                $overwrite
            );
        }

        closedir($handle);

        return $this->modules;
    }

    protected function compileModule($defaultSettings, $thisModule, $overwrite)
    {
        $module = array_replace_recursive(
            $defaultSettings,
            $thisModule,
            $overwrite
        );

        foreach ($module['paths'] as $key => $path) {
            $module['paths'][$key] = "{$module['path']}" . DIRECTORY_SEPARATOR . $path;
            if (!isset($module['namespaces'][$key])) {
                $module['namespaces'][$key] = str_replace('/', '\\', "{$module['namespace']}\\{$path}");
            }
        }

        return $module;
    }

    /**
     * Gets the registration path for the given path and module, or else returns
     * false if it is impossible to load a module from this directory.
     *
     * @param  string $path
     * @param  string $moduleName
     * @return string|bool
     */
    protected function getRegisterPath($path, $moduleName)
    {
        static $registerFile = null;
        if (is_null($registerFile)) {
            $registerFile = $this->getConfig('registration.file');
        }

        if ('.' === $moduleName || '..' === $moduleName) {
            return false;
        }

        $registerPath = implode(DIRECTORY_SEPARATOR, [$path, $moduleName, $registerFile]);

        // Check to see if a registration file exists
        if (!file_exists($registerPath)) {
            $this->app->log->warning(
                "A module could not be loaded because the registration file does not exist:\n".
                "\tModule: $moduleName\n\tRegistration File: $registerPath"
            );
            return false;
        }

        return $registerPath;
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
     * Retrieves the list of modules that are both registered AND enabled.
     *
     * @return array
     */
    public function getModules()
    {
        $modules = array();
        foreach ($this->modules as $module) {
            if (false !== $module['enabled']) {
                $modules[$module['module']] = $module;
            }
        }
        return $modules;
    }

    /**
     * Retrieves the list of all registered modules, regardless of whether
     * they are enabled.
     *
     * @return array
     */
    public function getAllModules()
    {
        return $this->modules;
    }
}
