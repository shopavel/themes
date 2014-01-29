<?php namespace Shopavel\Themes;

use Illuminate\View\FileViewFinder as IlluminateFileViewFinder;

class FileViewFinder extends IlluminateFileViewFinder {

    protected $namespace;

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function find($name)
    {
        if (strpos($name, '::') === false) {
            $name = $this->namespace . '::' . $name;
        }

        return parent::find($name);
    }

}