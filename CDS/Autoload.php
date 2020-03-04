<?php

declare(strict_types=1);

namespace CDS;

class Autoload {
    
    protected static $instance;
    
    public static function getInstance() : self {
        if ( static::$instance == null ) {
            static::$instance = new self();
        }
        return static::$instance;
    }
    
    private function __construct()
    {
        $this->register();
    }
    
    public function register()
    {
        spl_autoload_register([$this, 'loadClass'], true, true);
        
    }
    
    public function loadClass(string $class) : void
    {
        $path = $this->getMappings($class);
        $this->loadFile($path[0], $path[1]);
        
    }
    
    protected function loadFile(array $paths, string $class) : bool
    {
        foreach ($paths as $path) {
            $fullPath = str_replace('\\', DIRECTORY_SEPARATOR, $path . '\\' . $class) . '.php';
            if (file_exists(PROJECT_ROOT . $fullPath)) {
                /** @noinspection PhpIncludeInspection */
                require_once(PROJECT_ROOT . $fullPath);
                
                return true;
            }
            
        }
        
        return false;
    }
    
    protected function getMappings(string $class) : array
    {
        $namespaceSplit = explode('\\', $class);
        $class = array_pop($namespaceSplit);
        $path = implode('\\', $namespaceSplit);
        
        return [[$path], $class];
    }
}