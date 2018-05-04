<?php

class IMD_Booking_Appointment {

    public $booking;

    function __construct($booking) {
        $this->booking = $booking;
    }

    static function add_meta_box() {
        add_meta_box('inmedical-meta-box', __('Booking info', 'inwavethemes'), array('IMD_Booking_Appointment', 'metabox_html'), 'imd_bapm', 'advanced', 'high');
    }

    static function admin_columns_content($column_name, $post_ID) {
        if ($column_name == 'full_name') {
            echo get_post_meta($post_ID, 'imd_first_name', true) . ' ' . get_post_meta($post_ID, 'imd_last_name', true);
        } elseif ($column_name == 'email') {
            echo get_post_meta($post_ID, 'imd_email', true);
        } elseif ($column_name == 'phone') {
            echo get_post_meta($post_ID, 'imd_phone', true);
        } elseif ($column_name == 'department') {
            $appointment_id = get_post_meta($post_ID, 'imd_appointment_id', true);
            $appointment = IMD_Appointment::init($appointment_id);
            if ($appointment) {
                $doctor_id = $appointment->get_doctor();
                $department_id = get_post_meta($doctor_id, 'imd_doctor_info_department', true);
                if ($department_id) {
                    echo get_the_title($department_id);
                }
            }
        } elseif ($column_name == 'doctor') {
            $appointment_id = get_post_meta($post_ID, 'imd_appointment_id', true);
            $appointment = IMD_Appointment::init($appointment_id);
            if ($appointment) {
                $doctor_id = $appointment->get_doctor();
                if ($doctor_id) {
                    echo get_the_title($doctor_id);
                }
            }
        } elseif ($column_name == 'appoitment_date') {
            $date = get_post_meta($post_ID, 'imd_date', true);
            $appointment_id = get_post_meta($post_ID, 'imd_appointment_id', true);
            $appointment = IMD_Appointment::init($appointment_id);
            if ($appointment) {
                echo date(get_option('date_format'), $date) . ' ' . $appointment->get_time_range();
            }
        } elseif ($column_name == 'status') {
            $status = get_post_meta($post_ID, 'imd_status', true);
            if ($status == 1) {
                echo '<span class="booked-accepted">' . __('Accepted', 'inwavethemes') . '</span>';
            } elseif ($status == -1) {
                echo '<span class="booked-cancelled">' . __('Cancelled', 'inwavethemes') . '</span>';
            } else {
                echo '<span class="booked-not-accepted">' . __('Not Accepted', 'inwavethemes') . '</span>';
            }
        }
    }

    static function admin_search_query($query) {
        $custom_fields = array(
            // put all the meta fields you want to search for here
            "imd_first_name",
            "imd_last_name",
            "imd_email",
            "imd_phone",
        );

        $searchterm = $query->query_vars['s'];

        // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
        $query->query_vars['s'] = "";

        $main_meta_query = array('relation' => 'AND');
        if ($searchterm != "") {
            $meta_query = array('relation' => 'OR');
            foreach ($custom_fields as $cf) {
                array_push($meta_query, array(
                    'key' => $cf,
                    'value' => $searchterm,
                    'compare' => 'LIKE'
                ));
            }
            array_push($main_meta_query, $meta_query);
        };

        if (isset($_GET['book_status']) && $_GET['book_status'] !== '') {
            array_push($main_meta_query, array(
                'key' => 'imd_status',
                'value' => sanitize_text_field($_GET['book_status']),
                'compare' => '='
            ));
        }

        $doctor_ids = array();
        if (isset($_GET['book_doctor']) && $_GET['book_doctor']) {
            $doctor_ids[] = $_GET['book_doctor'];
        } elseif (isset($_GET['book_department']) && $_GET['book_department']) {
            global $wpdb;
            $sql = "SELECT p.ID FROM {$wpdb->posts} AS p JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id WHERE pm.meta_key = 'imd_doctor_info_department' AND pm.meta_value = %s AND p.post_status = 'publish' AND post_type = 'indoctor'";
            $all_doctors = $wpdb->get_results($wpdb->prepare($sql, $_GET['book_department']));
            if ($all_doctors) {
                foreach ($all_doctors as $doctor) {
                    $doctor_ids[] = $doctor->ID;
                }
            }
        }

        if ($doctor_ids) {
            global $wpdb;
            $sql = "SELECT DISTINCT id FROM {$wpdb->prefix}imd_appointments WHERE doctor IN (" . implode(",", $doctor_ids) . ")";
            $appointments = $wpdb->get_results($sql);

            if ($appointments) {
                foreach ($appointments as $appointment) {
                    $appointment_ids[] = $appointment->id;
                }

                array_push($main_meta_query, array(
                    'key' => 'imd_appointment_id',
                    'value' => $appointment_ids,
                    'compare' => 'IN'
                ));
            } else {
                array_push($main_meta_query, array(
                    'key' => 'imd_appointment_id',
                    'value' => array(0),
                    'compare' => 'IN'
                ));
            }
        }

        if (isset($_GET['from_date']) && $_GET['from_date']) {
            array_push($main_meta_query, array(
                'key' => 'imd_date',
                'value' => strtotime($_GET['from_date']),
                'type' => 'NUMERIC',
                'compare' => '>='
            ));
        }

        if (isset($_GET['to_date']) && $_GET['to_date']) {
            array_push($main_meta_query, array(
                'key' => 'imd_date',
                'value' => strtotime($_GET['to_date']),
                'type' => 'NUMERIC',
                'compare' => '<='
            ));
        }

        if (count($main_meta_query) > 1) {
            $query->set("meta_query", $main_meta_query);
        }
    }

    static function restrict_manage_posts($post_type) {
        wp_enqueue_script('jquery-datetimepicker');
        wp_enqueue_style('jquery-datetimepicker');
        $booking_status = isset($_REQUEST['book_status']) ? $_REQUEST['book_status'] : '';
        $book_department = isset($_REQUEST['book_department']) ? $_REQUEST['book_department'] : '';
        $book_doctor = isset($_REQUEST['book_doctor']) ? $_REQUEST['book_doctor'] : '';
        ?>
        <select name="book_status">
            <option value="" <?php selected($booking_status, ''); ?>><?php echo __('Status', 'inwavethemes'); ?></option>
            <option value="1" <?php selected($booking_status, '1'); ?>><?php echo __('Accepted', 'inwavethemes'); ?></option>
            <option value="0" <?php selected($booking_status, '0'); ?>><?php echo __('Not Accepted', 'inwavethemes'); ?></option>
            <option value="-1" <?php selected($booking_status, '-1'); ?>><?php echo __('Cancelled', 'inwavethemes'); ?></option>
        </select>
        <select name="book_department" id="book-department-search-field">
            <option value="" <?php selected($book_department, ''); ?>><?php echo __('Department', 'inwavethemes'); ?></option>
            <?php
            global $wpdb;
            $sql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'indepartment' AND post_status = 'publish'";
            $departments = $wpdb->get_results($sql);
            if ($departments) {
                foreach ($departments as $department) {
                    echo '<option value="' . $department->ID . '" ' . selected($book_department, $department->ID, false) . '>' . $department->post_title . '</option>';
                }
            }
            ?>
        </select>
        <select name="book_doctor" id="book-doctor-search-field">
            <option value="" <?php selected($book_doctor, ''); ?>><?php echo __('Doctor', 'inwavethemes'); ?></option>
            <?php
            global $wpdb;
            $sql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'indoctor' AND post_status = 'publish'";
            $doctors = $wpdb->get_results($sql);
            if ($doctors) {
                foreach ($doctors as $doctor) {
                    echo '<option value="' . $doctor->ID . '" data-department="' . get_post_meta($doctor->ID, 'imd_doctor_info_department', true) . '" ' . selected($book_doctor, $doctor->ID, false) . '>' . $doctor->post_title . '</option>';
                }
            }
            ?>
        </select>
        <input type="text" class="book-from-date" name="from_date" id="book-from-date-search-field"
               placeholder="<?php _e('From Date', 'inwavethemes'); ?>"
               value="<?php echo isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : ''; ?>"/>
        <input type="text" class="book-to-date" name="to_date" placeholder="<?php _e('To Date', 'inwavethemes'); ?>" id="book-to-date-search-field"
               value="<?php echo isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : ''; ?>"/>
               <?php
           }

           static function metabox_html() {
	           wp_enqueue_script('jquery-datetimepicker');
	           wp_enqueue_style('jquery-datetimepicker');
               // Noncename needed to verify where the data originated
               global $post;
               $post_id = $post->ID;
	           $date_options = array(
		           'format' => get_option('date_format'),
		           'timepicker' => false
	           );
               ?>
        <div class="booked-appointment-detail wp-clearfix">
            <div class="col-left">
                <div class="col-inner">
                    <h3><?php echo __('Patient Information', 'inwavethemes') ?></h3>
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td class="label"><label><?php echo __('First Name', 'inwavethemes'); ?></label></td>
                                <td class="value"><input type="text" name="first_name" value="<?php echo get_post_meta($post_id, 'imd_first_name', true); ?>" required/></td>
                            </tr>
                            <tr>
                                <td class="label"><label><?php echo __('Last Name', 'inwavethemes'); ?></label></td>
                                <td class="value"><input type="text" name="last_name" value="<?php echo get_post_meta($post_id, 'imd_last_name', true); ?>" required/></td>
                            </tr>
                            <tr>
                                <td class="label"><label><?php echo __('Email', 'inwavethemes'); ?></label></td>
                                <td class="value"><input type="email" name="email" value="<?php echo get_post_meta($post_id, 'imd_email', true); ?>" required/></td>
                            </tr>
                            <tr>
                                <td class="label"><label><?php echo __('Phone', 'inwavethemes'); ?></label></td>
                                <td class="value"><input type="text" name="phone" value="<?php echo get_post_meta($post_id, 'imd_phone', true); ?>" required/></td>
                            </tr>
                            <tr>
                                <td class="label"><label><?php echo __('Age', 'inwavethemes'); ?></label></td>
                                <td class="value"><input type="text" name="age" value="<?php echo get_post_meta($post_id, 'imd_age', true); ?>" required/></td>
                            </tr>
                            <tr>
                                <td class="label"><label><?php echo __('Gender', 'inwavethemes'); ?></label></td>
                                <td class="value">
                                    <select name="gender" required>
                                        <option value=""><?php echo __('Select Gender', 'inwavethemes'); ?></option>
                                        <?php
                                        $genders = IMD_Appointment::get_gender_array();
                                        foreach ($genders as $gender => $title) {
                                            echo '<option value="' . $gender . '" ' . selected(get_post_meta($post_id, 'imd_gender', true), $gender, false) . '>' . $title . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><label><?php echo __('Address', 'inwavethemes'); ?></label></td>
                                <td class="value"><input type="text" name="address" value="<?php echo get_post_meta($post_id, 'imd_address', true); ?>" required/></td>
                            </tr>
                            <tr>
                                <td class="label"><label><?php echo __('Message', 'inwavethemes'); ?></label></td>
                                <td class="value"><textarea name="message"><?php echo $post->post_content; ?></textarea></td>
                            </tr>
                            <?php do_action('imd_booked_patient_info', $post) ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-right">
                <div class="col-inner">
                    <h3><?php echo __('Appointment Information', 'inwavethemes') ?></h3>
                    <?php
                    $appointment_id = get_post_meta($post_id, 'imd_appointment_id', true);
                    $appointment = IMD_Appointment::init($appointment_id);
                    if ($appointment) {
                        $doctor_id = $appointment->get_doctor();
                        ?>
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td class="label"><label><?php echo __('Department', 'inwavethemes'); ?></label></td>
                                    <td class="value">
                                        <?php
                                        $department_id = get_post_meta($doctor_id, 'imd_doctor_info_department', true);
                                        if ($department_id) {
                                            echo get_the_title($department_id);
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label"><label><?php echo __('Doctor', 'inwavethemes'); ?></label></td>
                                    <td class="value">
                                        <?php
                                        echo get_the_title($doctor_id);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label"><label><?php echo __('Date', 'inwavethemes'); ?></label></td>
                                    <td class="value"><?php echo date(get_option('date_format'), get_post_meta($post_id, 'imd_date', true)); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><label><?php echo __('Time', 'inwavethemes'); ?></label></td>
                                    <td class="value"><?php echo $appointment->get_time_range(); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><label><?php echo __('Status', 'inwavethemes'); ?></label></td>
                                    <td class="value">
                                        <?php
                                        if (get_post_meta($post_id, 'imd_status', true) == 1) {
                                            echo __('Accepted', 'inwavethemes');
                                        } elseif (get_post_meta($post_id, 'imd_status', true) == -1) {
                                            echo __('Not Accepted', 'inwavethemes');
                                        } else {
                                            ?>
                                            <select name="status" class="appointment-accept">
                                                <option value="0"><?php echo __('Not Accepted', 'inwavethemes'); ?></option>
                                                <option value="1"><?php echo __('Accept', 'inwavethemes'); ?></option>
                                                <option value="-1"><?php echo __('Cancel', 'inwavethemes'); ?></option>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="appointment-reason" style="display: none">
                                    <td class="label"><label><?php echo __('Reason', 'inwavethemes'); ?></label></td>
                                    <td class="value"><textarea name="reason"></textarea></td>
                                </tr>
                                <?php do_action('imd_booked_appointment_info', $post) ?>
                            </tbody>
                        </table>
                        <?php
                    }/*else{ */?><!--
						<table style="width: 100%;">
							<tbody>
							<tr class="imd_book_date_apo">
								<td class="label"><label><?php /*echo __('Book Date', 'inwavethemes'); */?></label></td>
								<td class="value">
									<input type="text" required="required" name="event_date" data-date-options="<?php /*echo htmlspecialchars(json_encode($date_options)); */?>" class="has-date-picker" placeholder="Choose date">
									<input type="hidden" value="<?php /*echo isset($event_start) ? $event_start : ''; */?>" name="date"/>
								</td>
							</tr>
							<tr>
								<td class="label"><label><?php /*echo __('Appointment', 'inwavethemes'); */?></label></td>
								<td class="value">
									<select name="appointment_id" required id="imd_bak_appointment">
										<option value=""><?php /*esc_html_e('Choose an appointment', 'inwavethemes'); */?></option>
									</select>
								</td>
							</tr>
							<tr class="imd_doctor_apo">
								<td class="label"><label><?php /*echo __('Doctor', 'inwavethemes'); */?></label></td>
								<td class="value"></td>
							</tr>
							<tr class="imd_date_start">
								<td class="label"><label><?php /*echo __('Date Start', 'inwavethemes'); */?></label></td>
								<td class="value"></td>
							</tr>
							<tr class="imd_date_end">
								<td class="label"><label><?php /*echo __('Date End', 'inwavethemes'); */?></label></td>
								<td class="value"></td>
							</tr>
							<tr class="imd_time_apo">
								<td class="label"><label><?php /*echo __('Time', 'inwavethemes'); */?></label></td>
								<td class="value"></td>
							</tr>
							<tr>
								<td class="label"><label><?php /*echo __('Status', 'inwavethemes'); */?></label></td>
								<td class="value">
									<select name="status" class="appointment-accept">
										<option value="0"><?php /*echo __('Not Accepted', 'inwavethemes'); */?></option>
										<option value="1"><?php /*echo __('Accept', 'inwavethemes'); */?></option>
										<option value="-1"><?php /*echo __('Cancel', 'inwavethemes'); */?></option>
									</select>
								</td>
							</tr>
							<tr class="appointment-reason" style="display: none">
								<td class="label"><label><?php /*echo __('Reason', 'inwavethemes'); */?></label></td>
								<td class="value"><textarea name="reason"></textarea></td>
							</tr>
		                    <?php /*do_action('imd_booked_appointment_info', $post) */?>
							</tbody>
						</table>
						--><?php
/*					}*/ ?>
                </div>
            </div>
        </div>
        <?php
        // Echo out the field
    }

    static function save_post($post_id) {
        $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : null;
        $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : null;
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : null;
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : null;
        $age = isset($_POST['age']) ? sanitize_text_field($_POST['age']) : null;
        $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : null;
        $address = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : null;
        $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : null;
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : null;

        global $wpdb;
        $sql = "UPDATE {$wpdb->posts} SET post_content = %s WHERE ID = %d";
        $wpdb->query($wpdb->prepare($sql, $message, $post_id));

        update_post_meta($post_id, 'imd_first_name', $first_name);
        update_post_meta($post_id, 'imd_last_name', $last_name);
        update_post_meta($post_id, 'imd_phone', $phone);
        update_post_meta($post_id, 'imd_email', $email);
        update_post_meta($post_id, 'imd_age', $age);
        update_post_meta($post_id, 'imd_gender', $gender);
        update_post_meta($post_id, 'imd_address', $address);
        update_post_meta($post_id, 'imd_status', $status);
        update_post_meta($post_id, 'imd_reason', $reason);
        if ((int) $status == 1) {
            IMD_Email::sendMail('accept_appointment', $post_id);
            IMD_Email::sendMail('doctor_accept_appointment', $post_id);
        } elseif ((int) $status == -1) {
            IMD_Email::sendMail('cancel_appointment', $post_id);
            IMD_Email::sendMail('doctor_cancel_appointment', $post_id);
        }
    }

    static function add_new($data) {
        if (!$data) {
            return null;
        }

        do_action('imd_before_save_booking_appointment', $data);

        $new_id = wp_insert_post(array(
            'post_title' => __('Booked', 'inwavethemes'),
            'post_type' => 'imd_bapm',
            'post_status' => 'publish',
            'post_content' => isset($data['message']) ? $data['message'] : ''
        ));

        if ($new_id) {
            if (isset($data['first_name']) && $data['first_name']) {
                update_post_meta($new_id, 'imd_first_name', $data['first_name']);
            }
            if (isset($data['last_name']) && $data['last_name']) {
                update_post_meta($new_id, 'imd_last_name', $data['last_name']);
            }
            if (isset($data['email']) && $data['email']) {
                update_post_meta($new_id, 'imd_email', $data['email']);
            }
            if (isset($data['phone']) && $data['phone']) {
                update_post_meta($new_id, 'imd_phone', $data['phone']);
            }
            if (isset($data['age']) && $data['age']) {
                update_post_meta($new_id, 'imd_age', $data['age']);
            }
            if (isset($data['gender']) && $data['gender']) {
                update_post_meta($new_id, 'imd_gender', $data['gender']);
            }
            if (isset($data['address']) && $data['address']) {
                update_post_meta($new_id, 'imd_address', $data['address']);
            }
            if (isset($data['appointment_id']) && $data['appointment_id']) {
                update_post_meta($new_id, 'imd_appointment_id', $data['appointment_id']);
            }
            if (isset($data['date']) && $data['date']) {
                update_post_meta($new_id, 'imd_date', $data['date']);
            }

            global $imd_settings;
            if (isset($imd_settings['general']['auto_accept_booking_appointment']) && $imd_settings['general']['auto_accept_booking_appoinment']) {
                update_post_meta($new_id, 'imd_status', '1');
            } else {
                update_post_meta($new_id, 'imd_status', '0');
            }

            if (isset($data['email']) && isset($imd_settings['general']['special_email_auto_accept']) && trim($data['email']) == trim($imd_settings['general']['special_email_auto_accept']) ){
	            update_post_meta($new_id, 'imd_status', '1');
			}

            wp_update_post(array(
                'ID' => $new_id,
                'post_title' => __('Booking', 'inwavethemes') . ' #' . $new_id
            ));

            do_action('imd_save_booking_appointment',$new_id, $data);

            IMD_Email::sendMail('new_appointment', $new_id);
            IMD_Email::sendMail('doctor_new_appointment', $new_id);
            IMD_Email::sendMail('admin_new_appointment', $new_id);
        }

        do_action('imd_after_save_booking_appointment',$new_id, $data);

        return $new_id;
    }

    static function init_hook() {
        if (is_admin()) {
            add_filter('bulk_actions-edit-imd_bapm', array(__CLASS__, 'register_bulk_actions'));
            add_filter('handle_bulk_actions-edit-imd_bapm', array(__CLASS__, 'bulk_action_handler'), 10, 3);
            add_action('admin_notices', array(__CLASS__, 'admin_notice'));
        }
    }

    static function register_bulk_actions($bulk_actions) {
        $bulk_actions['accept'] = __('Accept', 'inwavethemes');
        return $bulk_actions;
    }

    static function bulk_action_handler($redirect_to, $doaction, $post_ids) {
        if ($doaction == 'accept') {
            if ($post_ids) {
                $count = 0;
                foreach ($post_ids as $post_id) {
                    $status = get_post_meta($post_id, 'imd_status', true);
                    if (!(int) $status) {
                        update_post_meta($post_id, 'imd_status', '1');
                        IMD_Email::sendMail('accept_appointment', $post_id);
                        IMD_Email::sendMail('doctor_accept_appointment', $post_id);
                        $count++;
                    }
                }

                update_option('imd_admin_notice', sprintf(_n('%d order accepted', '%d orders accepted', $count, 'inwavethemes'), $count));
            }
        }

        return $redirect_to;
    }

    static function admin_notice() {
        $notice = get_option('imd_admin_notice');
        if ($notice) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo $notice; ?></p>
            </div>
            <?php
            update_option('imd_admin_notice', '');
        }
    }

    static function init($data) {
        if ($data) {
            if (is_numeric($data)) {
                $appointment = get_post($data);
            } else {
                $appointment = $data;
            }

            if (is_object($appointment)) {
                return new IMD_Booking_Appointment($appointment);
            }
        }

        return null;
    }

    function get_id() {
        return $this->booking->ID;
    }

    function get_appointment_id() {
        return get_post_meta($this->get_id(), 'imd_appointment_id', true);
    }

    function get_email() {
        return get_post_meta($this->get_id(), 'imd_email', true);
    }

    function get_first_name() {
        return get_post_meta($this->get_id(), 'imd_first_name', true);
    }

    function get_last_name() {
        return get_post_meta($this->get_id(), 'imd_last_name', true);
    }

    function get_age() {
        return get_post_meta($this->get_id(), 'imd_age', true);
    }

    function get_gender() {
        return get_post_meta($this->get_id(), 'imd_gender', true);
    }

    function get_address() {
        return get_post_meta($this->get_id(), 'imd_address', true);
    }

    function get_phone() {
        return get_post_meta($this->get_id(), 'imd_phone', true);
    }

    function get_reason() {
        return get_post_meta($this->get_id(), 'imd_reason', true);
    }

    function get_message() {
        return $this->booking->post_content;
    }

}

IMD_Booking_Appointment::init_hook();
