<?php

// Add extensions of the University Center project model.
include_once( __DIR__ . '/includes/ascent-uc-project.php' );

add_action( 'wp_enqueue_scripts', 'sandbox_child_enqueue_scripts'); 
/**
 * Enqueue custom scripting in child theme.
 */
function sandbox_child_enqueue_scripts() {
	wp_enqueue_style( 'asc', get_stylesheet_directory_uri() . '/scss/asc.css' );
	wp_enqueue_script( 'cu', get_stylesheet_directory_uri() . '/js/cu.js', array( 'jquery' ), spine_get_script_version(), true );
}
/* 
* Add HTML5 search box on the side bar menu
*/
add_theme_support( 'html5', array( 'search-form' ) );
/*
* Override with new custom favicon
*/
function blog_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('wpurl').'wp-content/themes/ascent/favicon.ico" />';
}
add_action('wp_head', 'blog_favicon');