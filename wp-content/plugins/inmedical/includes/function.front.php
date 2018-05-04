<?php

/*
 * @package Inwave Directory
 * @version 1.0.0
 * @created Mar 2, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of file: File contain all function to process in front page
 *
 * @developer duongca
 */
require_once 'utility.php';
if (!function_exists('inmedical_timetable_outhtml')) {

    function inmedical_timetable_outhtml($atts) {
        extract(shortcode_atts(array(
                        ), $atts));
        ob_start();
        $path = inMedicalGetTemplatePath('inmedical/timetable');
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'timetable.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}

if (!function_exists('inmedical_event_listing_outhtml')) {

    function inmedical_event_listing_outhtml($atts) {
        extract(shortcode_atts(array(
            "post_number" => "5",
            "css" => "",
            "class" => ""
                        ), $atts));
        ob_start();
        $path = inMedicalGetTemplatePath('inmedical/event_listing');
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'event_listing.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}

if (!function_exists('inmedical_upcomming_event_outhtml')) {

    function inmedical_upcomming_event_outhtml($atts) {
        extract(shortcode_atts(array(
            "post_number" => "5",
            "item_desktop" => "3",
            "item_desktop_small" => "2",
            "auto_play" => "false",
            "show_navigation" => "false",
            "css" => "",
            "class" => ""
                        ), $atts));
        ob_start();
        $path = inMedicalGetTemplatePath('inmedical/upcomming_event');
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'upcomming_event.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}

if (!function_exists('inmedical_doctors_outhtml')) {

    function inmedical_doctors_outhtml($atts) {
        extract(shortcode_atts(array(
            'ids' => '',
            'departments' => '',
            'item_per_page' => 10,
            'order_by' => 'ID',
            'order_dir' => 'DESC',
            'show_paging' => 1,
            'show_filter' => 1,
            'desc_text_limit' => 20,
            "number_column" => '3',
            'style' => 'grid',
            "item_desktop" => "3",
            "item_desktop_small" => "2",
            "auto_play" => "false",
            "show_navigation" => "false",
            "css" => "",
            "class" => ""
                        ), $atts));
        ob_start();
        $doctor = new inMediacalDoctor();
        $utility = new inMedicalUtility();
        $paging = new iwPaging();
        $current_page = isset( $_GET['pagenum'] ) ? $_GET['pagenum'] : 1;
        $doctors = $doctor->getDoctors($ids, $departments, $order_by, $order_dir, $item_per_page, 'pagenum');
        $path = inMedicalGetTemplatePath('inmedical/doctors_' . $style);
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'doctors_' . $style . '.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}
if (!function_exists('inmedical_book_ticket_form_outhtml')) {

    function inmedical_book_ticket_form_outhtml($atts) {
        extract(shortcode_atts(array(
            'style' => 'default',
            'event' => '',
            'event_date' => '',
                        ), $atts));
        ob_start();
        $path = inMedicalGetTemplatePath('inmedical/book_ticket_form_' . $style);
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'book_ticket_form_' . $style . '.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}
if (!function_exists('inmedical_departments_outhtml')) {

    function inmedical_departments_outhtml($atts) {
        extract(shortcode_atts(array(
//            'ids'=>'',
//            'departments' => '',
            'style' => 'grid',
            'item_department_per_page' => -1,
//            'show_paging'=>1,
//            'show_filter'=>1,
			"order_by"=>"ID",
			"order_dir"=>"DESC",
            "desc_text_limit" => '15',
            "number_column" => '3',
            "item_desktop" => "3",
            "item_desktop_small" => "2",
            "auto_play" => "false",
            "style_navigation" => "",
            'link_all' => '',
            'link_all_text' => '',
            'height_item' => '',
            "css" => "",
            "class" => ""
                        ), $atts));
        ob_start();
        if ($style == 'grid_v2') {
            $item_department_per_page = 6;
        }
        $indepartment = new inMediacalDepartment();
        $departments = $indepartment->getDepartments($item_department_per_page, $order_by, $order_dir);
        $path = inMedicalGetTemplatePath('inmedical/departments_' . $style);
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'departments_' . $style . '.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}
if (!function_exists('inmedical_appointments_shortcode')) {

    function inmedical_appointments_shortcode($atts) {
        extract(shortcode_atts(array(
            "department_ids" => "",
            "doctor_ids" => "",
            "class" => ""
                        ), $atts, 'inmedical_appointments'));
        ob_start();

        $department_ids = $department_ids ? explode(",", $department_ids) : array();
        $doctor_ids = $doctor_ids ? explode(",", $doctor_ids) : array();

        $path = inMedicalGetTemplatePath('inmedical/appointments');
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'appointments.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}

if (!function_exists('inmedical_appointments_scroll_vertical_shortcode')) {

    function inmedical_appointments_scroll_vertical_shortcode($atts) {
        extract(shortcode_atts(array(
            "department_ids" => "",
            "doctor_ids" => "",
            "number_days_show" => "",
            "number_items_show" => "",
            "item_per_page" => "",
            "class" => ""
                        ), $atts, 'inmedical_appointments_scroll_vertical'));
        ob_start();


        $path = inMedicalGetTemplatePath('inmedical/appointments_scroll_vertical');
        if ($path) {
            include $path;
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'appointments_scroll_vertical.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo __('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}

function inMedicalAddSiteScript() {
    global $imd_settings;
    wp_enqueue_style('font-awesome', plugins_url('/inmedical/assets/css/font-awesome/css/font-awesome.min.css'));
    wp_enqueue_style('custombox', plugins_url('/inmedical/assets/css/custombox.min.css'));
    wp_enqueue_style('iw-legacy', plugins_url('/inmedical/assets/css/iw-legacy.css'));
    wp_enqueue_style('imdsite-style', plugins_url('/inmedical/assets/css/inmedical_style.css'));
    wp_enqueue_style('datetimepicker', plugins_url('/inmedical/assets/css/jquery.datetimepicker.css'));
    wp_enqueue_style('owl-carousel', plugins_url('/inmedical/assets/css/owl.carousel.css'));
    wp_enqueue_style('owl-theme', plugins_url('/inmedical/assets/css/owl.theme.css'));
    wp_enqueue_style('owl-transitions', plugins_url('/inmedical/assets/css/owl.transitions.css'));
    wp_enqueue_style('schedule-table', plugins_url('/inmedical/assets/css/schedule_table.css'));

    if(class_exists('Inwave_Helper')){
        $inwave_theme_option = Inwave_Helper::getConfig();
        wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $inwave_theme_option['google_api'] . '&libraries=places', array('jquery'), '1.0.0', true);
    }

    wp_enqueue_script('isotope-min', plugins_url('/inmedical/assets/js/isotope.pkgd.min.js'), array());
    wp_register_script('owl-carousel', plugins_url('/inmedical/assets/js/owl.carousel.min.js'), array('jquery'));
    wp_register_script('spin', plugins_url('/inmedical/assets/js/spin.min.js'), array('jquery'));
    wp_register_script('jquery-validate', plugins_url('/inmedical/assets/js/jquery.validate.min.js'), array('jquery'));
    wp_register_script('jcarousellite', plugins_url('/inmedical/assets/js/jquery.jcarousellite.min.js'), array());
    wp_enqueue_script('datetimepicker', plugins_url('/inmedical/assets/js/jquery.datetimepicker.full.min.js'), array());
    wp_register_script('imdsite-script', plugins_url('/inmedical/assets/js/inmedical_script.js'), array(), null, true);
    wp_register_script('imdmap-script', plugins_url('/inmedical/assets/js/inmedical_map.js'), array(), null, true);
    wp_enqueue_script('schedule-table-script', plugins_url('/inmedical/assets/js/schedule_table.js'), array(), null, true);
    wp_register_script('custombox', plugins_url('/inmedical/assets/js/custombox.min.js'), array());
    wp_localize_script('imdsite-script', 'inMedicalCfg', array('siteUrl' => site_url(), 'adminUrl' => admin_url(), 'ajaxUrl' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce( "iwm-security" )));
    wp_enqueue_script('imdsite-script');
}

function inMedicalGetTemplatePath($name) {
    $parent_path = get_template_directory();
    $path = $parent_path . '/' . $name . '.php';
    if (get_stylesheet_directory() != get_template_directory()) {
        //Theme child active
        $child_path = get_stylesheet_directory();
        $file_path = $child_path . '/' . $name . '.php';
        if (file_exists($file_path)) {
            $path = $file_path;
        }
    }
    if (file_exists($path)) {
        return $path;
    } else {
        return false;
    }
}

function inMedicalSubmitForm() {
    $action = filter_input(INPUT_POST, 'action');
    if ($action) {
        switch ($action) {
            case 'imdSubmitBookingTicket':
                imdSubmitBookingTicket();
                exit;
                break;
            default:
                break;
        }
    }
}

function imdSubmitBookingTicket() {
    global $imd_settings;
    $session = new Inwave_Session();
    $utility = new inMedicalUtility();
    $app = new inMedicalBookingEvent();
    if (isset($imd_settings['general']['auto_accept_booking_event']) && $imd_settings['general']['auto_accept_booking_event']) {
        $app->setStatus(1);
    } else {
        $app->setStatus(3);
    }

    $app->setAddress(sanitize_text_field($_POST['address']));
    $app->setAppointment_date(sanitize_text_field($_POST['event_date']));
    $app->setAppointment_reason(sanitize_text_field($_POST['appointment_reason']));
    $app->setDate_of_birth(sanitize_text_field($_POST['dob']));
    $app->setDepartment_post(sanitize_text_field($_POST['department']));
    $app->setDoctor_post(sanitize_text_field($_POST['doctor']));
    $app->setEmail(sanitize_email($_POST['email']));
    $app->setEvent_post(sanitize_text_field($_POST['event']));
    $app->setFirst_name(sanitize_text_field($_POST['first_name']));
    $app->setGender(sanitize_text_field($_POST['gender']));
    $app->setLast_name(sanitize_text_field($_POST['last_name']));
    $app->setPhone(sanitize_text_field($_POST['phone']));
    $ins = unserialize($app->addBooking($app));
    if ($ins['success']) {
        IMD_Email::sendMail('new_booking_event', $ins['data'], 'event');
        IMD_Email::sendMail('admin_new_booking_event', $ins['data'], 'event');
        $session->set('inwave_message', $utility->getMessage(__('Your booking ticket was sent, we will contact you soon.', 'inwavethemes')));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Your booking can\'t send, please try again or contact website admin.', 'inwavethemes'), 'error'));
    }
    wp_redirect($_SERVER['HTTP_REFERER']);
}

function imd_get_appointments() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $date = $_POST['date'];
    if ($date) {
        $day = date('D', $date);
        $appointments = IMD_Appointment::get_appointments($day, $date, $date + 86400);
        if ($appointments)
            ob_start();
        if (inMedicalGetTemplatePath('inmedical/appointment_available_list')) {
            include inMedicalGetTemplatePath('inmedical/appointment_available_list');
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'appointment_available_list.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo esc_html__('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_clean();

        $return = array('success' => true, 'html' => $html);
    } else {
        $return = array('success' => false, 'html' => '');
    }

    echo json_encode($return);
    exit;
}

function imd_get_appointment_form() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $date = $_POST['date'];
    $appointment_id = $_POST['appointment_id'];
    if ($date && $appointment_id) {
        $appointment = IMD_Appointment::init($appointment_id);
        //check can book
        if ($appointment->can_book($date)) {
            ob_start();
            if (inMedicalGetTemplatePath('inmedical/appointment_request_form')) {
                include inMedicalGetTemplatePath('inmedical/appointment_request_form');
            } else {
                $imd_theme = INMEDICAL_THEME_PATH . 'appointment_request_form.php';
                if (file_exists($imd_theme)) {
                    include $imd_theme;
                } else {
                    echo esc_html__('No theme was found', 'inwavethemes');
                }
            }
            $html = ob_get_clean();
            $return = array('success' => true, 'html' => $html);
            echo json_encode($return);
            exit;
        }
    }

    $return = array('success' => false, 'html' => '');
    echo json_encode($return);

    exit;
}

function imd_request_appointment() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $data = $_POST;
    $data['first_name'] = sanitize_text_field($_POST['first_name']);
    $data['last_name'] = sanitize_text_field($_POST['last_name']);
    $data['email'] = sanitize_email($_POST['email']);
    $data['phone'] = sanitize_text_field($_POST['phone']);
    $data['message'] = sanitize_text_field($_POST['message']);
    $data['age'] = sanitize_text_field($_POST['age']);
    $data['gender'] = sanitize_text_field($_POST['gender']);
    $data['address'] = sanitize_text_field($_POST['address']);
    $data['appointment_id'] = sanitize_text_field($_POST['appointment_id']);
    $data['date'] = sanitize_text_field($_POST['date']);

    $data = apply_filters('imd_request_appointment_data', $data);

    //check
    $validate = apply_filters('imd_validate_request_appointment', false, $data);
    if($validate === false){
        if (!$data['first_name'] || !$data['last_name'] || !$data['email'] || !$data['phone']) {
            echo json_encode(array(
                'success' => false,
                'message' => __('Please fill out all required fields.', 'inwavethemes'),
            ));
            exit;
        }

        $appointment = IMD_Appointment::init($data['appointment_id']);
        if (!$appointment) {
            echo json_encode(array(
                'success' => false,
                'message' => __('Please select an appointment.', 'inwavethemes'),
            ));
            exit;
        }

        if (!$appointment->can_book($data['date'])) {
            echo json_encode(array(
                'success' => false,
                'message' => __('Sorry, can book this appoinment please refresh the page and try again.', 'inwavethemes'),
            ));
            exit;
        }
    }elseif($validate !== true){
        echo json_encode(array(
            'success' => false,
            'message' => $validate,
        ));
    }

    $booked_id = IMD_Booking_Appointment::add_new($data);
    if ($booked_id) {
        //do_action('imd_after_request_appointment', $booked_id, $data);
        ob_start();
        if (inMedicalGetTemplatePath('inmedical/appointment_thanks')) {
            include inMedicalGetTemplatePath('inmedical/appointment_thanks');
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'appointment_thanks.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo esc_html__('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_clean();

        $return = array('success' => true, 'html' => $html);
    } else {
        $return = array('success' => false, 'html' => '');
    }

    echo json_encode($return);
    exit;
}

function imd_booked_next_month() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $current_date = $_POST['current_date'];
    $year = date('Y', $current_date);
    $month = date('n', $current_date);
    if ($current_date) {
        $newdate = strtotime('first day of +1 month', strtotime("{$year}-{$month}-01"));
        $year = date('Y', $newdate);
        $month = date('n', $newdate);
        ob_start();
        if (inMedicalGetTemplatePath('inmedical/appointments_body')) {
            include inMedicalGetTemplatePath('inmedical/appointments_body');
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'appointments_body.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo esc_html__('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_clean();

        echo json_encode(array('success' => true, 'html' => $html, 'date_title' => date("F", $newdate) . ' ' . $year, 'current_date' => $newdate));
        exit;
    }

    echo json_encode(array('success' => false, 'html' => ''));
    exit;
}

function imd_booked_prev_month() {
    check_ajax_referer('iwm-security', 'ajax_nonce');
    $current_date = $_POST['current_date'];
    $original_date = $_POST['original_date'];
    $year = date('Y', $current_date);
    $month = date('n', $current_date);
    if ($current_date) {
        $newdate = strtotime('first day of -1 month', strtotime("{$year}-{$month}-01"));
        $year = date('Y', $newdate);
        $month = date('n', $newdate);
        ob_start();
        if (inMedicalGetTemplatePath('inmedical/appointments_body')) {
            include inMedicalGetTemplatePath('inmedical/appointments_body');
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'appointments_body.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo esc_html__('No theme was found', 'inwavethemes');
            }
        }
        $html = ob_get_clean();

        $prev_disable = (date('Y', $original_date) == $year && date('n', $original_date) == $month) ? true : false;
        echo json_encode(array('success' => true, 'html' => $html, 'date_title' => date("F", $newdate) . ' ' . $year, 'current_date' => $newdate, 'prev_disable' => $prev_disable));
        exit;
    }

    echo json_encode(array('success' => false, 'html' => ''));
    exit;
}

function imd_get_day_off_work() {
    global $imd_settings;
    if (isset($imd_settings['general']['day_off_work']) && $imd_settings['general']['day_off_work']) {
        return (array) $imd_settings['general']['day_off_work'];
    }
    return array();
}
