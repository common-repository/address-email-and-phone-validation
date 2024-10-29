<?php
/**
 * PCA Predict Settings Functions
 *
 * Functions for settings specific things.
 *
 * @author 		PCA Predict
 * @package 	pca-predict/admin
 * @version     1.0.3
 */
 
/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * pcapredict_create_default_settings()
 * Create the default settings by filtering the settings that are
 * registered.
 */
function pcapredict_create_default_settings( $settings ) {
	
	$settings[] = 'pcapredict_accountcode';
	return $settings;
	
}
add_filter( 'pcapredict_registered_settings', 'pcapredict_create_default_settings' );



/**
 * pcapredict_create_default_settings_output()
 * Adds the output to the settings page for the settings
 * register above with pcapredict_create_default_settings()
 */
function pcapredict_create_default_settings_output( $settings ) {
	
	$settings[ 'pcapredict_accountcode' ] = array(
		'label' => 'Account Code',
		'name' => 'pcapredict_accountcode',
		'type' => 'text',
		'description' => 'Add your account code here.'
	);
	return $settings;
	
}
add_filter( 'pcapredict_settings_output', 'pcapredict_create_default_settings_output' );

