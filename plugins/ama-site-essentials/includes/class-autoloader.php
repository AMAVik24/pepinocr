<?php

/**
 * The file that defines the autoloader plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */

namespace AmaSiteEssentials\Includes;

class Autoloader {
    public function __construct() {
        spl_autoload_register(array($this, 'autoload'));
    }
 
    private function autoload($class) {
        $namespace = 'AmaSiteEssentials\\';
        $base_dir = plugin_dir_path(__DIR__);
 
        // Remove the namespace, since it's included in the class object
        $class = str_replace($namespace, '', $class);

        // Adjust the name of the class to a file name
        $class = strtolower($class);
        $class = str_replace( '_', '-', $class );
        $class = str_replace('\\', '/', $class);

        // Adds "class" to the begining of the class name to make it a complete file name
        if ( strrpos( $class, '/') > 0 ) {
            $class = substr_replace ( $class , 'class-' , strrpos( $class, '/') + 1 , 0 );
        } else {
            $class = substr_replace ( $class , 'class-' , 0, 0 );
        }
 
        // Check if the class file exists in the expected directories
            $file = $base_dir . $class . '.php';
            if (file_exists($file)) {
                require_once $file;
            return;
        }
    }
 
    public function run() {
        // Add any initialization logic here
    }
} 