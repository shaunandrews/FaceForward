<?php

/*
 * Define Constants
 */
define('FFORWARD_SHORTNAME', 'wptuts'); // used to prefix the individual setting field id see wptuts_options_page_fields()
define('FFORWARD_PAGE_BASENAME', 'wptuts-settings'); // the settings page slug


/*
 * Specify Hooks/Filters
 */
add_action( 'admin_menu', 'fforward_add_menu' );
add_action( 'admin_init', 'fforward_register_settings' );


/*
 * The Admin menu page
 */
function fforward_add_menu(){
	
	// Display Settings Page link under the "Appearance" Admin Menu
	// add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	$wptuts_settings_page = add_theme_page(__('FaceForward Options'), __('FaceForward Options','fforward_textdomain'), 'manage_options', FFORWARD_PAGE_BASENAME, 'fforward_settings_page_fn');			
}


 /**
 * Helper function for defining variables for the current page
 *
 * @return array
 */
function fforward_get_settings() {
	$output = array();
	$output['fforward_option_name'] 	= 'fforward_options';
	$output['fforward_page_title'] 		= __( 'FaceForward Options','fforward_textdomain');
	$output['fforward_page_sections'] 	= '';
	$output['fforward_page_fields'] 	= '';
	$output['fforward_contextual_help'] = '';
	return $output;
}

function fforward_register_settings() {
	// get the settings sections array
	$settings_output 	= fforward_get_settings();
	$fforward_option_name = $settings_output['fforward_option_name'];
	
	//setting
	//register_setting( $option_group, $option_name, $sanitize_callback );
	register_setting($fforward_option_name, $fforward_option_name, 'fforward_validate_options' );

    //sections  
    // add_settings_section( $id, $title, $callback, $page );  
    if( !empty( $settings_output['fforward_page_sections'])){  
        // call the "add_settings_section" for each!  
        foreach ( $settings_output['fforward_page_sections'] as $id => $title ) {  
            add_settings_section( $id, $title, 'fforward_section_fn', __FILE__);  
        }  
    }  
}

/* 
 * Section HTML, displayed before the first option 
 * @return echoes output 
 */  
function fforward_section_fn( $desc ) {
	echo "<p>" . __('Settings for this section','fforward_textdomain') . "</p>";
}

/* 
 * Validate input 
 *  
 * @return array 
 */  
function fforward_validate_options( $input ) {
    $valid_input = array();
	return $valid_input;
}


/** 
 * Define our settings sections 
 * 
 * array key=$id, array value=$title in: add_settings_section( $id, $title, $callback, $page ); 
 * @return array 
 */  
function fforward_options_page_sections() {
	$sections = array();
	// $sections[$id]       = __($title, 'fforward_textdomain');
	$sections['txt_section']    = __('Text Form Fields', 'fforward_textdomain');
	$sections['txtarea_section']    = __('Textarea Form Fields', 'fforward_textdomain');
	$sections['select_section']     = __('Select Form Fields', 'fforward_textdomain');
	$sections['checkbox_section']   = __('Checkbox Form Fields', 'fforward_textdomain');
	return $sections;  
} 


/*
 * Admin Settings Page HTML
 * 
 * @return echoes output
 */
function fforward_settings_page_fn() {
// get the settings sections array
	$settings_output = fforward_get_settings();
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"></div>
		<h2><?php echo $settings_output['fforward_page_title']; ?></h2>
		
		<form action="options.php" method="post">
            <?php   
            	// http://codex.wordpress.org/Function_Reference/settings_fields  
            	settings_fields( $settings_output['fforward_option_name'] );   
              
            	// http://codex.wordpress.org/Function_Reference/do_settings_sections  
            	do_settings_sections();   
            ?>  
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes','fforward_textdomain'); ?>" />
			</p>
		</form>
	</div><!-- wrap -->
<?php }
