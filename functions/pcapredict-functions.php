<?php
/**
 * PCA Predict Functions
 *
 * Functions
 *
 * @author 		PCA Predict
 * @package 	pca-predict
 * @version     1.0.3
 */
 
 
/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * function pcapredict_get_field()
 * gets the value of a meta box field for a pcapredict post
 * @param (string) $field is the name of the field to return
 * @param (int) $post_id is the id of the post for which to look for the field in - defaults to current loop post
 * @param (string) $prefix is the prefix to use for the custom field key. Defaults to _pcapredict_
 * return (string) $field the value of the field
 */
function pcapredict_get_field( $field, $post_id = '', $prefix = '_pcapredict_' ) {
	
	global $post;
	
	/* if no post id is provided use the current post id in the loop */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* if we have no field name passed go no further */
	if( empty( $field ) )
		return false;
	
	/* build the meta key to return the value for */
	$key = $prefix . $field;
	
	/* gete the post meta value for this field name of meta key */
	$field = get_post_meta( $post_id, $key, true );
	
	return apply_filters( 'pcapredict_field_value', $field );
	
}



/**
 * function pcapredict_get_setting()
 *
 * gets a named plugin settings returning its value
 * @param	mixed	key name to retrieve - this is the key of the stored option
 * @return	mixed	the value of the key
 */
function pcapredict_get_setting( $name = '' ) {
	
	/* if no name is passed */
	if( empty( $name ) ) {
		return false;
	}
	
	/* get the option */
	$setting = get_option( 'pcapredict_' . $name );
	
	/* check we have a value returned */
	if( empty( $setting ) ) {
		return false;
	}
	
	return apply_filters( 'pcapredict_get_setting', $setting );
}



/**
 * pcapredict_on_activation()
 * On plugin activation makes current user a wpbasis user and
 * sets an option to redirect the user to another page.
 */
function pcapredict_on_activation() {
	
	/* set option to initialise the redirect */
	add_option( 'pcapredict_activation_redirect', true );
	
}
register_activation_hook( __FILE__, 'pcapredict_on_activation' );



/**
 * pcapredict_activation_redirect()
 * Redirects user to the settings page for wp basis on plugin
 * activation.
 */
function pcapredict_activation_redirect() {
	
	/* check whether we should redirect the user or not based on the option set on activation */
	if( true == get_option( 'pcapredict_activation_redirect' ) ) {
		
		/* delete the redirect option */
		delete_option( 'pcapredict_activation_redirect' );
		
		/* redirect the user to the wp basis settings page */
		wp_redirect( admin_url( 'admin.php?page=pcapredict_settings' ) );
		exit;
		
	}
	
}
add_action( 'admin_init', 'pcapredict_activation_redirect' );



/**
 * pcapredict_hook_javascript()
 * 
 * Adds the PCA tag to the head of every page
 */
function pcapredict_hook_javascript() {

	$accCode = pcapredict_get_setting( 'accountcode' );
	if ( $accCode ) { ?>
	
        <script>
        (function (a, c, b, e) {
        a[b] = a[b] || {}; a[b].initial = { accountCode: "<?php echo $accCode; ?>", host: "<?php echo $accCode; ?>.pcapredict.com" };
        a[b].on = a[b].on || function () { (a[b].onq = a[b].onq || []).push(arguments) }; var d = c.createElement("script");
        d.async = !0; d.src = e; c = c.getElementsByTagName("script")[0]; c.parentNode.insertBefore(d, c)
        })(window, document, "pca", "//<?php echo $accCode; ?>.pcapredict.com/js/sensor.js");
		(function() {
			pca.on('ready', function () {pca.sourceString = "WordPressPlugin-v<?php echo pcapredict_plugin_get_version(); ?>";});
			pca.on('data', function(source, key, address, variations) {var provNameElId = "";if (pca.platform.productList.hasOwnProperty(key) && pca.platform.productList[key].hasOwnProperty("PLATFORM_CAPTUREPLUS")) {for (var b = 0; b < pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings.length; b++) {for (var f = 0; f < pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings[b].fields.length; f++) {var el = document.getElementById(pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings[b].fields[f].element);if (el) {if (pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings[b].fields[f].field === "{ProvinceName}" || pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings[b].fields[f].field === "{ProvinceCode}") {provNameElId = el.id;}}}}if (provNameElId != "") {var el = document.getElementById(provNameElId);if (el) {for (var j = 0; j < el.options.length; j++) {if (el.options[j].text === address.ProvinceName) {el.selectedIndex = j;if (jQuery && Select2) {jQuery('select').trigger('change.select2');}break;}}pca.fire(el, 'change');}}}});
			if(jQuery){jQuery(document).bind('gform_post_render', function(){window.setTimeout(function(){pca.load();}, 200);});};
		})();
		
        </script>

        
	<?php }
}
add_action( 'wp_head', 'pcapredict_hook_javascript' );
add_action( 'admin_head', 'pcapredict_hook_javascript' );




function pcapredict_allow_setup() {
	
	$qval = isset( $_REQUEST[ 'pcasetup_ts' ] );
	if ( $qval ) {
		set_transient( 'allow_pca_setup', $_REQUEST[ 'pcasetup_ts' ], 20 * MINUTE_IN_SECONDS );		
	}	
	if ( get_transient( 'allow_pca_setup' ) ) {
		remove_action( 'template_redirect', 'wc_send_frame_options_header' );
		remove_action( 'admin_init', 'send_frame_options_header' );
	}
}
add_action( 'init', 'pcapredict_allow_setup', 20, 0 );





