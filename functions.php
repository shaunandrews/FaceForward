<?php
/**
 * Face Foward functions and definitions
 *
 * @package Face Foward
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'fforward_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function fforward_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Face Foward, use a find and replace
	 * to change 'fforward' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'fforward', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	// Load up our theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	$theme_options = fforward_get_theme_options();
	$default_background_color = 'efefef';
	if ( 'dark' == $theme_options['color_scheme'] )
		$default_background_color = '25353c';
	if ( 'light' == $theme_options['color_scheme'] )
		$default_background_color = '8dcae7';

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'fforward' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/*
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'fforward_custom_background_args', array(
		'default-color' => $default_background_color,
		'default-image' => get_template_directory_uri() . '/images/flowers.jpg',
	) ) );

	add_theme_support( 'custom-header', array(
		'default-image' => get_template_directory_uri() . '/images/portrait.png',
		'width'                  => 150,
		'height'                 => 150,
	) );

	add_theme_support( 'infinite-scroll', array(
		'container'  => 'content',
		'footer'     => 'page',
	) );

	/*
	if ( is_admin() ) {   
		require_once('inc/theme-settings.php');  
	}
	*/
}
endif; // fforward_setup
add_action( 'after_setup_theme', 'fforward_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function fforward_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Browse Bar', 'fforward' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'fforward_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function fforward_scripts() {
	wp_enqueue_style( 'fforward-style', get_stylesheet_uri() );
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons.css', array(), '20120206', true );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'fforward-specific', get_template_directory_uri() . '/js/faceforward.js', array(), '20120206', true );

	wp_enqueue_script( 'fforward-waypoints', get_template_directory_uri() . '/js/waypoints.js', array(), '20120206', true );

	wp_enqueue_script( 'fforward-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'fforward-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'fforward-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'fforward_scripts' );

/**
 * Update the preview on the customer-header settings page
 *
 */
function fforward_customer_header_preview() {
	?>
	<style type="text/css">
		.appearance_page_custom-header #headimg #name,
		.appearance_page_custom-header #headimg #desc {
			display: none;
		}
		.appearance_page_custom-header #headimg {
			background-size: 150px;
			border-radius: 100%;
		}
		.appearance_page_custom-header .available-headers label img {
			height: 75px;
			width: 75px;
			border-radius: 100%;
		}
	</style>
	<?php
}
add_action( 'custom_header_options', 'fforward_customer_header_preview' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';