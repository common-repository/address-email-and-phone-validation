<?php
/**
 * PCA Predict Admin Functions
 *
 * Functions for admin specific things.
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
 * function pcapredict_admin_styles()
 * outputs css for the admin pages
 */
function pcapredict_admin_styles() {
	?>
	<style>
		.pcapredict-postbox-inner { padding: 6px 10px 16px; }
		.pcapredict-postbox-title { border-bottom:1px solid #eee }
		.plugin-info { line-height: 2; }
	</style>
	<?php
}
add_action( 'admin_head', 'pcapredict_admin_styles' );



/**
 * pcapredict_add_admin_sub_menus()
 * adds the plugins sub menus under the wpbroadbean main admin menu item
 */
function pcapredict_add_admin_sub_menus() {

	add_submenu_page(
		'options-general.php', // parent_slug,
		'PCA Predict', // page_title,
		'PCA Predict', // menu_title,
		'manage_options', // capability,
		'pcapredict_settings', // menu slug,
		'pcapredict_settings_page_content' // callback function for the pages content
	);
		
}
add_action( 'admin_menu', 'pcapredict_add_admin_sub_menus' );



/**
 * pcapredict_register_settings()
 * Register the settings for this plugin. Just a username and a
 * password for authenticating.
 */
function pcapredict_register_default_settings() {

	/* build array of setttings to register */
	$pcapredict_registered_settings = apply_filters( 'pcapredict_registered_settings', array() );
	
	/* loop through registered settings array */
	foreach( $pcapredict_registered_settings as $pcapredict_registered_setting ) {
		
		/* register a setting for the username */
		register_setting( 'pcapredict_settings', $pcapredict_registered_setting );
		
	}
		
}
add_action( 'admin_init', 'pcapredict_register_default_settings' );



/**
 * pcapredict_settings_page_content()
 * Builds the content for the admin settings page.
 */
function pcapredict_settings_page_content() {

	?>
	
	<div class="wrap">
		
		<?php screen_icon( 'options-general' ); ?>
		<h2><?php echo apply_filters( 'pcapredict_admin_settings_page_title', 'PCA Predict Settings' ); ?></h2>
		
		<form method="post" action="options.php">
			
			<div id="poststuff">
				
				<div id="post-body" class="metabox-holder columns-2">
					
					<div class="right-column postbox-container" id="postbox-container-1">
						
						<div class="column-inner">
							
							<?php
							
								/* do before settings page action */
								do_action( 'pcapredict_settings_page_right_column' );
							
							?>
							
						</div><!-- // column-inner -->
						
					</div><!-- // postbox-contaniner -->
					
					<div class="left-column postbox-container" id="postbox-container-2">
						
						<div class="column-inner">
							
							<?php
								
								/* output settings field nonce action fields etc. */
								settings_fields( 'pcapredict_settings' );
		
								/* do before settings page action */
								do_action( 'pcapredict_before_settings_page' );
										
								/* setup an array of settings */
								$pcapredict_settings = apply_filters(
									'pcapredict_settings_output', 
									array()
								);
							
							?>
			
							<table class="form-table">
							
								<tbody>
								
									<?php
									
										/* loop through the settings array */
										foreach( $pcapredict_settings as $pcapredict_setting ) {
											
											?>
											
											<tr valign="top">
												<th scope="row">
													<label for="pcapredict_username"><?php echo $pcapredict_setting[ 'label' ]; ?></label>
												</th>
												<td>
													<?php
														switch( $pcapredict_setting[ 'type' ] ) {
														    										    
														    /* if the setting is a select input */
														    case 'select' :
														        
														        ?>
														    	<select name="<?php echo $pcapredict_setting[ 'name' ]; ?>" id="<?php echo $pcapredict_setting[ 'name' ]; ?>">
														    	
														    	<?php
				
														    	/* get the setting options */
														    	$pcapredict_setting_options = $pcapredict_setting[ 'options' ];
				
														        /* loop through each option */
														        foreach( $pcapredict_setting_options as $pcapredict_setting_option ) {
				
															        ?>
															        <option value="<?php echo esc_attr( $pcapredict_setting_option[ 'value' ] ); ?>" <?php selected( get_option( $pcapredict_setting[ 'name' ] ), $pcapredict_setting_option[ 'value' ] ); ?>><?php echo $pcapredict_setting_option[ 'name' ]; ?></option>
																	<?php
				
														        }
				
														        ?>
														    	</select>
														        <?php
														        if( $pcapredict_setting[ 'description' ] != '' ) {
																	?>
																	<p class="description"><?php echo $pcapredict_setting[ 'description' ]; ?></p>
																	<?php
																}
														        
														        /* break out of the switch statement */
														        break;
														    
														    /* if the setting is a wysiwyg input */
														    case 'wysiwyg' :
														    	
														    	/* set some settings args for the editor */
														    	$pcapredict_editor_settings = array(
														    		'textarea_rows' => $pcapredict_setting[ 'textarea_rows' ],
														    		'media_buttons' => $pcapredict_setting[ 'media_buttons' ],
														    	);
				
														    	/* get current content for the wysiwyg */
														    	$pcapredict_wysiwyg_content = get_option( $pcapredict_setting[ 'name' ] );
																
														    	/* display the wysiwyg editor */
														    	wp_editor( $pcapredict_wysiwyg_content, $pcapredict_setting[ 'name' ], $pcapredict_editor_settings );
														    	
														    	/* break out of the switch statement */
														    	break;
														        
														    default :
														       
														       ?>
																<input type="text" name="<?php echo $pcapredict_setting[ 'name' ]; ?>" id="<?php echo $pcapredict_setting[ 'name' ]; ?>" class="regular-text" value="<?php echo get_option( $pcapredict_setting[ 'name' ] ) ?>" />
																<?php
																if( $pcapredict_setting[ 'description' ] != '' ) {
																	?>
																	<p class="description"><?php echo $pcapredict_setting[ 'description' ]; ?></p>
																	<?php
																}
																
														} // end switch statement
														
													?>
													
												</td>
				
											</tr>
											
											<?php
										}
									
									?>
									
								</tbody>
								
							</table>
							
							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
							</p>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	
	<?php
	
	/* do after settings page action */
	do_action( 'pcapredict_after_settings_page' );
	
}



/**
 * function pcapredict_settings_page_cta()
 * adds intro text on the settings page
 */
function pcapredict_settings_page_cta() {
	
	?>
	
	<div class="pcapredict-cta">
		
		<p>Create an account at <a href="https://www.pcapredict.com/register/">PCApredict.com</a><p>
		<!-- <p>Add your PCA Predict Account Code below:</p> -->
		
	</div>
	
	<?php
	
}
add_action( 'pcapredict_before_settings_page', 'pcapredict_settings_page_cta', 10 );



/**
 *
 */
function pcapredict_settings_page_ctas() {
	
	/* get this plugins data - such as version, author etc. */
	$data = get_plugin_data(
		PCAPREDICT_LOCATION . '/pca-predict.php',
		false // no markup in return
	);
	?>
	
	<div class="postbox">
		
		<h3 class="pcapredict-postbox-title"><?php echo esc_html( $data[ 'Name' ] ); ?></h3>
		
		<div class="pcapredict-postbox-inner">
			<p class="plugin-info">
				Version: <?php echo esc_html( $data[ 'Version' ] ); ?><br />
				Written by: <a href="<?php echo esc_url( $data[ 'AuthorURI' ] ); ?>"><?php echo esc_html( $data[ 'AuthorName' ] ); ?></a><br />
				Website: <a href="http://www.pcapredict.com/woocommerce">PCA Predict</a>
			</p>
			<p>
				If you find this plugin useful then please <a href="https://wordpress.org/support/view/plugin-reviews/address-email-and-phone-validation">rate it on the plugin repository</a>.
			</p>
		</div>
		
	</div>
	
	<?php		
}
add_action( 'pcapredict_settings_page_right_column', 'pcapredict_settings_page_ctas' );

