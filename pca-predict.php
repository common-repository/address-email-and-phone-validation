<?php
/*
Plugin Name: PCA Predict
Plugin URI: http://pcapredict.com/woocommerce
Description: Integrates the PCA Predict tag into WordPress!
Version: 1.0.3
Author: PCA Predict
Author URI: http://pcapredict.com
License: GPLv2 or later
*/

/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * initialises plugin
 * @return [type] [description]
 */
function pcapredict_init() {

	/* define variable for path to this plugin file. */
	define( 'PCAPREDICT_LOCATION', dirname( __FILE__ ) );

	/* load required files & functions */
	require_once( dirname( __FILE__ ) . '/functions/pcapredict-functions.php' );
    
    if ( is_admin() ) {
        /* load admin files & functions */
        require_once( dirname( __FILE__ ) . '/functions/admin/admin.php' );
        require_once( dirname( __FILE__ ) . '/functions/admin/default-settings.php' );
        require_once( dirname( __FILE__ ) . '/functions/admin/woocommerce/woocommerce.php' );
    }
        
}
add_action( 'init', 'pcapredict_init' );


/**
 * Returns current plugin version. (https://code.garyjones.co.uk/get-wordpress-plugin-version)
 * 
 * @return string Plugin version
 */
function pcapredict_plugin_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}
