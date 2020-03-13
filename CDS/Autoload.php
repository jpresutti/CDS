<?php

declare(strict_types=1);

namespace CDS;

/**
 * Class Autoload
 * Note: This class is derived from a similar autoloader I wrote in my own framework
 * @package CDS
 */
class Autoload {
    
    protected static $instance;

    /**
     * Get static instance of autoloader
     * @return static
     */
    public static function getInstance() : self {
        if ( static::$instance == null ) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * Autoload constructor. - Private to prevent manual instantiation
     */
    private function __construct()
    {
        $this->register();
    }

    /**
     * Registers autoload function
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass'], true, true);
        
    }

    /**
     * Autoload a class by name
     * @param string $class
     */
     public function loadClass(string $class) : void
    {
        $path = $this->getMappings($class);
        $this->loadFile($path[0], $path[1]);
        
    }

    /**
     * Get path mapping for a class
     * @param string $class
     * @return array
     */
    protected function getMappings(string $class) : array
    {
        // Explode on \ to get an array of namespaces (aka directory roots)
        $namespaceSplit = explode('\\', $class);

        // Get the actual class name by popping off the last element
        $class = array_pop($namespaceSplit);

        // Get the path
        $path = implode('\\', $namespaceSplit);

        return [[$path], $class];
    }

    /**
     * Load file from path
     * @param array $paths
     * @param string $class
     * @return bool
     */
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

}