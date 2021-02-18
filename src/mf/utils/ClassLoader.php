<?php
namespace mf\utils;
class ClassLoader extends AbstractClassLoader
{
    public function loadClass(string $classname)
    {
        $filename = $this->getFilename($classname);
        $classname = $this->makePath($filename);
        if(file_exists($classname))
        {
            require_once $classname;
        }
    }
    protected function makePath(string $filename) : string
    {
        $filename = $this->prefix.DIRECTORY_SEPARATOR.$filename;
        return $filename;
    }
    protected function getFilename(string $classname) : string
    {
        $classname = strtr($classname,"\\",DIRECTORY_SEPARATOR).".php";
        return $classname;
    }
}