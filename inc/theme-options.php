<?php
/**
 * FaceForward Theme Options
 *
 * @package WordPress
 * @subpackage FaceFoward
 * @since FaceForward 1.0
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since FaceForward 1.0
 *
 */
function fforward_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'fforward-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
	wp_enqueue_script( 'fforward-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array( 'farbtastic' ), '2011-06-10' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'fforward_admin_enqueue_scripts' );

/**
 * Register the form setting for our fforward_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, fforward_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * @since FaceForward 1.0
 */
function fforward_theme_options_init() {

	register_setting(
		'fforward_options',       // Options group, see settings_fields() call in fforward_theme_options_render_page()
		'fforward_theme_options', // Database option, see fforward_get_theme_options()
		'fforward_theme_options_validate' // The sanitization callback, see fforward_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see fforward_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field(
		'color_scheme',  // Unique identifier for the field for this section
		__( 'Color Scheme', 'fforward' ), // Setting field label
		'fforward_settings_field_color_scheme', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see fforward_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field( 'link_color', __( 'Link Color',     'fforward' ), 'fforward_settings_field_link_color', 'theme_options', 'general' );
}
add_action( 'admin_init', 'fforward_theme_options_init' );

/**
 * Change the capability required to save the 'fforward_options' options group.
 *
 * @see fforward_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see fforward_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function fforward_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_fforward_options', 'fforward_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since FaceForward 1.0
 */
function fforward_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'fforward' ),   // Name of page
		__( 'Theme Options', 'fforward' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'fforward_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	add_action( "load-$theme_page", 'fforward_theme_options_help' );
}
add_action( 'admin_menu', 'fforward_theme_options_add_page' );

function fforward_theme_options_help() {

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, FaceForward, provides the following Theme Options:', 'fforward' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Color Scheme</strong>: You can choose a color palette of "Light" (light background with dark text) or "Dark" (dark background with light text) for your site.', 'fforward' ) . '</li>' .
				'<li>' . __( '<strong>Link Color</strong>: You can choose the color used for text links on your site. You can enter the HTML color or hex code, or you can choose visually by clicking the "Select a Color" button to pick from a color wheel.', 'fforward' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'fforward' ) . '</p>';

	$sidebar = '<p><strong>' . __( 'For more information:', 'fforward' ) . '</strong></p>' .
		'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'fforward' ) . '</p>' .
		'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'fforward' ) . '</p>';

	$screen = get_current_screen();

	if ( method_exists( $screen, 'add_help_tab' ) ) {
		// WordPress 3.3
		$screen->add_help_tab( array(
			'title' => __( 'Overview', 'fforward' ),
			'id' => 'theme-options-help',
			'content' => $help,
			)
		);

		$screen->set_help_sidebar( $sidebar );
	} else {
		// WordPress 3.2
		add_contextual_help( $screen, $help . $sidebar );
	}
}

/**
 * Returns an array of color schemes registered for FaceForward.
 *
 * @since FaceForward 1.0
 */
function fforward_color_schemes() {
	$color_scheme_options = array(
		'light' => array(
			'value' => 'light',
			'label' => __( 'Light', 'fforward' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/light.png',
			'default_link_color' => '#1e8cbe',
		),
		'dark' => array(
			'value' => 'dark',
			'label' => __( 'Dark', 'fforward' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/dark.png',
			'default_link_color' => '#2297dd',
		),
	);

	return apply_filters( 'fforward_color_schemes', $color_scheme_options );
}

/**
 * Returns the default options for FaceForward.
 *
 * @since FaceForward 1.0
 */
function fforward_get_default_theme_options() {
	$default_theme_options = array(
		'color_scheme' => 'light',
		'link_color'   => fforward_get_default_link_color( 'light' ),
	);

	return apply_filters( 'fforward_default_theme_options', $default_theme_options );
}

/**
 * Returns the default link color for FaceForward, based on color scheme.
 *
 * @since FaceForward 1.0
 *
 * @param $string $color_scheme Color scheme. Defaults to the active color scheme.
 * @return $string Color.
*/
function fforward_get_default_link_color( $color_scheme = null ) {
	if ( null === $color_scheme ) {
		$options = fforward_get_theme_options();
		$color_scheme = $options['color_scheme'];
	}

	$color_schemes = fforward_color_schemes();
	if ( ! isset( $color_schemes[ $color_scheme ] ) )
		return false;

	return $color_schemes[ $color_scheme ]['default_link_color'];
}

/**
 * Returns the options array for FaceForward.
 *
 * @since FaceForward 1.0
 */
function fforward_get_theme_options() {
	return get_option( 'fforward_theme_options', fforward_get_default_theme_options() );
}

/**
 * Renders the Color Scheme setting field.
 *
 * @since FaceForward 1.0
 */
function fforward_settings_field_color_scheme() {
	$options = fforward_get_theme_options();

	foreach ( fforward_color_schemes() as $scheme ) {
	?>
	<div class="layout image-radio-option color-scheme">
	<label class="description">
		<input type="radio" name="fforward_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
		<input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['default_link_color'] ); ?>" />
		<span>
			<img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="136" height="122" alt="" />
			<?php echo $scheme['label']; ?>
		</span>
	</label>
	</div>
	<?php
	}
}

/**
 * Renders the Link Color setting field.
 *
 * @since FaceForward 1.0
 */
function fforward_settings_field_link_color() {
	$options = fforward_get_theme_options();
	?>
	<input type="text" name="fforward_theme_options[link_color]" id="link-color" value="<?php echo esc_attr( $options['link_color'] ); ?>" />
	<a href="#" class="pickcolor hide-if-no-js" id="link-color-example"></a>
	<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e( 'Select a Color', 'fforward' ); ?>" />
	<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
	<br />
	<span><?php printf( __( 'Default color: %s', 'fforward' ), '<span id="default-color">' . fforward_get_default_link_color( $options['color_scheme'] ) . '</span>' ); ?></span>
	<?php
}

/**
 * Returns the options array for FaceForward.
 *
 * @since FaceForward 1.0
 */
function fforward_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
		<h2><?php printf( __( '%s Theme Options', 'fforward' ), $theme_name ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'fforward_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see fforward_theme_options_init()
 * @todo set up Reset Options action
 *
 * @since FaceForward 1.0
 */
function fforward_theme_options_validate( $input ) {
	$output = $defaults = fforward_get_default_theme_options();

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], fforward_color_schemes() ) )
		$output['color_scheme'] = $input['color_scheme'];

	// Our defaults for the link color may have changed, based on the color scheme.
	$output['link_color'] = $defaults['link_color'] = fforward_get_default_link_color( $output['color_scheme'] );

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
		$output['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	return apply_filters( 'fforward_theme_options_validate', $output, $input, $defaults );
}

/**
 * Enqueue the styles for the current color scheme.
 *
 * @since FaceForward 1.0
 */
function fforward_enqueue_color_scheme() {
	$options = fforward_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme )
		wp_enqueue_style( 'dark', get_template_directory_uri() . '/colors/dark.css', array(), null );
	if ( 'light' == $color_scheme )
		wp_enqueue_style( 'light', get_template_directory_uri() . '/colors/light.css', array(), null );

	do_action( 'fforward_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'fforward_enqueue_color_scheme' );

/**
 * Add a style block to the theme for the current link color.
 *
 * This function is attached to the wp_head action hook.
 *
 * @since FaceForward 1.0
 */
function fforward_print_link_color_style() {
	$options = fforward_get_theme_options();
	$link_color = $options['link_color'];

	$default_options = fforward_get_default_theme_options();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
	<style>
		/* Link color */
		a,
		a:focus,
		a:hover,
		a:active {
			color: <?php echo $link_color; ?>;
		}
	</style>
<?php
}
add_action( 'wp_head', 'fforward_print_link_color_style' );

/**
 * Implements FaceForward theme options into Theme Customizer
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since FaceForward 1.0
 */
function fforward_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$options  = fforward_get_theme_options();
	$defaults = fforward_get_default_theme_options();

	$wp_customize->add_setting( 'fforward_theme_options[color_scheme]', array(
		'default'    => $defaults['color_scheme'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );

	$schemes = fforward_color_schemes();
	$choices = array();
	foreach ( $schemes as $scheme ) {
		$choices[ $scheme['value'] ] = $scheme['label'];
	}

	$wp_customize->add_control( 'fforward_color_scheme', array(
		'label'    => __( 'Color Scheme', 'fforward' ),
		'section'  => 'colors',
		'settings' => 'fforward_theme_options[color_scheme]',
		'type'     => 'radio',
		'choices'  => $choices,
		'priority' => 5,
	) );

	// Link Color (added to Color Scheme section in Theme Customizer)
	$wp_customize->add_setting( 'fforward_theme_options[link_color]', array(
		'default'           => fforward_get_default_link_color( $options['color_scheme'] ),
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
		'capability'        => 'edit_theme_options',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'    => __( 'Link Color', 'fforward' ),
		'section'  => 'colors',
		'settings' => 'fforward_theme_options[link_color]',
	) ) );
}
add_action( 'customize_register', 'fforward_customize_register' );

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 * Used with blogname and blogdescription.
 *
 * @since FaceForward 1.0
 */
function fforward_customize_preview_js() {
	wp_enqueue_script( 'fforward-customizer', get_template_directory_uri() . '/inc/theme-customizer.js', array( 'customize-preview' ), '20120523', true );
}
add_action( 'customize_preview_init', 'fforward_customize_preview_js' );