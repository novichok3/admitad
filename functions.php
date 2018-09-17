<?php
function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), false);
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );

/////////////// added by dt
///

add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'child-understrap-styles-dt', get_stylesheet_directory_uri() . '/style.css');
});

add_action( 'wp_enqueue_scripts', 'my_scripts' );
function my_scripts() {
    wp_register_script( 'hip-ajax', get_stylesheet_directory_uri() . '/ajax.js', array( 'jquery' ) );
    wp_localize_script( 'hip-ajax', 'object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'hip-ajax');
    wp_dequeue_script('jquery');
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_stylesheet_directory_uri() . '/js/jquery-3.3.1.min.js', array(), null, true);
}

add_action( 'wp_enqueue_scripts', 'add_google_fonts' );
function add_google_fonts() {
    wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300|PT+Sans|PT+Sans+Narrow', false );
}

add_action('template_redirect', 'redirect_to_real_estate_archive');
function redirect_to_real_estate_archive() {
    if(is_home()) {
        wp_redirect(get_site_url() .'/real_estate');
        exit();
    }
}

require_once ('process_ajax_request.php');
require_once (get_theme_file_path().'/init/custom_post_type_real_estate.php');
//require_once (get_theme_file_path().'/init/custom_real_estate_city_taxonomy.php');
require_once (get_theme_file_path().'/init/custom_post_type_city.php');
require_once (get_theme_file_path().'/init/widget-city_list.php');
