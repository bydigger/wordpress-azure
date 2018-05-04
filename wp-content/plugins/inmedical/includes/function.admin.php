<?php
/*
 * @package Inwave Funding
 * @version 1.0.1
 * @created May 11, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of file: File contain all function to process in admin page
 *
 * @developer duongca
 */
require 'utility.php';

register_sidebar(array(
    'name' => esc_html__('Sidebar Departments', 'inwavethemes'),
    'id' => 'sidebar-departments',
    'description' => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title"><span>',
    'after_title' => '</span></h3>',
));

register_sidebar(array(
    'name' => esc_html__('Sidebar Department Detail', 'inwavethemes'),
    'id' => 'sidebar-department-detail',
    'description' => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title"><span>',
    'after_title' => '</span></h3>',
));

//Add plugin menu to admin sidebar
function inMedicalAddAdminMenu() {
    //Doctor menu
    add_submenu_page('edit.php?post_type=indepartment', __('Doctors', 'inwavethemes'), __('Doctors', 'inwavethemes'), 'manage_options', 'edit.php?post_type=indoctor');

    //Appointment
    add_submenu_page('edit.php?post_type=indepartment', __('Appointments', 'inwavethemes'), __('Appointments', 'inwavethemes'), 'manage_options', 'appointments', 'inMedicalAppointmentRenderPage');
    add_submenu_page('edit.php?post_type=indepartment', __('Appointment Orders Appts', 'inwavethemes'), __('Appointment Orders', 'inwavethemes'), 'manage_options', 'edit.php?post_type=imd_bapm');

    //Books menu
    add_submenu_page('edit.php?post_type=indepartment', __('Events', 'inwavethemes'), __('Events', 'inwavethemes'), 'manage_options', 'edit.php?post_type=inmedical');
    add_submenu_page('edit.php?post_type=indepartment', __('Event Orders', 'inwavethemes'), __('Event Orders', 'inwavethemes'), 'manage_options', 'bookings', 'inMedicalBookRenderPage');
    add_submenu_page(null, __('Booking Info', 'inwavethemes'), null, 'manage_options', 'booking/edit', 'inMedicalBookingViewRenderPage');
    //Doctor Extra menu
    add_submenu_page('edit.php?post_type=indepartment', __('Department Extrafield', 'inwavethemes'), __('Extrafields', 'inwavethemes'), 'manage_options', 'extrafields', 'inMedicalExtrafieldRenderPage');
    add_submenu_page(null, __('Add Room Extrafield', 'inwavethemes'), null, 'manage_options', 'extrafield/addnew', 'inMedicalAddnewExtraRenderPage');
    add_submenu_page(null, __('Edit Room Extrafield', 'inwavethemes'), null, 'manage_options', 'extrafield/edit', 'inMedicalAddnewExtraRenderPage');

    //Settings
    add_submenu_page('edit.php?post_type=indepartment', __('Settings', 'inwavethemes'), __('Settings', 'inwavethemes'), 'manage_options', 'settings', 'inMedicalSettingsRenderPage');
}

if (!function_exists('inMedicalInstall')) {
    //1.0.0, 1.1.0
    global $inMedicalVersion;
    $inMedicalVersion = '1.0.1';

    /**
     *
     * @global type $wpdb
     * @global type $inMedicalVersion
     */
    function inMedicalInstall() {
        global $wpdb;
        global $inMedicalVersion;
        $utility = new inMedicalUtility();

        $charset_collate = '';
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}imd_events (
          id int(11) NOT NULL AUTO_INCREMENT,
          event_post int(11) NOT NULL,
          doctor_post int(11) NOT NULL,
          department_post int(11) NOT NULL,
          event_date varchar(20) DEFAULT NULL,
          time_start varchar(20) DEFAULT NULL,
          time_end varchar(20) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql);


        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}imd_appointments (
        id int(11) NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          doctor int(11) DEFAULT NULL,
          day varchar(3) DEFAULT NULL,
          time_start tinyint(4) DEFAULT NULL,
          time_end tinyint(4) DEFAULT NULL,
          date_start int(11) DEFAULT NULL,
          date_end int(11) DEFAULT NULL,
          slot tinyint(4) DEFAULT NULL,
          sort int(11) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}imd_booking_ticket (
          id int(11) NOT NULL AUTO_INCREMENT,
          event_post int(11) NOT NULL,
          department_post int(11) NOT NULL,
          doctor_post int(11) NOT NULL,
          first_name varchar(50) DEFAULT NULL,
          last_name varchar(50) DEFAULT NULL,
          email varchar(50) DEFAULT NULL,
          phone varchar(50) DEFAULT NULL,
          date_of_birth varchar(20) DEFAULT NULL,
          gender varchar(20) DEFAULT NULL,
          address varchar(100) DEFAULT NULL,
          appointment_date varchar(20) DEFAULT NULL,
          appointment_reason text DEFAULT NULL,
          status int(11) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}imd_extrafield (
          id int(11) NOT NULL AUTO_INCREMENT,
          name varchar(255) DEFAULT NULL,
          type varchar(20) DEFAULT NULL,
          icon varchar(50) DEFAULT NULL,
          default_value text,
          description text,
          ordering int(11) DEFAULT NULL,
          published int(11) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}imd_extrafield_value (
          doctor_id int(11) NOT NULL,
          extrafield_id int(11) NOT NULL,
          value text NOT NULL
        ) $charset_collate;";
        dbDelta($sql);

        //add $inMedicalVersion table version
        update_option('inMedicalVersion', $inMedicalVersion);

        //Add themes
        $utility->initPluginThemes();
    }

}

if (!function_exists('inMedicalCheckUpdate')) {

    function inMedicalCheckUpdate() {
        return;
    }

}

if (!function_exists('inMedicalUninstall')) {

    function inMedicalUninstall() {

        global $wpdb;
        $option_names = array('inMedicalVersion', 'imd_settings');
        $tables = array($wpdb->prefix . 'imd_extrafield_value', $wpdb->prefix . 'imd_extrafield');

        foreach ($option_names as $option) {
            delete_option($option);
            delete_site_option($option);
        }

        //drop a custom db table
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS " . $table);
        }

        //delete all inmedical post meta
        $wpdb->query("DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE 'imd_%'");
        //delete all post with post type 
        $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'inmedical'");

        //delete all post with post type 
        $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'indepartment'");

        //delete all post with post type 
        $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'indoctor'");
    }

}

/**
 * Function to register Inwave Medical Post_type with Wordpress
 */
function inMedicalCreateMedicalPostType() {
    global $imd_settings;
    if ($imd_settings) {
        $general = $imd_settings['general'];
    }
    $labels = array(
        'name' => _x('Events', 'Post Type General Name', 'inwavethemes'),
        'singular_name' => _x('Event', 'Post Type Singular Name', 'inwavethemes'),
        'menu_name' => __('Medical Event', 'inwavethemes'),
        'parent_item_colon' => __('Parent Event:', 'inwavethemes'),
        'all_items' => __('All Events', 'inwavethemes'),
        'view_item' => __('View Event', 'inwavethemes'),
        'add_new_item' => __('Add New Event', 'inwavethemes'),
        'add_new' => __('Add New', 'inwavethemes'),
        'edit_item' => __('Edit Item', 'inwavethemes'),
        'update_item' => __('Update Item', 'inwavethemes'),
        'search_items' => __('Search Item', 'inwavethemes'),
        'not_found' => __('Not found', 'inwavethemes'),
        'not_found_in_trash' => __('Not found in Trash', 'inwavethemes'),
    );
    $rewrite = array(
        'slug' => isset($general['medical_slug']) ? $general['event_slug'] : 'event',
        'with_front' => false,
        'pages' => true,
        'feeds' => true,
    );
    $args = array(
        'label' => __('inmedical', 'inwavethemes'),
        'description' => __('Inwave Medical', 'inwavethemes'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'comments'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        //'menu_icon' => 'dashicons-calendar-alt',
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'rewrite' => $rewrite,
        'capability_type' => 'page',
    );
    register_post_type('inmedical', $args);
}

/**
 * Function to register Inwave Medical Post_type with Wordpress
 */
function inMedicalCreateDoctorPostType() {
    global $imd_settings;
    if ($imd_settings) {
        $general = $imd_settings['general'];
    }
    $labels = array(
        'name' => _x('Doctors', 'Post Type General Name', 'inwavethemes'),
        'singular_name' => _x('Doctor', 'Post Type Singular Name', 'inwavethemes'),
        'menu_name' => __('Doctor', 'inwavethemes'),
        'parent_item_colon' => __('Parent Doctor:', 'inwavethemes'),
        'all_items' => __('All Doctors', 'inwavethemes'),
        'view_item' => __('View Doctor', 'inwavethemes'),
        'add_new_item' => __('Add New Doctor', 'inwavethemes'),
        'add_new' => __('Add New', 'inwavethemes'),
        'edit_item' => __('Edit Item', 'inwavethemes'),
        'update_item' => __('Update Item', 'inwavethemes'),
        'search_items' => __('Search Item', 'inwavethemes'),
        'not_found' => __('Not found', 'inwavethemes'),
        'not_found_in_trash' => __('Not found in Trash', 'inwavethemes'),
    );
    $rewrite = array(
        'slug' => isset($general['doctor_slug']) ? $general['doctor_slug'] : 'doctor',
        'with_front' => false,
        'pages' => true,
        'feeds' => true,
    );
    $args = array(
        'label' => __('indoctor', 'inwavethemes'),
        'description' => __('Inwave Doctor', 'inwavethemes'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments','page-attributes'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'query_var' => 'indoctor',
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'rewrite' => $rewrite,
        'capability_type' => 'post',
    );
    register_post_type('indoctor', $args);
}

/**
 * Function to register Inwave Medical Post_type with Wordpress
 */
function inMedicalCreateDepartmentPostType() {
    global $imd_settings;
    if ($imd_settings) {
        $general = $imd_settings['general'];
    }
    $labels = array(
        'name' => _x('Inmedical', 'Post Type General Name', 'inwavethemes'),
        'singular_name' => _x('Department', 'Post Type Singular Name', 'inwavethemes'),
        'menu_name' => __('Inmedical', 'inwavethemes'),
        'parent_item_colon' => __('Parent Department:', 'inwavethemes'),
        'all_items' => __('Departments', 'inwavethemes'),
        'view_item' => __('View Department', 'inwavethemes'),
        'add_new_item' => __('Add New Department', 'inwavethemes'),
        'add_new' => __('Add New', 'inwavethemes'),
        'edit_item' => __('Edit Item', 'inwavethemes'),
        'update_item' => __('Update Item', 'inwavethemes'),
        'search_items' => __('Search Item', 'inwavethemes'),
        'not_found' => __('Not found', 'inwavethemes'),
        'not_found_in_trash' => __('Not found in Trash', 'inwavethemes'),
    );
    $rewrite = array(
        'slug' => isset($general['department_slug']) ? $general['department_slug'] : 'department',
        'with_front' => false,
        'pages' => true,
        'feeds' => true,
    );
    $args = array(
        'label' => __('indepartment', 'inwavethemes'),
        'description' => __('Inwave Department', 'inwavethemes'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'query_var' => 'indepartment',
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'rewrite' => $rewrite,
        'capability_type' => 'page',
        'taxonomies'  => array( 'category' ),
//        'capabilities' => array(
//            'create_posts' => false,
//        ),
    );
    register_post_type('indepartment', $args);
}

function hide_new_indepartment() {
// Hide sidebar link
    global $submenu;
    unset($submenu['edit.php?post_type=indepartment'][10]);
}

/**
 * Function to register Inwave Medical Post_type with Wordpress
 */
function inMedicalCreateBookedAppointmentPostType() {
    $labels = array(
        'name' => __('Booking Appointments', 'inwavethemes'),
        'singular_name' => __('Booking Appointment', 'inwavethemes'),
        'add_new' => __('Add New', 'inwavethemes'),
        'all_items' => __('Booking Appointments', 'inwavethemes'),
        'add_new_item' => __('Add New', 'inwavethemes'),
        'edit_item' => __('Edit', 'inwavethemes'),
        'new_item' => __('New', 'inwavethemes'),
        'view_item' => __('View', 'inwavethemes'),
        'search_items' => __('Search', 'inwavethemes'),
        'not_found' => __('Not found', 'inwavethemes'),
        'not_found_in_trash' => __('Not found in Trash', 'inwavethemes'),
        'parent_item_colon' => '',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'taxonomies' => array(),
        'hierarchical' => false,
        'rewrite' => false,
        'query_var' => true,
        //'show_in_menu'      => 'edit.php?post_type=inmedical',
        'show_in_menu' => false,
        'has_archive' => false,
        'menu_icon' => 'dashicons-list-view',
        'supports' => false,
        'capabilities' => array(
            'create_posts' => 'do_not_allow',  // Removes support for the "Add New" function, including Super Admin's
        ),
        'map_meta_cap' => true,
            //'register_meta_box_cb' => 'inMedicalBookedAppointmentMetabox'
    );

    register_post_type('imd_bapm', $args);
}

/*
  function inMedicalBookedAppointmentMetabox(){
  add_meta_box('inmedical-meta-box', __('Booking info', 'inwavethemes'), 'inMedicalBookedAppointmentMetabox_html', 'imd_bapm', 'advanced', 'high');
  } */

/**
 * Function to add script for admin page
 */
function inMedicalAdminAddScript() {
    wp_enqueue_style('font-awesome', plugins_url('/inmedical/assets/css/font-awesome/css/font-awesome.min.css'));
    wp_enqueue_style('select2', plugins_url('/inmedical/assets/css/select2.min.css'));
    wp_enqueue_style('imdadmin-style', plugins_url('/inmedical/assets/css/inmedical_admin.css'));
    wp_enqueue_style('iw-legacy-style', plugins_url('/inmedical/assets/css/iw-legacy.css'));
    wp_register_style('jquery-datetimepicker', plugins_url('/inmedical/assets/css/jquery.datetimepicker.css'));
    wp_register_style('bootstrap-popover', plugins_url('/inmedical/assets/css/bootstrap-popover.css'));
    wp_enqueue_style('custombox', plugins_url('/inmedical/assets/css/custombox.min.css'));

    if (class_exists('Inwave_Helper')) {
        $inwave_theme_option = Inwave_Helper::getConfig();
        wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $inwave_theme_option['google_api'] . '&libraries=places', array('jquery'), '1.0.0', true);
    }

    wp_register_script('iw-map-field', plugins_url() . '/inmedical/assets/js/iw-map-field.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('select2', plugins_url() . '/inmedical/assets/js/select2.min.js', array('jquery'), '1.0.0', true);
    wp_register_script('bootstrap-popover', plugins_url() . '/inmedical/assets/js/bootstrap-popover.js', array('jquery'), '1.0.0', true);
    wp_register_script('jquery-datetimepicker', plugins_url() . '/inmedical/assets/js/jquery.datetimepicker.full.min.js', array(), '1.0.0', true);
    wp_enqueue_script('custombox', plugins_url() . '/inmedical/assets/js/custombox.min.js', array(), '', true);
    wp_enqueue_script('imdadmin-script', plugins_url() . '/inmedical/assets/js/inmedical_admin.js', array(), '1.0.0', true);
    wp_localize_script('imdadmin-script', 'inMedicalCfg', array('siteUrl' => site_url(), 'adminUrl' => admin_url(), 'ajaxUrl' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce("iwm-security")));

    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
}

function inMedicalFilter() {
    $link = filter_input(INPUT_SERVER, 'HTTP_REFERER');
    $link_param = parse_url($link);
    $q_vars = array();
    parse_str($link_param['query'], $q_vars);
    $post = filter_input_array(INPUT_POST);
    unset($post['action']);
    $query_vars = array_merge($q_vars, $post);
    $new_params = array();
    foreach ($query_vars as $key => $value) {
        if ($value) {
            $new_params[$key] = $value;
        }
    }

    $params = http_build_query($new_params);
    wp_redirect($link_param['scheme'] . '://' . $link_param['host'] . $link_param['path'] . '?' . $params);
}

/* * *********************************************************
 * ******** CODE CAMPAIGN PAGE POST ********
 * ******************************************************** */

//Add metabox
function inMedicalAddMetaBox() {
    $screen = get_current_screen();
    if ($screen->post_type == 'inmedical') {
        $works = new inMedicalWorkingTable();
        add_action('add_meta_boxes', array($works, 'add_meta_box'));
        add_action('save_post', array($works, 'save'));
    }
    if ($screen->post_type == 'indepartment') {
        $indepartment = new inMediacalDepartment();
        add_action('add_meta_boxes', array($indepartment, 'add_meta_box'));
        add_action('save_post', array($indepartment, 'save'));
    }
    if ($screen->post_type == 'indoctor') {
        $indoctor = new inMediacalDoctor();
        add_action('add_meta_boxes', array($indoctor, 'add_meta_box'));
        add_action('save_post', array($indoctor, 'save'));
    }
    if ($screen->post_type == 'imd_bapm') {
        add_action('add_meta_boxes', array('IMD_Booking_Appointment', 'add_meta_box'));
        add_action('save_post', array('IMD_Booking_Appointment', 'save_post'));
    }
}

function inMedicalHeaderColumnsHeader($columns) {
    $screen = get_current_screen();
    if ($screen->post_type == 'imd_bapm') {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title', 'inwavethemes'),
            'status' => __('Status', 'inwavethemes'),
            'full_name' => __('Full Name', 'inwavethemes'),
            'email' => __('Email', 'inwavethemes'),
            'phone' => __('Phone', 'inwavethemes'),
            'department' => __('Department', 'inwavethemes'),
            'doctor' => __('Doctor', 'inmedical'),
            'appoitment_date' => __('Appointment Date', 'inwavethemes'),
        );
    }

    return $columns;
}

function inMedicalColumnsContent($column_name, $post_ID) {
    $screen = get_current_screen();
    if ($screen->post_type == 'imd_bapm') {
        IMD_Booking_Appointment::admin_columns_content($column_name, $post_ID);
    }
}

function inMedicalSearchQuery($query) {
    if (isset($query->query['post_type']) && $query->query['post_type'] == 'imd_bapm') {
        IMD_Booking_Appointment::admin_search_query($query);
    }
}

function inMedicalRestrictManagePosts($post_type) {
    if ($post_type == 'imd_bapm') {
        IMD_Booking_Appointment::restrict_manage_posts($post_type);
    }
}

/* * **************************************************
 * ************ CODE SETTINGS PAGE ****************
 * ************************************************** */

function inMedicalSettingsRenderPage() {
    include_once 'views/settings.php';
}

function imdSaveSettings() {
    $data = $_POST;
    update_option('imd_settings', serialize($data['imd_settings']));
    wp_redirect(admin_url('edit.php?post_type=indepartment&page=settings'));
}

if (!function_exists('initPluginSettings')) {

    function initPluginSettings() {
        global $imd_settings;
        $imd_settings = unserialize(get_option('imd_settings'));

        if (!$imd_settings) {
            update_option('imd_settings', unserialize('s:4514:"a:2:{s:7:"general";a:8:{s:10:"event_slug";s:5:"event";s:11:"doctor_slug";s:6:"doctor";s:15:"department_slug";s:10:"department";s:25:"auto_accept_booking_event";s:1:"0";s:14:"show_empty_day";s:1:"0";s:30:"auto_accept_booking_appoinment";s:1:"0";s:17:"book_before_hours";s:0:"";s:12:"day_off_work";a:1:{i:0;s:3:"Sun";}}s:6:"emails";a:8:{s:15:"new_appointment";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:39:"[ime_site_name] New Booking Appointment";s:7:"content";s:520:"Dear [ime_first_name] you have booked a appoinment on [ime_site_name]

------------------------------------------------------------------------------------------
Appointment Infomation.
Department : [ime_department link=\"true\"]
Doctor : [ime_doctor link=\"true\"]
Date : [ime_date]

-----------------------------------------------------------------------------------------

If you need to add any other help you please call phone to your us theo + (84) 12345678 or email: contact@inmedical.com.

Thank you!";}s:18:"accept_appointment";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:58:"[ime_site_name] Accepted your appoinment #[ime_booking_id]";s:7:"content";s:496:"Dear [ime_first_name] we have accepted your appoinment on [ime_site_name]

------------------------------------------------------------------------------------------
Appointment Infomation.
Department : [ime_department]
Doctor : [ime_doctor]
Date : [ime_date]

-----------------------------------------------------------------------------------------

If you need to add any other help you please call phone to your us theo + (84) 12345678 or email: contact@inmedical.com.

Thank you!";}s:18:"cancel_appointment";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:59:"[ime_site_name] Cencelled your appoinment #[ime_booking_id]";s:7:"content";s:398:"Dear [ime_first_name] we had cancelled  your appoinment on [ime_site_name]

------------------------------------------------------------------------------------------
Appointment Infomation.
Department : [ime_department]
Doctor : [ime_doctor]
Date : [ime_date]

-----------------------------------------------------------------------------------------

&nbsp;

The Reason: [ime_reason]";}s:21:"admin_new_appointment";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:40:"[ime_site_name] New Booking Appointment.";s:7:"content";s:107:"Just had someone make an appointment [ime_booking_edit_link] on [ime_site_name]. You should go check it out";}s:17:"new_booking_event";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:33:"[ime_site_name] New Booking Event";s:7:"content";s:511:"Dear [ime_first_name] you have booked a event on [ime_site_name]

------------------------------------------------------------------------------------------
Booking Infomation.
Department : [ime_department link=\"true\"]
Doctor : [ime_doctor link=\"true\"]
Date : [ime_date]

-----------------------------------------------------------------------------------------

If you need to add any other help you please call phone to your us theo + (84) 12345678 or email: contact@inmedical.com.

Thank you!";}s:20:"accept_booking_event";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:61:"[ime_site_name] Accepted your event booking #[ime_booking_id]";s:7:"content";s:487:"Dear [ime_first_name] we have accepted your event on [ime_site_name]

------------------------------------------------------------------------------------------
Booking Infomation.
Department : [ime_department]
Doctor : [ime_doctor]
Date : [ime_date]

-----------------------------------------------------------------------------------------

If you need to add any other help you please call phone to your us theo + (84) 12345678 or email: contact@inmedical.com.

Thank you!";}s:20:"cancel_booking_event";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:62:"[ime_site_name] Cencelled your event booking #[ime_booking_id]";s:7:"content";s:387:"Dear [ime_first_name] we had cancelled  your event booking on [ime_site_name]

------------------------------------------------------------------------------------------
Booking Infomation.
Department : [ime_department]
Doctor : [ime_doctor]
Date : [ime_date]

-----------------------------------------------------------------------------------------

The Reason: [ime_reason]";}s:23:"admin_new_booking_event";a:3:{s:6:"enable";s:1:"1";s:5:"title";s:34:"[ime_site_name] New Booking Event.";s:7:"content";s:109:"Just had someone make an event booking [ime_booking_edit_link] on [ime_site_name]. You should go check it out";}}}";'));
            $imd_settings = unserialize(get_option('imd_settings'));
        }
    }

}

function inMedicalScreen($current_screen) {
    if ('indepartment' == $current_screen->post_type) {
        new Inwave_Session();
    }
}

/* * *********************************************************
 * ******** FUNCTION PROCESS EXTRAFIELD ********
 * ******************************************************** */

/**
 * Function to render Room extrafield management page
 */
function inMedicalExtrafieldRenderPage() {
    $extrafield = new inMedicalExtra();
    $paging = new iwPaging();
    $utility = new inMedicalUtility();
    $start = $paging->findStart(IMD_LIMIT_ITEMS);
    $count = $extrafield->getCountExtrafield(filter_input(INPUT_GET, 'keyword'));
    $pages = $paging->findPages($count, IMD_LIMIT_ITEMS);
    $extrafields = $extrafield->getMedicalExtraFields($start, IMD_LIMIT_ITEMS, filter_input(INPUT_GET, 'keyword'));
    include_once 'views/extrafield.list.php';
}

/**
 * Function to render Add new or Edit Extrafield page
 */
function inMedicalAddnewExtraRenderPage() {
    $session = new Inwave_Session();
    $utility = new inMedicalUtility();
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $extrafield = new inMedicalExtra();
    $utility = new inMedicalUtility();
    if ($id) {
        $extrafield = $extrafield->getMedicalExtra($id);
        if (!$extrafield->getId()) {
            $session->set('inwave_message', $utility->getMessage(sprintf(__('No extrafield found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice'));
        }
    }
    include_once 'views/extrafield.edit.php';
}

/**
 * Function to save extrafield
 */
function inMedicalSaveExtrafield() {
    $extra = new inMedicalExtra();
    $session = new Inwave_Session();
    $utility = new inMedicalUtility();
    if (isset($_POST)) {
        $extrafield = $extra->getMedicalExtra(sanitize_text_field($_POST['id']));
        $msg = $title = $default_value = '';
        if ($_POST['name'] != '') {
            $extrafield->setName(sanitize_text_field($_POST['name']));
        } else {
            $msg .= __('- Please input name of field<br/>', 'inwavethemes');
        }
        $field_type = $extrafield->getType();
        if (isset($_POST['type']) && $field_type != $_POST['type']) {
            $extrafield->setType(sanitize_text_field($_POST['type']));
            $field_type = $extrafield->getType();
        }
        if (!$field_type) {
            $msg .= __('- Please select a field type<br/>');
        }

        if ($field_type == 'textarea') {
            $default_value = stripslashes($_POST['text_value']);
        }
        if ($field_type == 'link') {
            $default_value = serialize(array('link_value_link' => stripslashes($_POST['link_value_link']), 'link_value_text' => stripslashes($_POST['link_value_text']), 'link_value_target' => stripslashes($_POST['link_value_target'])));
        }
        if ($field_type == 'image') {
            $default_value = $_POST['image'];
        }
        if ($field_type == 'text') {
            $default_value = stripslashes($_POST['string_value']);
        }
        if ($field_type == 'dropdown_list') {
            $default_value = serialize(array(stripslashes($_POST['drop_value']), $_POST['drop_multiselect']));
        }
        if ($field_type == 'date') {
            $default_value = $_POST['date_value'];
        }
        if ($field_type == 'measurement') {
            $default_value = serialize(array('measurement_value' => stripslashes($_POST['measurement_value']), 'measurement_unit' => stripslashes($_POST['measurement_unit'])));
        }
        if ($msg == '') {
            $extrafield->setIcon($_POST['icon']);
            $extrafield->setDescription($_POST['description']);
            $extrafield->setPublished($_POST['published']);
            $extrafield->setOrdering(isset($_POST['ordering']) ? $_POST['ordering'] : null);
            $extrafield->setDefault_value($default_value);
            if ($extrafield->getId()) {
                $update = unserialize($extra->editMedicalExtra($extrafield));
                if (!$update['success']) {
                    $session->set('inwave_message', $utility->getMessage($update['msg'], 'error'));
                } else {
                    $session->set('inwave_message', $utility->getMessage($update['msg'], 'success'));
                }
            } else {
                $insert = unserialize($extra->addMedicalExtra($extrafield));
                if (!$insert['success']) {
                    $session->set('inwave_message', $utility->getMessage($insert['msg'], 'error'));
                } else {
                    $session->set('inwave_message', $utility->getMessage($insert['msg']));
                    $extrafield->setId($insert['data']);
                }
            }
        } else {
            $session->set('inwave_message', $utility->getMessage($msg, 'error'));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No data send'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=indepartment&page=extrafield/' . ($extrafield->getId() ? 'edit&id=' . $extrafield->getId() : 'addnew')));
}

/**
 * Delete single extrafield on list
 */
function inMedicalDeleteExtra() {
    $utility = new inMedicalUtility();
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $session = new Inwave_Session();
    $extra = new inMedicalExtra();
    if ($id && is_numeric($id)) {
        $del = unserialize($extra->deleteMedicalExtra($id));
        if (!$del['success']) {
            $session->set('inwave_message', $utility->getMessage($del['msg'], 'error'));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Booking extrafield has been removed', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=indepartment&page=extrafields'));
}

function inMedicaladdAppointment() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $title = sanitize_text_field($_POST['title']);
    $doctor = sanitize_text_field($_POST['doctor']);
    $day = sanitize_text_field($_POST['day']);
    $slot = sanitize_text_field($_POST['slot']);
    $time_start = sanitize_text_field($_POST['time_start']);
    $time_end = sanitize_text_field($_POST['time_end']);
    $date_start = sanitize_text_field($_POST['date_start']);
    $date_end = sanitize_text_field($_POST['date_end']);
    $add_all = sanitize_text_field($_POST['add_all']);

    $data = array(
        'title' => $title,
        'doctor' => $doctor,
        'slot' => $slot,
        'day' => $day,
        'time_start' => $time_start,
        'time_end' => $time_end,
        'date_start' => $date_start ? strtotime($date_start) : '',
        'date_end' => $date_end ? strtotime($date_end) : '',
    );

    $appointments = array();
    if ($add_all) {
        $days = IMD_Appointment::get_day_array();
        foreach ($days as $day => $title) {
            if (!in_array($day, imd_get_day_off_work())) {
                $data['day'] = $day;
                $appointment_id = IMD_Appointment::add_appointment($data);
                if ($appointment_id) {
                    $appointment = IMD_Appointment::init($appointment_id);
                    $appointments[$day] = $appointment->get_backend_appointment_html();
                }
            }
        }
    } else {
        $appointment_id = IMD_Appointment::add_appointment($data);
        $appointment = IMD_Appointment::init($appointment_id);
        $appointments[$day] = $appointment->get_backend_appointment_html();
    }

    $return = array('success' => true, 'appointments' => $appointments);

    echo json_encode($return);
    exit;
}

function inMedicaleditAppointment() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $id = sanitize_text_field($_POST['id']);
    $title = sanitize_text_field($_POST['title']);
    $slot = sanitize_text_field($_POST['slot']);
    $doctor = sanitize_text_field($_POST['doctor']);
    $time_start = sanitize_text_field($_POST['time_start']);
    $time_end = sanitize_text_field($_POST['time_end']);
    $date_start = sanitize_text_field($_POST['date_start']);
    $date_end = sanitize_text_field($_POST['date_end']);

    $data = array(
        'id' => $id,
        'title' => $title,
        'doctor' => $doctor,
        'slot' => $slot,
        'time_start' => $time_start,
        'time_end' => $time_end,
        'date_start' => $date_start ? strtotime($date_start) : '',
        'date_end' => $date_end ? strtotime($date_end) : '',
    );

    IMD_Appointment::update_appointment($data);
    $appointment = IMD_Appointment::init($id);
    $return = array('success' => true, 'html' => $appointment->get_backend_appointment_html());
    echo json_encode($return);
    exit;
}

function inMedicaldeleteAppointment() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $id = $_POST['id'];
    if ($id) {
        $total = IMD_Appointment::delete_appointment($id);
        if ($total) {
            $return = array('success' => true);
            echo json_encode($return);
            exit;
        }
    }

    $return = array('success' => false);
    echo json_encode($return);
    exit;
}

function inMedicalduplicateAppointment() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $id = $_POST['id'];
    if ($id) {
        $new_id = IMD_Appointment::duplicate_appointment($id);
        if ($new_id) {
            $appoinment = IMD_Appointment::init($new_id);
            $return = array('success' => true, 'html' => $appoinment->get_backend_appointment_html());
            echo json_encode($return);
            exit;
        }
    }

    $return = array('success' => false);
    echo json_encode($return);
    exit;
}

function inMedicalsortAppointment() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $id = $_POST['id'];
    $ids = $_POST['ids'];
    $day = $_POST['day'];
    $appoinment = IMD_Appointment::init($id);
    global $wpdb;
    if ($appoinment->appointment->day != $day) {
        $wpdb->update($wpdb->prefix . "imd_appointments", array('day' => $day), array('id' => (int) $id), array('%s'));
    }
    $sort = 0;
    foreach ($ids as $id) {
        $sort++;
        $update = $wpdb->update($wpdb->prefix . "imd_appointments", array('sort' => $sort), array('id' => (int) $id), array('%d'));
    }

    $return = array('success' => true);
    echo json_encode($return);
    exit;
}

/**
 * Delete multiple Extrafield on list
 */
function inMedicalDeleteExtras() {
    $utility = new inMedicalUtility();
    $session = new Inwave_Session();
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $extra = new inMedicalExtra();
        $ids = $_POST['fields'];
        $msg = $extra->deleteMedicalExtras($ids);
        if (isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage($msg['error'] . $msg['success']), 'notice');
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage($msg['error'], 'error'));
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage($msg['success']));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Unknown error', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=indepartment&page=extrafields'));
}

if (!function_exists('loadDoctorOptions')) {

    function loadDoctorOptions() {
        check_ajax_referer('iwm-security', 'ajax_nonce');
        $dep = sanitize_text_field($_POST['department']);
        $doctor_val = sanitize_text_field($_POST['doctor']);
        $department = new inMediacalDepartment();
        $doctors = $department->getDepartmentDoctors($dep);
        $html = array();
        $html[] = '<option value="">' . __('Select doctor', 'inwavethemes') . '</option>';
        if ($doctors) {
            foreach ($doctors as $doctor) {
                $html[] = '<option ' . ($doctor->ID == $doctor_val ? 'selected' : '') . ' value="' . $doctor->ID . '">' . $doctor->post_title . '</option>';
            }
        }
        echo implode($html);
        exit;
    }

}

if (! function_exists('chooseDateBookingAppointment')){
	function chooseDateBookingAppointment(){
		check_ajax_referer( 'iwm-security', 'ajax_nonce' );
		$date_book = sanitize_text_field($_POST['date_canbook']);
		$all_appointments = IMD_Appointment::get_all_appointments();
		if (!$all_appointments){
			echo json_encode(array(
				'success' => false,
			));
			exit;
		}
		$appointment_available = new stdClass();
		$myArray = array();
		foreach ( $all_appointments as $key => $all_appointment ) {
			$appointment_1 = IMD_Appointment::init($all_appointment);
			$canbook = $appointment_1->can_book($date_book);
			if ($canbook){
				/*$appointment_available->id = $all_appointment->id;
				$appointment_available->title = $all_appointment->title;
				$appointment_available->day = $all_appointment->day;
				$appointment_available->slot = $all_appointment->slot;*/
				$myArray[] = array(
					'id' => $all_appointment->id,
					'title' => $all_appointment->title,
					'day' => $all_appointment->day,
					'slot' => $all_appointment->slot,
				);
			}

		}
		echo json_encode(array(
			'success' => true,
			'data1' => json_encode($myArray),
		));
		exit;
	}
}

if ( ! function_exists( 'newBookingAppointment' ) ) {
	function newBookingAppointment() {
		check_ajax_referer( 'iwm-security', 'ajax_nonce' );
		$appointment_id = sanitize_text_field( $_POST['appointment'] );
		$appointment    = IMD_Appointment::init( $appointment_id );
		$doctor = new inMediacalDoctor();
		$doctor_info = $doctor->getDoctorInformation($appointment->get_doctor());
		$time           = $appointment->get_time_range();
		$date_start           = $appointment->get_date_start();
		$date_end           = $appointment->get_date_end();
		if (!empty($date_start) && $date_start > 0){
			$real_date_start = date(get_option('date_format'),$date_start);
		}else{
			$real_date_start = 'Unset';
		}
		if (!empty($date_end) && $date_end > 0){
			$real_date_end = date(get_option('date_format'),$date_end);
		}else{
			$real_date_end = 'Unlimited';
		}
		echo json_encode(
			array(
				'success' => true,
				'doctor' => $doctor_info->title,
				'time' => $time,
				'date_start' => $real_date_start,
				'date_end' => $real_date_end,
			)
		);
		exit;
	}
}

function inMedicalAppointmentRenderPage() {
    //$appointment = new IMD_Appointment();
    $utility = new inMedicalUtility();
    include_once 'views/appointments.php';
}

function inMedicalBookRenderPage() {
    $filter = '';
    $orderby = '';
    $request = filter_input_array(INPUT_GET);
    if (isset($request['status']) && $request['status']) {
        if ($filter) {
            $filter .= ' AND status=' . $request['status'];
        } else {
            $filter .= ' status=' . $request['status'];
        }
    }
    if (isset($request['event']) && $request['event']) {
        if ($filter) {
            $filter .= ' AND o.event_post=' . $request['event'];
        } else {
            $filter .= ' o.event_post=' . $request['event'];
        }
    }
    if (isset($request['keyword']) && $request['keyword']) {
        if ($filter) {
            $filter .= ' AND (o.appointment_reason LIKE \'%' . htmlspecialchars($request['keyword']) . '%\' OR o.first_name LIKE \'%' . htmlspecialchars($request['keyword']) . '%\' OR p.post_title LIKE \'%' . htmlspecialchars($request['keyword']) . '%\' OR o.last_name LIKE \'%' . htmlspecialchars($request['keyword']) . '%\') AND p.post_type=\'inmedical\'';
        } else {
            $filter .= '  (o.appointment_reason LIKE \'%' . htmlspecialchars($request['keyword']) . '%\' OR first_name LIKE \'%' . htmlspecialchars($request['keyword']) . '%\' OR p.post_title LIKE \'%' . htmlspecialchars($request['keyword']) . '%\' OR o.last_name LIKE \'%' . htmlspecialchars($request['keyword']) . '%\') AND p.post_type=\'inmedical\'';
        }
    }
    if (isset($request['date_from']) && $request['date_from']) {
        if ($filter) {
            $filter .= ' AND o.appointment_date >=' . $request['date_from'];
        } else {
            $filter .= ' o.appointment_date >=' . $request['date_from'];
        }
    }
    if (isset($request['date_to']) && $request['date_to']) {
        if ($filter) {
            $filter .= ' AND o.appointment_date <=' . $request['date_to'];
        } else {
            $filter .= ' o.appointment_date <=' . $request['date_to'];
        }
    }

    if (isset($request['orderby']) && $request['orderby']) {
        switch ($request['orderby']) {
            case 'event_post':
                $orderby .= ' ORDER BY p.post_title ';
                break;
            case 'date':
                $orderby .= ' ORDER BY o.appointment_date ';
                break;
            case 'cus_name':
                $orderby .= ' ORDER BY o.last_name ';
                break;
            default:
                $orderby .= ' ORDER BY o.' . $request['orderby'] . ' ';
                break;
        }
        $orderby .= ($request['dir'] ? $request['dir'] : 'asc');
    } else {
        $orderby .= ' ORDER BY o.id desc';
    }

    $server_data = $_SERVER;
    parse_str($server_data['QUERY_STRING'], $server_data['query']);
    unset($server_data['query']['dir']);
    unset($server_data['query']['orderby']);
    $order_link = $server_data['REQUEST_SCHEME'] . '://' . $server_data['HTTP_HOST'] . $server_data['SCRIPT_NAME'] . '?' . http_build_query($server_data['query']);

    $appointment = new inMedicalBookingEvent();
    $paging = new iwPaging();
    $utility = new inMedicalUtility();
    $start = $paging->findStart(IMD_LIMIT_ITEMS);
    $count = $appointment->getCountBookings(filter_input(INPUT_GET, 'keyword'));
    $pages = $paging->findPages($count, IMD_LIMIT_ITEMS);
    $bookings = $appointment->getBookings($start, IMD_LIMIT_ITEMS, $filter, $orderby);
    $order_dir = (filter_input(INPUT_GET, 'dir') == 'asc');
    $sorted = filter_input(INPUT_GET, 'orderby');
    include_once 'views/booking.list.php';
}

function inMedicalBookingViewRenderPage() {
    $appointment = new inMedicalBookingEvent();
    $session = new Inwave_Session();
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $utility = new inMedicalUtility();
    if ($id) {
        $appointment = $appointment->getAppointment($id);
        if (!$appointment->getId()) {
            $session->set('inwave_message', $utility->getMessage(sprintf(__('No appointment found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice'));
        } else {
            include_once 'views/booking.edit.php';
        }
    }
}

function inMedicalSaveBookingOrder() {
    $session = new Inwave_Session();
    $utility = new inMedicalUtility();
    $booking = new inMedicalBookingEvent();
    $booking->setId(sanitize_text_field($_POST['id']));
    $booking->setAddress(sanitize_text_field($_POST['address']));
    $booking->setDate_of_birth(sanitize_text_field($_POST['dob']));
    $booking->setEmail(sanitize_text_field($_POST['email']));
    $booking->setPhone(sanitize_text_field($_POST['phone']));
    $booking->setFirst_name(sanitize_text_field($_POST['first_name']));
    $booking->setLast_name(sanitize_text_field($_POST['last_name']));
    $booking->setGender(sanitize_text_field($_POST['gender']));
    $booking->setAppointment_reason(sanitize_textarea_field($_POST['reason']));
    $booking->setStatus($_POST['status']);
    $up = unserialize($booking->editBooking($booking));
    if ($up['success']) {
        if ($booking->getStatus() == '1') {
            IMD_Email::sendMail('accept_booking_event', $booking->getId(), 'event');
        }
        if ($booking->getStatus() == '2') {
            global $ime_data;
            $ime_data['reason'] = sanitize_textarea_field($_POST['cancel_reason']);
            IMD_Email::sendMail('cancel_booking_event', $booking->getId(), 'event');
        }
        $session->set('inwave_message', $utility->getMessage(__('Update booking success', 'inwavethemes')));
    } else {
        $session->set('inwave_message', $up['msg']);
    }
    wp_redirect(admin_url('edit.php?post_type=indepartment&page=booking/edit&id=' . $booking->getId()));
}

function bookingEventAccept() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $id = sanitize_text_field($_POST['id']);
    $appointment = new inMedicalBookingEvent();
    $up = $appointment->changeBookingStatus($id);
    if ($up) {
        IMD_Email::sendMail('accept_booking_event', $id, 'event');
        echo '<span class="accepted">' . __('Accepted', 'inwavethemes') . '</span>';
    } else {
        echo '<span data-id="' . $id . '" title="' . __('Click to quick accept', 'inwavethemes') . '" class="pendding">' . __('Accept now', 'inwavethemes') . '</span>';
    }
    exit;
}

function inmedicalAdminMainMenu() {
    $classes = 'inmedical-admin-menu';
    $menu_items = apply_filters('inmedical_admin_menu', array());
    $screen = get_current_screen();
    $plugin = get_plugin_data(plugin_dir_path(dirname(__FILE__)) . 'inmedical.php');
    if ($screen->parent_file == 'edit.php?post_type=indepartment'):
        echo '<div class="wrap">';
        ?>
        <div class="inmedical iw-inmedical-admin">
            <div class="inmedical-icon"><span class="fa fa-hospital-o fa-3x"></span></div>
            <div class="inmedical-title"><?php _e('InMedical - Medical & Hopital Managerment Plugin', 'inwavethemes'); ?></div>
            <div class="inmedical-version"><?php printf(__('You are using version %s', 'inwavethemes'), $plugin['Version']) ?></div>
        </div>
        <?php
        if (!empty($menu_items)):
            ?>
            <div id="inmedical-admin-menu" class="<?php echo esc_attr($classes); ?>">
                <ul class="menu-items">
                    <?php
                    foreach ($menu_items as $item) {
                        echo '<li id="' . $item['id'] . '" class="menu-item ' . $item['class'] . ' ' . ($screen->id == $item['id'] ? 'active' : '') . '">';
                        echo '<a href="' . $item['link'] . '">' . ($item['icon'] ? '<span class="fa fa-' . $item['icon'] . '"></span>' : '') . $item['title'] . '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
                <?php
                if (!empty($menu_items[$screen->id]['items'])) {
                    echo '<ul class="sub-menu-item">';
                    foreach ($item['items'] as $key => $sub) {
                        echo '<li class="tab-item ' . $sub['class'] . ' ' . ($key == 0 ? 'active' : '') . '">' . $sub['title'] . '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            <?php
        endif;
        echo '</div>';
    endif;
}

function inmedicalFilterAdminMenu($menu_items) {
    $menu_items['edit-indepartment'] = array(
        'title' => __('Departments'),
        'link' => admin_url('edit.php?post_type=indepartment'),
        'class' => 'department-page',
        'id' => 'edit-indepartment',
        'icon' => 'medkit',
        'items' => array()
    );
    $menu_items['edit-indoctor'] = array(
        'title' => __('Doctors'),
        'link' => admin_url('edit.php?post_type=indoctor'),
        'class' => 'doctor-page',
        'id' => 'edit-indoctor',
        'icon' => 'user-md',
        'items' => array()
    );
    $menu_items['indepartment_page_appointments'] = array(
        'title' => __('Appointments'),
        'link' => admin_url('edit.php?post_type=indepartment&page=appointments'),
        'class' => 'appointment-page',
        'id' => 'indepartment_page_appointments',
        'icon' => 'calendar',
        'items' => array()
    );
    $menu_items['edit-imd_bapm'] = array(
        'title' => __('Appointment Order'),
        'link' => admin_url('edit.php?post_type=imd_bapm'),
        'class' => 'appointment-order',
        'id' => 'edit-imd_bapm',
        'icon' => 'check-square-o',
        'items' => array()
    );
    $menu_items['edit-inmedical'] = array(
        'title' => __('Events'),
        'link' => admin_url('edit.php?post_type=inmedical'),
        'class' => 'event-page',
        'id' => 'edit-inmedical',
        'icon' => 'ticket',
        'items' => array()
    );
    $menu_items['indepartment_page_bookings'] = array(
        'title' => __('Event Orders'),
        'link' => admin_url('edit.php?post_type=indepartment&page=bookings'),
        'class' => 'booking-page',
        'id' => 'indepartment_page_bookings',
        'icon' => 'check-square',
        'items' => array()
    );
    $menu_items['indepartment_page_extrafields'] = array(
        'title' => __('Extrafields'),
        'link' => admin_url('edit.php?post_type=indepartment&page=extrafields'),
        'class' => 'extrafield-page',
        'id' => 'indepartment_page_extrafields',
        'icon' => 'bars',
        'items' => array()
    );
    $menu_items['indepartment_page_settings'] = array(
        'title' => __('Settings'),
        'link' => admin_url('edit.php?post_type=indepartment&page=settings'),
        'class' => 'setting-page',
        'id' => 'indepartment_page_settings',
        'icon' => 'cog',
        'items' => array(
            array(
                'title' => __('General', 'inwavethemes'),
                'callback' => 'general_settings_page',
                'icon' => '',
                'id' => 'general',
                'class' => ''
            ),
            array(
                'title' => __('Appointment Mail Template', 'inwavethemes'),
                'callback' => 'appointment_mail_template_settings_page',
                'icon' => '',
                'id' => 'appointment-mail-template',
                'class' => ''
            ),
            array(
                'title' => __('Event Mail Template', 'inwavethemes'),
                'callback' => 'event_mail_template_settings_page',
                'icon' => '',
                'id' => 'event-mail-template',
                'class' => ''
            )
        )
    );

    return apply_filters('imd_admin_menu', $menu_items);
}

function inmedical_end_tab_content() {
    echo '</div></div>';
}

function inmedical_tab_content() {
    echo '</div><div class="tab-item">';
}

function inmedical_start_tab_content() {
    echo '<div id="imd-setting-tab" class="imd-setting-tab"><div id="imd-setting-general" class="tab-item">';
}

function getIwMapFieldHtml($fname, $fvalue, $class) {
    global $inf_settings;
    if ($fvalue) {
        $mapoptions = json_decode($fvalue);
    }
    ob_start();
    ?>
    <div class="infunding-map infuding-map-container">
        <div class="infuding-map-wrap">
            <div class="map-preview" style="height:350px;">
            </div>
        </div>
        <div class="description">
            <ul>
                <li><?php _e('- Click on map to set map position.', 'inwavethemes'); ?></li>
                <li><?php _e('- Drag and Drop marker to set map position.', 'inwavethemes'); ?></li>
                <li><?php _e('- ZoomIn or ZoomOut to change and set map Zoom Level.', 'inwavethemes'); ?></li>
            </ul>
        </div>
        <input type="hidden" value="<?php echo $fvalue; ?>" class="iw-map <?php echo $class; ?>" name="<?php echo $fname; ?>"/>
    </div>
    <script type="text/javascript">
        (function () {
            var mapProperties = {
                zoom: <?php echo isset($mapoptions->zoomlv) ? $mapoptions->zoomlv : (isset($inf_settings['general']['map_zoom_lever']) ? $inf_settings['general']['map_zoom_lever'] : '8'); ?>,
                center: new google.maps.LatLng(<?php echo (isset($mapoptions->lat) ? $mapoptions->lat : -33.8665433) . ', ' . (isset($mapoptions->lng) ? $mapoptions->lng : 151.1956316); ?>),
                zoomControl: true,
                scrollwheel: true,
                disableDoubleClickZoom: true,
                draggable: true,
                panControl: true,
                mapTypeControl: true,
                scaleControl: true,
                overviewMapControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            setTimeout(function () {
                jQuery('.infunding-map').iwMap(mapProperties);
            }, 1000);
        })();
    </script>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function inmedical_get_doctor($doctor_id){

}
