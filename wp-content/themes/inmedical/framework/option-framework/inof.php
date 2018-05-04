<?php
/*
Title		: INOF
Description	: Inwave Options Framework
Version		: 1.0
Author		: Inwave
Author URI	: http://inwave.vn
License		: GPLv3 - http://www.gnu.org/copyleft/gpl.html

Credits		: Thematic Options
		 	  Woo Themes - http://woothemes.com/
*/

/**
 * Definitions
 *
 * @since 1.0
 */
$theme_version = '';
$inwave_smof_output = '';

if( function_exists( 'wp_get_theme' ) ) {
	if( is_child_theme() ) {
		$temp_obj = wp_get_theme();
		$theme_obj = wp_get_theme( $temp_obj->get('Template') );
	} else {
		$theme_obj = wp_get_theme();
	}

	$theme_version = $theme_obj->get('Version');
	$theme_name = $theme_obj->get('Name');
	$theme_uri = $theme_obj->get('ThemeURI');
	$author_uri = $theme_obj->get('AuthorURI');
} else {
	$theme_data = wp_get_theme( get_template_directory().'/style.css' );
	$theme_version = $theme_data['Version'];
	$theme_name = $theme_data['Name'];
	$theme_uri = $theme_data['ThemeURI'];
	$author_uri = $theme_data['AuthorURI'];
}

define( 'INWAVE_OF_VERSION', '1.0' );
if( !defined('INWAVE_OF_PATH') )
	define( 'INWAVE_OF_PATH', get_template_directory().'/framework/option-framework/' );
if( !defined('INWAVE_OF_DIR') )
	define( 'INWAVE_OF_DIR', get_template_directory_uri() . '/framework/option-framework/' );

define( 'INWAVE_OF_THEMENAME', $theme_name );
/* Theme version, uri, and the author uri are not completely necessary, but may be helpful in adding functionality */
define( 'INWAVE_OF_THEMEVERSION', $theme_version );
define( 'INWAVE_OF_THEMEURI', $theme_uri );
define( 'INWAVE_OF_THEMEAUTHORURI', $author_uri );

define( 'INWAVE_OF_BACKUPS','backups' );

/**
 * Required action filters
 *
 * @uses add_action()
 *
 * @since 1.0.0
 */

if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) add_action('admin_head','inwave_of_option_setup');
add_action('init', 'inwave_of_option_check_setup');
add_action('admin_head', 'inwave_of_admin_message');
add_action('admin_init','inwave_of_admin_init');
add_action('admin_menu', 'inwave_of_add_admin');

/**
 * Required Files
 *
 * @since 1.0.0
 */
require_once( INWAVE_OF_PATH . 'inc/functions.filters.php' );
require_once( INWAVE_OF_PATH . 'inc/functions.interface.php' );
require_once( INWAVE_OF_PATH . 'inc/functions.admin.php' );
require_once ( INWAVE_OF_PATH . 'inc/class.options_machine.php' );

/**
 * AJAX Saving Options
 *
 * @since 1.0.0
 */
add_action('wp_ajax_of_ajax_post_action', 'inwave_of_ajax_callback');
