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

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

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

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'fforward_custom_background_args', array(
		'default-image' => get_template_directory_uri() . '/images/flowers.jpg',
		'default-color' => 'efefef'
	) ) );

	add_theme_support( 'custom-header', array(
		'width'                  => 150,
		'height'                 => 150
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

function fforward_admin_menu() {
	add_theme_page(
		__('FaceForward'),
		__('FaceForward','fforward_textdomain'),
		'manage_options',
		'fforward-theme',
		'my_options_page'
	);
}
add_action( 'admin_menu', 'fforward_admin_menu' );

function fforward_admin_init() {
    register_setting( 'fforward-settings-group', 'fforward-setting' );
    add_settings_section( 'section-one', 'Section One', 'section_one_callback', 'fforward-theme' );
    add_settings_field( 'field-one', 'Field One', 'field_one_callback', 'fforward-theme', 'section-one' );
}
add_action( 'admin_init', 'fforward_admin_init' );

function section_one_callback() {
    echo 'Some help text goes here.';
}

function field_one_callback() {
    $setting = esc_attr( get_option( 'fforward-setting' ) );
    echo "<input type='text' name='fforward-setting' value='$setting' />";
}

function my_options_page() {
    ?>
    <div class="wrap">
        <h2>My Plugin Options</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'fforward-settings-group' ); ?>
            <?php do_settings_sections( 'fforward-theme' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}



/**
 * Register widgetized area and update sidebar with default widgets
 */
function fforward_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Site Top', 'fforward' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Browse Bar', 'fforward' ),
		'id'            => 'sidebar-2',
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
	wp_enqueue_script( 'fforward-specific', get_template_directory_uri() . '/js/facefoward.js', array(), '20120206', true );
	
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
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function fforward_get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
//require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
