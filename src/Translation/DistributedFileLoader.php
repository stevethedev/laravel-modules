<?php

namespace Infrastructure\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

class DistributedFileLoader extends FileLoader
{
    /*
     |--------------------------------------------------------------------------
     | Translation Paths
     |--------------------------------------------------------------------------
     |
     | The list of paths to localization directories (each inside of a module).
     |
     */
    protected $paths = array();

    /**
     * Create a new file loader instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $path
     * @return void
     */
    public function __construct(Filesystem $files, array $paths = array())
    {
        $this->files = $files;
        $this->paths = $paths;
    }

    /**
     * Load a locale from a given path.
     *
     * @param  string  $path
     * @param  string  $locale
     * @param  string  $group
     * @return array
     */
    protected function loadPath($path, $locale, $group)
    {
        $newPath = array();
        foreach ($this->paths as $path) {
            $newPath = array_merge($newPath, parent::loadPath($path, $locale, $group));
        }
        return $newPath;
    }
}
