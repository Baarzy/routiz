<?php

use \Routiz\Inc\Utils\Register;
use \Routiz\Inc\Utils\Helper;
use \Routiz\Inc\Utils\Icon;
use \Routiz\Inc\Utils\Notify;
use \Routiz\Inc\Utils\Notifications;
use \Routiz\Inc\Utils\Component_Manager;

if ( ! defined('ABSPATH') ) {
	exit;
}

/*
 * human readable dump
 *
 */
if( ! function_exists('dd') ) {
    function dd( $what = '' ) {
        print '<pre class="rz-dump">';
        print_r( $what );
        print '</pre>';
    }
}

/*
 * autoloader
 *
 */
spl_autoload_register( function( $class ) {

    if ( strpos( $class, 'Routiz' ) === false ) { return; }

    $file_parts = explode( '\\', $class );

    $namespace = '';
    for( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {

        $current = strtolower( $file_parts[ $i ] );
        $current = str_ireplace( '_', '-', $current );

        if( count( $file_parts ) - 1 === $i ) {
            $file_name = "{$current}.php";
        }else{
            $namespace = '/' . $current . $namespace;
        }
    }

    $filepath  = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
    $filepath .= $file_name;

    if( file_exists( $filepath ) ) {
        include_once( $filepath );
    }

});

/*
 * register utils
 *
 */
if( ! function_exists('routiz') ) {
    function routiz() {
        return Register::instance();
    }
}

if( ! function_exists('Rz') ) {
    function Rz() {
    	return routiz()->helper();
    }
    routiz()->register( 'helper', Helper::instance() );
    routiz()->register( 'icon', Icon::instance() );
    routiz()->register( 'notify', Notify::instance() );
}

/*
 * initializer
 *
 */
Routiz\Inc\Init::instance();
