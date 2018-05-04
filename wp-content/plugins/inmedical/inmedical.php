<?php

/*
  Plugin Name: InMedical
  Plugin URI:
  Description:
  Version: 1.0.7
  Author: InwaveThemes
  Author URI: http://www.inwavethemes.com
  License: GNU General Public License v2 or later
 */

/**
 * Description of InMedical
 *
 * @developer duongca
 */
if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

global $imd_settings;

include_once 'includes/function.admin.php';
include_once 'includes/function.front.php';
include_once 'includes/widgets/our-departments.php';

// translate plugin
add_action('plugins_loaded', 'imd_load_textdomain');
add_action('plugins_loaded', 'initPluginSettings');

function imd_load_textdomain() {
    load_plugin_textdomain('inwavethemes', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

if (!defined('INMEDICAL_THEME_PATH')) {
    define('INMEDICAL_THEME_PATH', WP_PLUGIN_DIR . '/inmedical/themes/');
}

if (!defined('IMD_LIMIT_ITEMS')) {
    define('IMD_LIMIT_ITEMS', 10);
}

$utility = new inMedicalUtility();

register_activation_hook(__FILE__, 'inMedicalInstall');
register_uninstall_hook(__FILE__, 'inMedicalUninstall');

//Check plugin update
add_action('plugins_loaded', 'inMedicalCheckUpdate');

//Add parralax menu
add_action('admin_menu', 'inMedicalAddAdminMenu');
add_action('admin_menu', 'hide_new_indepartment');

// Hook into the 'init' action
add_action('init', 'inMedicalCreateMedicalPostType', 0);
add_action('init', 'inMedicalCreateDoctorPostType', 0);
add_action('init', 'inMedicalCreateDepartmentPostType', 0);
add_action('init', 'inMedicalCreateBookedAppointmentPostType', 0);
add_action('init', array($utility, 'inMedicalAddImageSize'));
add_action('current_screen', 'inMedicalScreen');

// Add scripts
add_action('admin_enqueue_scripts', 'inMedicalAdminAddScript');

//init plugin theme
add_action('after_switch_theme', array($utility, 'initPluginThemes'));
add_action('admin_post_inMedicalFilter', 'inMedicalFilter');
add_action('admin_notices', 'inmedicalAdminMainMenu' );
add_filter('inmedical_admin_menu', 'inmedicalFilterAdminMenu',10,1);
add_action('inmedical_start_tab_content','inmedical_start_tab_content');
add_action('inmedical_tab_content','inmedical_tab_content');
add_action('inmedical_end_tab_content','inmedical_end_tab_content');

//Add metabox
if (is_admin()) {
    add_action('load-post.php', 'inMedicalAddMetaBox');
    add_action('load-post-new.php', 'inMedicalAddMetaBox');
    add_filter('manage_posts_columns', 'inMedicalHeaderColumnsHeader');
    add_action('manage_posts_custom_column', 'inMedicalColumnsContent', 10, 2);
    add_filter( "pre_get_posts", "inMedicalSearchQuery");
    add_action('restrict_manage_posts', 'inMedicalRestrictManagePosts');
}

//Add action save settings
add_action('admin_post_imdSaveSettings', 'imdSaveSettings');

//Add action to process extrafield
add_action('admin_post_inMedicalSaveExtrafield', 'inMedicalSaveExtrafield');
add_action('admin_post_inMedicalDeleteExtra', 'inMedicalDeleteExtra');
add_action('admin_post_inMedicalDeleteExtras', 'inMedicalDeleteExtras');

//update booking
add_action('admin_post_inMedicalSaveBookingOrder', 'inMedicalSaveBookingOrder');

add_action('wp_ajax_nopriv_loadDoctorOptions', 'loadDoctorOptions');
add_action('wp_ajax_loadDoctorOptions', 'loadDoctorOptions');
add_action('wp_ajax_bookingEventAccept', 'bookingEventAccept');
add_action('wp_ajax_addAppointment', 'inMedicaladdAppointment');
add_action('wp_ajax_editAppointment', 'inMedicaleditAppointment');
add_action('wp_ajax_deleteAppointment', 'inMedicaldeleteAppointment');
add_action('wp_ajax_duplicateAppointment', 'inMedicalduplicateAppointment');
add_action('wp_ajax_sortAppointment', 'inMedicalsortAppointment');
/*add_action('wp_ajax_chooseDateBookingAppointment', 'chooseDateBookingAppointment');
add_action('wp_ajax_newBookingAppointment', 'newBookingAppointment');*/

/*fontend*/
add_action('wp_ajax_imd_get_appointments', 'imd_get_appointments');
add_action('wp_ajax_nopriv_imd_get_appointments', 'imd_get_appointments');
add_action('wp_ajax_nopriv_imd_get_appointment_form', 'imd_get_appointment_form');
add_action('wp_ajax_imd_get_appointment_form', 'imd_get_appointment_form');
add_action('wp_ajax_imd_request_appointment', 'imd_request_appointment');
add_action('wp_ajax_nopriv_imd_request_appointment', 'imd_request_appointment');
add_action('wp_ajax_imd_booked_next_month', 'imd_booked_next_month');
add_action('wp_ajax_nopriv_imd_booked_next_month', 'imd_booked_next_month');
add_action('wp_ajax_imd_booked_prev_month', 'imd_booked_prev_month');
add_action('wp_ajax_nopriv_imd_booked_prev_month', 'imd_booked_prev_month');

/* ----------------------------------------------------------------------------------
  FRONTEND FUNCTIONS
  ---------------------------------------------------------------------------------- */

/**
 * Register and enqueue scripts and styles for frontend.
 *
 * @since 1.0.0
 */
//Add site script
add_action('wp_enqueue_scripts', 'inMedicalAddSiteScript');

//Define plugin shortcodes
add_shortcode('inmedical_upcomming_event', 'inmedical_upcomming_event_outhtml');
add_shortcode('inmedical_event_listing', 'inmedical_event_listing_outhtml');
add_shortcode('inmedical_timetable', 'inmedical_timetable_outhtml');
add_shortcode('inmedical_doctors', 'inmedical_doctors_outhtml');
add_shortcode('inmedical_departments', 'inmedical_departments_outhtml');
add_shortcode('inmedical_book_ticket_form', 'inmedical_book_ticket_form_outhtml');
add_shortcode('inmedical_appointments', 'inmedical_appointments_shortcode');
add_shortcode('inmedical_appointments_scroll_vertical', 'inmedical_appointments_scroll_vertical_shortcode');

$doctor = new inMediacalDoctor();
add_action('wp_ajax_nopriv_sendMessageToDoctor', array($doctor, 'sendMessageToDoctor'));
add_action('wp_ajax_sendMessageToDoctor', array($doctor, 'sendMessageToDoctor'));

//Submit form
add_action('init', 'inMedicalSubmitForm');
