<?php
namespace EasyMaintenanceTimer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Autoloader {
    public static function register() {
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    public static function autoload( $class ) {
        if ( strpos( $class, __NAMESPACE__ . '\\' ) !== 0 ) {
            return;
        }

        $relative_class = substr( $class, strlen( __NAMESPACE__ . '\\' ) );
        
        $parts = explode( '\\', $relative_class );
        $class_name = array_pop( $parts );
        
        $file_name = 'class-' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';
        
        $sub_path = '';
        if ( ! empty( $parts ) ) {
            $sub_path = strtolower( implode( '/', $parts ) ) . '/';
        }

        $file = EMMWT_PLUGIN_DIR . 'includes/' . $sub_path . $file_name;

        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }
}
