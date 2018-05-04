<?php
/*
  Plugin Name: Inwave Common Version Medical
  Plugin URI: http://inwavethemes.com
  Description: Includes advanced addon elements for Visual Composer, Custom post type, ...
  Version: 1.0.4
  Author: Inwavethemes
  Author URI: http://www.inwavethemes.com
  License: GNU General Public License v2 or later
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

define('INWAVE_COMMON_VERSION', '1.0.1');
define('INWAVE_COMMON', plugin_dir_path( __FILE__ ));

// translate plugin
load_plugin_textdomain('inwavethemes', false, dirname(plugin_basename(__FILE__)) . '/languages/');

//include files
require_once INWAVE_COMMON .'inc/helper.php';
require_once INWAVE_COMMON .'inc/shortcode.class.php';

if(!function_exists('inwave_initialize_cmb_meta_boxes')){
    add_action( 'init', 'inwave_initialize_cmb_meta_boxes', 9999 );
    /**
     * Initialize the metabox class.
     */
    function inwave_initialize_cmb_meta_boxes() {
        if ( ! class_exists( 'inwave_CMB_Meta_Box' ) )
            require_once INWAVE_COMMON .'inc/metaboxes/init.php';
    }
}
