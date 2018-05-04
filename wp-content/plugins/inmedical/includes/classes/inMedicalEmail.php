<?php

if (!defined('ABSPATH'))
    exit;

class IMD_Email {

    static function register_email_shortcodes() {
        add_shortcode('ime_site_name', array(__CLASS__, 'site_name_shortcode'));
        add_shortcode('ime_site_url', array(__CLASS__, 'site_url_shortcode'));
        add_shortcode('ime_admin_email', array(__CLASS__, 'admin_email_shortcode'));
        add_shortcode('ime_booking_id', array(__CLASS__, 'booking_id_shortcode'));
        add_shortcode('ime_booking_edit_link', array(__CLASS__, 'booking_edit_link_shortcode'));
        add_shortcode('ime_first_name', array(__CLASS__, 'first_name_shortcode'));
        add_shortcode('ime_last_name', array(__CLASS__, 'last_name_shortcode'));
        add_shortcode('ime_email', array(__CLASS__, 'email_shortcode'));
        add_shortcode('ime_phone', array(__CLASS__, 'phone_shortcode'));
        add_shortcode('ime_age', array(__CLASS__, 'age_shortcode'));
        add_shortcode('ime_gender', array(__CLASS__, 'gender_shortcode'));
        add_shortcode('ime_address', array(__CLASS__, 'address_shortcode'));
        add_shortcode('ime_reason', array(__CLASS__, 'reason_shortcode'));
        add_shortcode('ime_message', array(__CLASS__, 'message_shortcode'));
        add_shortcode('ime_department', array(__CLASS__, 'department_shortcode'));
        add_shortcode('ime_doctor', array(__CLASS__, 'doctor_shortcode'));
        add_shortcode('ime_doctor_email', array(__CLASS__, 'doctor_email_shortcode'));
        add_shortcode('ime_doctor_phone', array(__CLASS__, 'doctor_phone_shortcode'));
        add_shortcode('ime_date', array(__CLASS__, 'date_shortcode'));
    }

    static function site_name_shortcode($atts) {
        return get_bloginfo('name');
    }

    static function site_url_shortcode($atts) {
        return network_site_url('/');
    }

    static function admin_email_shortcode($atts) {
        return get_bloginfo('admin_email');
    }

    static function booking_id_shortcode($atts) {
        global $ime_data;
        return isset($ime_data['booking_id']) ? '#' . $ime_data['booking_id'] : '';
    }

    static function booking_edit_link_shortcode($atts) {
        global $ime_data;
        if (isset($ime_data['booking_id'])) {
            if ($ime_data['booking_type'] == 'appointment') {
                return '<a href="' . get_edit_post_link($ime_data['booking_id']) . '">' . get_the_title($ime_data['booking_id']) . '</a>';
            } else {
                return '<a href="' . admin_url('edit.php?post_type=indepartment&page=booking/edit&id=' . $ime_data['booking_id']) . '">Booking #' . $ime_data['booking_id'] . '</a>';
            }
        } else {
            return '';
        }
    }

    static function first_name_shortcode() {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['first_name'])) {
            return '';
        }
        return $ime_data['first_name'];
    }

    static function last_name_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['last_name'])) {
            return '';
        }
        return $ime_data['last_name'];
    }

    static function email_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['email'])) {
            return '';
        }
        return $ime_data['email'];
    }

    static function phone_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['phone'])) {
            return '';
        }
        return $ime_data['phone'];
    }

    static function age_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['age'])) {
            return '';
        }
        return $ime_data['age'];
    }

    static function address_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['address'])) {
            return '';
        }
        return $ime_data['address'];
    }

    static function message_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['message'])) {
            return '';
        }
        return $ime_data['message'];
    }

    static function gender_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['gender'])) {
            return '';
        }
        return $ime_data['gender'];
    }

    static function reason_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['reason'])) {
            return '';
        }
        return $ime_data['reason'];
    }

    static function department_shortcode($atts) {
        $atts = shortcode_atts(array(
            'link' => 'false',
                ), $atts, 'ime_department');

        global $ime_data;
        if (!$ime_data || !isset($ime_data['booking_id'])) {
            return '';
        }

        if ($ime_data['booking_type'] == 'appointment') {
            $booking = IMD_Booking_Appointment::init($ime_data['booking_id']);
            if ($booking) {
                $appointment_id = $booking->get_appointment_id();
                $appointment = IMD_Appointment::init($appointment_id);
                if ($appointment) {
                    $doctor_id = $appointment->get_doctor();
                    $department_id = get_post_meta($doctor_id, 'imd_doctor_info_department', true);
                    if ($department_id) {
                        if ($atts['link'] == 'true') {
                            return '<a href="' . get_permalink($department_id) . '">' . get_the_title($department_id) . '</a>';
                        } else {
                            return get_the_title($department_id);
                        }
                    }
                }
            }
        } else {
            if ($ime_data['department']) {
                if ($atts['link'] == 'true') {
                    return '<a href="' . get_permalink($ime_data['department']) . '">' . get_the_title($ime_data['department']) . '</a>';
                } else {
                    return get_the_title($ime_data['department']);
                }
            }
        }
        return '';
    }

    static function doctor_shortcode($atts) {
        $atts = shortcode_atts(array(
            'link' => 'false',
                ), $atts, 'ime_doctor');

        global $ime_data;
        if (!$ime_data || !isset($ime_data['booking_id'])) {
            return '';
        }

        if ($ime_data['booking_type'] == 'appointment') {
            if ($ime_data['appointment_id']) {
                $appointment = IMD_Appointment::init($ime_data['appointment_id']);
                if ($appointment) {
                    $doctor_id = $appointment->get_doctor();
                    if ($doctor_id) {
                        if ($atts['link'] == 'true') {
                            return '<a href="' . get_permalink($doctor_id) . '">' . get_the_title($doctor_id) . '</a>';
                        } else {
                            return get_the_title($doctor_id);
                        }
                    }
                }
            }
        } else {
            if ($ime_data['doctor']) {
                if ($atts['link'] == 'true') {
                    return '<a href="' . get_permalink($ime_data['doctor']) . '">' . get_the_title($ime_data['doctor']) . '</a>';
                } else {
                    return get_the_title($ime_data['doctor']);
                }
            }
        }

        return '';
    }

    static function doctor_email_shortcode($atts) {

        global $ime_data;
        if (!$ime_data || !isset($ime_data['booking_id'])) {
            return '';
        }
        $doctor_id = 0;
        if ($ime_data['booking_type'] == 'appointment') {
            if ($ime_data['appointment_id']) {
                $appointment = IMD_Appointment::init($ime_data['appointment_id']);
                if ($appointment) {
                    $doctor_id = $appointment->get_doctor();
                }
            }
        } else {
            if ($ime_data['doctor']) {
                $doctor_id = $ime_data['doctor'];
            }
        }

        if($doctor_id){
            return get_post_meta($doctor_id, 'imd_doctor_info_email', true);
        }

        return '';
    }

    static function doctor_phone_shortcode($atts) {
        global $ime_data;
        if (!$ime_data || !isset($ime_data['booking_id'])) {
            return '';
        }
        $doctor_id = 0;
        if ($ime_data['booking_type'] == 'appointment') {
            if ($ime_data['appointment_id']) {
                $appointment = IMD_Appointment::init($ime_data['appointment_id']);
                if ($appointment) {
                    $doctor_id = $appointment->get_doctor();
                }
            }
        } else {
            if ($ime_data['doctor']) {
                $doctor_id = $ime_data['doctor'];
            }
        }

        if($doctor_id){
            return get_post_meta($doctor_id, 'imd_doctor_info_phone', true);
        }

        return '';
    }

    static function date_shortcode($atts) {
        $atts = shortcode_atts(array(
            'fortmat' => '',
                ), $atts, 'ime_date');

        global $ime_data;
        if (!$ime_data || !isset($ime_data['booking_id'])) {
            return '';
        }
        $format = $atts['format'] ? $atts['format'] : get_option('date_format');
        if ($ime_data['booking_type'] == 'appointment') {
            $date = get_post_meta($ime_data['booking_id'], 'imd_date', true);
        } else {
            $date = $ime_data['date'];
        }
        $return = $date ? date($format, $date) : '';
        if ($ime_data['booking_type'] == 'appointment') {
            $booking = IMD_Booking_Appointment::init($ime_data['booking_id']);
            if ($booking) {
                $appointment_id = $booking->get_appointment_id();
                $appointment = IMD_Appointment::init($appointment_id);
                if ($appointment) {
                    $return .= ' ' . $appointment->get_time_range();
                }
            }
        }
        return $return;
    }

    static public function sendMail($type, $booking_id, $booking_type = 'appointment') {
        if ($type && $booking_id) {
            global $imd_settings, $ime_data;

            if ($booking_type == 'appointment') {
                $booking = IMD_Booking_Appointment::init($booking_id);
                $ime_data = array(
                    'first_name' => $booking->get_first_name(),
                    'last_name' => $booking->get_last_name(),
                    'email' => $booking->get_email(),
                    'phone' => $booking->get_phone(),
                    'age' => $booking->get_age(),
                    'address' => $booking->get_address(),
                    'gender' => $booking->get_gender(),
                    'reason' => $booking->get_reason(),
                    'message' => $booking->get_message(),
                    'appointment_id' => $booking->get_appointment_id(),
                );
            } else {
                $event_booking = new inMedicalBookingEvent();
                if (is_numeric($booking_id)) {
                    $booking = $event_booking->getAppointment($booking_id);
                } else {
                    $booking = $booking_id;
                }
                $ime_data['first_name'] = $booking->getFirst_name();
                $ime_data['last_name'] = $booking->getLast_name();
                $ime_data['email'] = $booking->getEmail();
                $ime_data['phone'] = $booking->getPhone();
                $ime_data['doctor'] = $booking->getDoctor_post();
                $ime_data['age'] = $booking->getAge();
                $ime_data['address'] = $booking->getAddress();
                $ime_data['gender'] = $booking->getGender();
                $ime_data['department'] = $booking->getDepartment_post();
                if (!$ime_data['message']) {
                    $ime_data['message'] = $booking->getAppointment_reason();
                }
                $ime_data['date'] = $booking->getAppointment_date();
            }
            $ime_data['booking_id'] = $booking_id;
            $ime_data['booking_type'] = $booking_type;

            $emails = isset($imd_settings['emails']) ? $imd_settings['emails'] : array();

            do_action('imd_before_send_email', $type, $booking_id, $booking_type);

            if (isset($emails[$type]['enable']) && $emails[$type]['enable'] == 1) {
                $from_name = get_bloginfo('name');
                $from_address = get_bloginfo('admin_email');
                //get email doctor
                $appointmentdata = IMD_Appointment::init($ime_data['get_appointment_id']);
                $appointmentdata_doctor_id = $appointmentdata->appointment->doctor;
                $doctor_data = inMediacalDoctor::getDoctorInformation($appointmentdata_doctor_id);
                $doctor_email = $doctor_data->email;
                //end email doctor

                if (strpos($type, 'admin_') === 0) {
                    $recipient = get_bloginfo('admin_email');
                } elseif (strpos($type, 'doctor_') === 0) {
                    $recipient = $doctor_email;
                } else {
                    $recipient = $ime_data['email'];
                }

                $subject = strip_tags(apply_filters('the_content', $emails[$type]['title']));
                $content = apply_filters('the_content', stripslashes($emails[$type]['content']));

                if ($recipient) {
                    $headers = array();
                    $headers[] = 'Content-Type: text/html; charset=UTF-8';
                    $headers[] = 'From: ' . $from_name . ' <' . $from_address . '>';
                    wp_mail($recipient, $subject, $content, $headers);
                }
            }

            do_action('imd_after_send_email', $type, $booking_id, $booking_type);
        }
    }

}

IMD_Email::register_email_shortcodes();
