<?php

declare(strict_types=1);

namespace CDS;

class Config {
    private static $instance;
    
    private array $configData;

    /**
     * Get an instance of the config class
     * @return Config
     * @throws \Exception
     */
    public static function getInstance() : self {
        if ( static::$instance == null ) {
            static::$instance = new self();
        }
        return static::$instance;
    }
    
    /**
     * Config constructor.
     * @throws \Exception
     */
    private function __construct()
    {
        $this->configData = parse_ini_file(PROJECT_ROOT . 'config.ini', true);
        if ( $this->configData === false ) {
            throw new \Exception('Please configure application. View README.md for help.');
        }
    }
    
    /**
     * Get a config setting
     * @param string $setting
     * @param bool $default
     * @return array|bool|mixed|string
     */
    public function getSetting(string $setting, $default = false) {
        $path = explode('.',$setting);
        $setting = $this->configData;
        
        foreach($path as $key) {
            if ( !isset($setting[$key]) ) {
                return $default;
            }
            $setting = $setting[$key];
        }
        return $setting;
    }
}