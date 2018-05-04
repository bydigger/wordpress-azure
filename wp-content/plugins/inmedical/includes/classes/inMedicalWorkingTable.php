<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inMedicalWorkingTable
 *
 * @author duongca
 */
class inMedicalWorkingTable {

    /**
     * Adds the meta box container.
     */
    public function add_meta_box($post_type) {
        if ($post_type == 'inmedical') {
            add_meta_box(
                    'inmedical_event_location', __('Event Location', 'inwavethemes'), array($this, 'render_meta_box_event_location'), $post_type, 'advanced', 'high'
            );
            add_meta_box(
                    'inmedical_implement', __('Event Organizer', 'inwavethemes'), array($this, 'render_meta_box_event_implement'), $post_type, 'advanced', 'high'
            );
            add_meta_box(
                    'inmedical_detail', __('Event Detail', 'inwavethemes'), array($this, 'render_meta_box_work_detail'), $post_type, 'advanced', 'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save($post_id) {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        /* @var $_POST type */
        if (!isset($_POST['imd_post_metabox_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['imd_post_metabox_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'imd_post_metabox')) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        $post_type = $_POST['post_type'];
        if ('page' == $post_type) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /* OK, its safe for us to save the data now. */

        $settings = $_POST['event-settings'];
        $event_repeat = $settings['event-repeat'];
        global $wpdb;
        $start_date = strtotime(date('m/d/Y', $settings['event-date-value']));
        $wpdb->delete($wpdb->prefix . "imd_events", array('event_post' => $post_id));
        $doctor = new inMediacalDoctor();
        if(isset($settings['doctor']) && $settings['doctor']) {
            $doctor_info = $doctor->getDoctorInformation($settings['doctor']);
        }
        $department = new inMediacalDepartment();
        $department_info = $department->getDepartmentInformation($settings['department']);
        $input_data = array(
            'event_post' => $post_id,
            'event_date' => $start_date,
            'time_start' => $settings['event-time-start'],
            'time_end' => $settings['event-time-end'],
            'doctor_post' => $doctor_info->id ? $doctor_info->id : '0',
            'department_post' => $department_info->id
        );
        switch ($event_repeat) {
            case 'daily':
                for ($i = 0; $i < $settings['daily-number-repeat']; $i++) {
                    $difference_time = $i * $settings['daily-gap-repeat'] * 86400;
                    $date = $start_date + $difference_time;
                    $input_data['event_date'] = $date;
                    $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                }
                break;
            case 'weekly':
                for ($i = 0; $i < $settings['weekly-number-repeat']; $i++) {
                    $difference_time = $i * $settings['weekly-gap-repeat'] * 7 * 86400;
                    $date = $start_date + $difference_time;
                    if ($settings['repeat-mode'] == 'single') {
                        $input_data['event_date'] = $date;
                        $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                    } else {
                        $week = date('W', $date);
                        $year = date('Y', $date);
                        $day_time = strtotime($year . 'W' . $week);
                        foreach ($settings['repeat-days'] as $key => $value) {
                            $day = $day_time + $key * 86400;
                            $input_data['event_date'] = $day;
                            $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                        }
                    }
                }
                break;
            case 'monthly':
                list($c_day, $c_month, $c_year) = array(date('j', $start_date), date('n', $start_date), date('Y', $start_date));
                for ($i = 0; $i < $settings['monthly-number-repeat']; $i++) {
                    if ($settings['repeat-by'] == 'day_of_month') {
                        $next_month = $c_month + $i * $settings['monthly-gap-repeat'];
                        $month = $next_month / 12 <= 0 ? $next_month : $next_month % 12;
                        $year = $next_month / 12 <= 0 ? $c_year : $c_year + intval($next_month / 12);
                        $date = strtotime($month . '/' . $c_day . '/' . $year);
                        $input_data['event_date'] = $date;
                        $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                    } else {
                        
                    }
                }
                break;
            case 'yearly':
                list($c_day, $c_month, $c_year) = array(date('j', $start_date), date('n', $start_date), date('Y', $start_date));
                for ($i = 0; $i < $settings['yearly-number-repeat']; $i++) {
                    $year = $c_year + $i * $settings['yearly-gap-repeat'];
                    $date = strtotime($c_month . '/' . $c_day . '/' . $year);
                    $input_data['event_date'] = $date;
                    $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                }
                break;
            case 'custom':
                $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                if (isset($settings['custom-repeat']) && !empty($settings['custom-repeat'])) {
                    foreach ($settings['custom-repeat'] as $custom_repeat) {
                        list($f_date, $t_date) = explode('|', $custom_repeat);
                        $days = (($t_date - $f_date) / 86400) + 1;
                        for ($i = 0; $i < $days; $i++) {
                            $date = strtotime(date('m/d/Y', $f_date)) + $i * 86400;
                            if ($date != $start_date) {
                                $input_data['event_date'] = $date;
                                $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                            }
                        }
                    }
                }

                break;

            default:
                $wpdb->insert($wpdb->prefix . "imd_events", $input_data);
                break;
        }

        update_post_meta($post_id, 'imd_event_settings', $settings);
    }

    public function render_meta_box_event_location($post) {
        $settings = get_post_meta($post->ID, 'imd_event_settings', TRUE);
        wp_enqueue_script('google-maps');
        wp_enqueue_script('iw-map-field');
        echo inMedicalUtility::getIwMapFieldHtml('event-settings[map_location]', isset($settings['map_location']) ? $settings['map_location'] : array(), '');
        ?>
        <div class="iw-metabox-fields">
            <div class="field-group">
                <label class="field-label"><?php _e('Location', 'inwavethemes'); ?></label>
                <div class="field-input">
                    <input id="map-location" type="text" name="event-settings[location]" placeholder="<?php _e('Input Location', 'inwavethemes') ?>" value="<?php echo isset($settings['location']) && $settings['location'] ? $settings['location'] : ''; ?>"/>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_meta_box_event_implement($post) {
        $settings = get_post_meta($post->ID, 'imd_event_settings', TRUE);
        $department = new inMediacalDepartment();
        $departments = $department->getDepartments(-1);
        ?>
        <div class="iw-metabox-fields">
            <div class="field-group">
                <label class="field-label"><?php _e('Department', 'inwavethemes'); ?></label>
                <div class="field-input">
                    <select class="department" name="event-settings[department]">
                        <option value=""><?php _e('Select department', 'inwavethemes'); ?></option>
                        <?php
                        foreach ($departments as $dep) {
                            echo '<option ' . (isset($settings['department']) && $dep->id == $settings['department'] ? 'selected' : '') . ' value="' . $dep->id . '">' . $dep->title . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="field-group">
                <label class="field-label"><?php _e('Doctor', 'inwavethemes'); ?></label>
                <div class="field-input">
                    <select data-current-value="<?php echo isset($settings['doctor']) ? $settings['doctor'] : ''; ?>" class="doctor" name="event-settings[doctor]">
                        <option value=""><?php _e('Select doctor', 'inwavethemes'); ?></option>
                    </select>
                </div>
            </div>
            <div class="field-group">
                <label class="field-label"><?php _e('Custome Event Organizer', 'inwavethemes'); ?></label>
                <div class="field-input">
                    <input type="text" name="event-settings[custom_organizer]" value="<?php echo isset($settings['custom_organizer']) && $settings['custom_organizer'] ? $settings['custom_organizer'] : ''; ?>"/>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_meta_box_work_detail($post) {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-datetimepicker');
        wp_enqueue_style('jquery-datetimepicker');
        wp_enqueue_style('wp-color-picker');
        $date_options = array(
            'format' => get_option('date_format'),
            'timepicker' => false
        );
        $time_options = array(
            'format' => 'H:i',
            'datepicker' => false
        );

        $settings = get_post_meta($post->ID, 'imd_event_settings', TRUE);
        $department = new inMediacalDepartment();
        $departments = $department->getDepartments(-1);
        wp_nonce_field('imd_post_metabox', 'imd_post_metabox_nonce');
        ?>
        <div class="iw-metabox-fields">
            <div class="field-group">
                <label class="field-label"><?php _e('Event Color', 'inwavethemes'); ?></label>
                <div class="field-input">
                    <input type="text" name="event-settings[event_color]" value="<?php echo isset($settings['event_color']) && $settings['event_color'] ? $settings['event_color'] : ''; ?>" class="event-wp-color-picker" >
                </div>
            </div>
            <div class="field-group">
                <label class="field-label"><?php _e('Available tickets', 'inwavethemes'); ?></label>
                <div class="field-input">
                    <input min="1" type="number" name="event-settings[available_tickets]" value="<?php echo isset($settings['available_tickets']) && $settings['available_tickets'] ? $settings['available_tickets'] : '1'; ?>"/>
                </div>
            </div>
            <div class="field-group">
                <label class="field-label"><?php _e('Event Date', 'inwavethemes'); ?></label>
                <div class="field-input">
                    <input data-date-options="<?php echo htmlspecialchars(json_encode($date_options)); ?>" class="input-date" type="text" name="event-date" value="<?php echo isset($settings['event-date-value']) && $settings['event-date-value'] ? date_i18n(get_option('date_format'), $settings['event-date-value']) : ''; ?>" placeholder="<?php echo date_i18n(get_option('date_format')); ?>"/>
                    <input type="hidden" value="<?php echo isset($settings['event-date-value']) ? $settings['event-date-value'] : ''; ?>" name="event-settings[event-date-value]"/>
                </div>
            </div>
            <div class="event-time">
                <div class="field-group">
                    <label class="field-label"><?php _e('Event Time Start', 'inwavethemes'); ?></label>
                    <div class="field-input">
                        <input data-date-options="<?php echo htmlspecialchars(json_encode($time_options)); ?>" class="input-date" type="text" name="event-settings[event-time-start]" value="<?php echo isset($settings['event-time-start']) ? $settings['event-time-start'] : ''; ?>" placeholder="<?php echo date_i18n('H:i'); ?>"/>
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label"><?php _e('Event Time End', 'inwavethemes'); ?></label>
                    <div class="field-input">
                        <input data-date-options="<?php echo htmlspecialchars(json_encode($time_options)); ?>" class="input-date" type="text" name="event-settings[event-time-end]" value="<?php echo isset($settings['event-time-end']) ? $settings['event-time-end'] : ''; ?>" placeholder="<?php echo date_i18n('H:i'); ?>"/>
                    </div>
                </div>
            </div>
            <div class="field-group-event-repeat">
                <div class="field-group">
                    <label class="field-label"><?php _e('Event Repeat', 'inwavethemes'); ?></label>
                    <div class="field-input">
                        <select class="event-repeat" name="event-settings[event-repeat]">
                            <option <?php echo isset($settings['event-repeat']) && $settings['event-repeat'] == 'none' ? 'selected' : ''; ?> value="none"><?php _e('None', 'inwavethemes'); ?></option>
                            <option <?php echo isset($settings['event-repeat']) && $settings['event-repeat'] == 'daily' ? 'selected' : ''; ?> value="daily"><?php _e('Daily', 'inwavethemes'); ?></option>
                            <option <?php echo isset($settings['event-repeat']) && $settings['event-repeat'] == 'weekly' ? 'selected' : ''; ?> value="weekly"><?php _e('Weekly', 'inwavethemes'); ?></option>
                            <option <?php echo isset($settings['event-repeat']) && $settings['event-repeat'] == 'monthly' ? 'selected' : ''; ?> value="monthly"><?php _e('Monthly', 'inwavethemes'); ?></option>
                            <option <?php echo isset($settings['event-repeat']) && $settings['event-repeat'] == 'yearly' ? 'selected' : ''; ?> value="yearly"><?php _e('Yearly', 'inwavethemes'); ?></option>
                            <option <?php echo isset($settings['event-repeat']) && $settings['event-repeat'] == 'custom' ? 'selected' : ''; ?> value="custom"><?php _e('Custom', 'inwavethemes'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="repeat-type daily">
                    <div class="field-group">
                        <label class="field-label"><?php _e('Gap between repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[daily-gap-repeat]" value="<?php echo isset($settings['daily-gap-repeat']) && $settings['daily-gap-repeat'] ? $settings['daily-gap-repeat'] : '1'; ?>"/> <span><?php _e('day(s)', 'inwavethemes'); ?></span>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('Number of repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[daily-number-repeat]" value="<?php echo isset($settings['daily-number-repeat']) && $settings['daily-number-repeat'] ? $settings['daily-number-repeat'] : '1'; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="repeat-type weekly">
                    <div class="field-group">
                        <label class="field-label"><?php _e('Gap between repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[weekly-gap-repeat]" value="<?php echo isset($settings['weekly-gap-repeat']) && $settings['weekly-gap-repeat'] ? $settings['weekly-gap-repeat'] : '1'; ?>"/><span><?php _e('Week(s)', 'inwavethemes'); ?></span>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('Number of repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[weekly-number-repeat]" value="<?php echo isset($settings['weekly-number-repeat']) && $settings['weekly-number-repeat'] ? $settings['weekly-number-repeat'] : '1'; ?>"/>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('Repeat Mode', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <select class="repeat-mode" name="event-settings[repeat-mode]">
                                <option <?php echo isset($settings['repeat-mode']) && $settings['repeat-mode'] == 'single' ? 'selected' : ''; ?> value="single"><?php _e('Single day', 'inwavethemes'); ?></option>
                                <option <?php echo isset($settings['repeat-mode']) && $settings['repeat-mode'] == 'day_of_week' ? 'selected' : ''; ?> value="day_of_week"><?php _e('Day of week', 'inwavethemes'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="field-group repeat-mode-by-dow">
                        <label class="field-label"><?php _e('Repeat on selected days', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input <?php echo isset($settings['repeat-days'][0]) && $settings['repeat-days'][0] == '0' ? 'checked' : ''; ?> type="checkbox" value="0" name="event-settings[repeat-days][0]"/>S
                            <input <?php echo isset($settings['repeat-days'][1]) && $settings['repeat-days'][1] == '1' ? 'checked' : ''; ?> type="checkbox" value="1" name="event-settings[repeat-days][1]"/>M
                            <input <?php echo isset($settings['repeat-days'][2]) && $settings['repeat-days'][2] == '2' ? 'checked' : ''; ?> type="checkbox" value="2" name="event-settings[repeat-days][2]"/>T
                            <input <?php echo isset($settings['repeat-days'][3]) && $settings['repeat-days'][3] == '3' ? 'checked' : ''; ?> type="checkbox" value="3" name="event-settings[repeat-days][3]"/>W
                            <input <?php echo isset($settings['repeat-days'][4]) && $settings['repeat-days'][4] == '4' ? 'checked' : ''; ?> type="checkbox" value="4" name="event-settings[repeat-days][4]"/>T
                            <input <?php echo isset($settings['repeat-days'][5]) && $settings['repeat-days'][5] == '5' ? 'checked' : ''; ?> type="checkbox" value="5" name="event-settings[repeat-days][5]"/>F
                            <input <?php echo isset($settings['repeat-days'][6]) && $settings['repeat-days'][6] == '6' ? 'checked' : ''; ?> type="checkbox" value="6" name="event-settings[repeat-days][6]"/>S
                        </div>
                    </div>
                </div>
                <div class="repeat-type monthly">
                    <div class="field-group">
                        <label class="field-label"><?php _e('Gap between repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[monthly-gap-repeat]" value="<?php echo isset($settings['monthly-gap-repeat']) && $settings['monthly-gap-repeat'] ? $settings['monthly-gap-repeat'] : '1'; ?>"/><span><?php _e('Month(s)', 'inwavethemes'); ?></span>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('Number of repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[monthly-number-repeat]" value="<?php echo isset($settings['monthly-number-repeat']) && $settings['monthly-number-repeat'] ? $settings['monthly-number-repeat'] : '1'; ?>"/>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('Repeat by', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <select name="event-settings[repeat-by]">
                                <option <?php echo isset($settings['repeat-by']) && $settings['repeat-by'] == 'day_of_month' ? 'selected' : ''; ?> value="day_of_month"><?php _e('Day of month', 'inwavethemes'); ?></option>
                                <!--<option <?php echo isset($settings['repeat-by']) && $settings['repeat-by'] == 'day_of_week' ? 'selected' : ''; ?> value="day_of_week"><?php _e('Day of week', 'inwavethemes'); ?></option>-->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="repeat-type yearly">
                    <div class="field-group">
                        <label class="field-label"><?php _e('Gap between repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[yearly-gap-repeat]" value="<?php echo isset($settings['yearly-gap-repeat']) && $settings['yearly-gap-repeat'] ? $settings['yearly-gap-repeat'] : '1'; ?>"/><span><?php _e('Year(s)', 'inwavethemes'); ?></span>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('Number of repeats', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input min="1" type="number" name="event-settings[yearly-number-repeat]" value="<?php echo isset($settings['yearly-number-repeat']) && $settings['yearly-number-repeat'] ? $settings['yearly-number-repeat'] : '1'; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="repeat-type custom">
                    <div class="list-custom-repeat">
                        <div class="list-custom-repeat-title"><?php _e('Custom repeat list', 'inwavethemes'); ?></div>
                        <div class="list-custom-repeat-items">
                            <?php
                            if (isset($settings['custom-repeat']) && !empty($settings['custom-repeat'])) {
                                foreach ($settings['custom-repeat'] as $c_times) {
                                    $times = explode('|', $c_times);
                                    if (count($times) != 2) {
                                        continue;
                                    }
                                    echo '<div class="repeat-item">' . sprintf(__('From: %s - To: %s', 'inwavethemes'), date(get_option('date_format'), $times[0]), date(get_option('date_format'), $times[1])) . ' <span>remove</span>
                                <input type="hidden" value="' . $c_times . '" name="event-settings[custom-repeat][]"/>
                            </div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="error-message" style="display: none;"><?php _e('Please input date', 'inwavethemes'); ?></div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('From', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input data-date-options="<?php echo htmlspecialchars(json_encode($date_options)); ?>" class="input-date custom-time-start" type="text" name="event-time-end" value="" placeholder="<?php echo date_i18n(get_option('date_format')); ?>"/>
                            <input class="custom-time-start-value" type="hidden" value="" name="event-settings[event-time-end-value]"/>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label"><?php _e('To', 'inwavethemes'); ?></label>
                        <div class="field-input">
                            <input data-date-options="<?php echo htmlspecialchars(json_encode($date_options)); ?>" class="input-date custom-time-end" type="text" name="event-time-end" value="" placeholder="<?php echo date_i18n(get_option('date_format')); ?>"/>
                            <input type="hidden" class="custom-time-end-value" value="" name="event-settings[event-time-end-value]"/>
                        </div>
                    </div>
                    <div class="add-custom-repeat"><span class="button"><?php _e('Add Custom Repeat', 'inwavethemes'); ?></span></div>
                </div>
            </div>
        </div>
        <?php
    }

    public function getTimeTableEvents($time = null) {
        if (!$time) {
            $time = strtotime(date('m/d/Y', time()));
        } else {
            $time = strtotime(date('m/d/Y', $time));
        }

        global $wpdb;
        $result = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'imd_events AS a INNER JOIN ' . $wpdb->prefix . 'posts AS p ON a.event_post = p.ID WHERE p.post_type = %s AND p.post_status=%s AND a.event_date = %d ORDER BY a.time_start', 'inmedical', 'publish', $time));

        return $result;
    }

    public function getEventDayHtml($events, $c_dep = null) {
        $html = array();
        $doctor = new inMediacalDoctor();
        $utility = new inMedicalUtility();

        $html[] = '<ul>';
        foreach ($events as $e):
            $e_settings = unserialize(get_post_meta($e->ID, 'imd_event_settings', TRUE));
            $show = false;
            if (($c_dep && $c_dep == $e_settings['department']) || !$c_dep) {
                $show = true;
            }
            if ($show):
                $event_link = get_permalink($e->ID);
                $event_link = strpos('?', $event_link) ? $event_link . '&eventDate=' . $e->event_date : $event_link . '?eventDate=' . $e->event_date;
                $html[] = '<li ' . (isset($e_settings['event_color']) && $e_settings['event_color'] ? 'style="background-color:' . $e_settings['event_color'] . '!important"' : '') . ' class="single-event" data-start="' . $e->time_start . '" data-end="' . $e->time_end . '" data-content="event-abs-circuit">';
                $html[] = '<div class="schedule-item-overlay"></div>';
                $html[] = '<div class="schedule-item">';
                $html[] = '<div class="doctor-name">';
                $html[] = '<span class="event-time">' . $e->time_start . '-' . $e->time_end . '</span>';
                $html[] = '<a href="' . $event_link . '"><span class="event-title">' . $e->post_title . '</span></a>';
                $doctor_info = $doctor->getDoctorInformation($e_settings['doctor']);
                if ($doctor_info):
                    $html[] = '<span class="doctor-title">' . $doctor_info->title . '</span>';
                    $html[] = '<div class="schedule-info">';
                    $html[] = '<div class="schedule-time">' . date(get_option('date_format'), $e->event_date) . ' ' . sprintf('%s - %s', $e->time_start, $e->time_end) . '</div>';
                    $html[] = '<div class="doctor-name-hover">' . $e->post_title . '</div>';
                    $html[] = '<div class="schedule-deparment">' . $doctor_info->title . '</div>';
                    $html[] = '<div class="doctor-desc">' . $utility->truncateString($e->post_content, 30) . '</div>';
                    $html[] = '</div>';
                endif;
                $html[] = '</div>';
                $html[] = '</div>';
                $html[] = '</li>';
            endif;
        endforeach;
        $html[] = '</ul>';
        return implode($html);
    }

    public function getAvailableEventDates($event) {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT event_date FROM ' . $wpdb->prefix . 'imd_events  WHERE event_post=%d AND event_date >= %d', $event, strtotime(date('m/d/Y', time()))));
        $dates = array();
        foreach ($rows as $value) {
            $dates[] = $value->event_date;
        }
        return $dates;
    }

    public function getAllEventDates($event) {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT event_date FROM ' . $wpdb->prefix . 'imd_events  WHERE event_post=%d', $event));
        $dates = array();
        foreach ($rows as $value) {
            $dates[] = $value->event_date;
        }
        return $dates;
    }

    public function getSuccessBookingTicket($event, $date) {
        global $wpdb;
        $rs = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'imd_booking_ticket WHERE status != 2 AND appointment_date=' . $date . ' AND event_post=' . $event);
        return $rs;
    }

    public function getEventInfo($event, $date = '') {
        $doctor = new inMediacalDoctor();
        $department = new inMediacalDepartment();
        $eventObj = new stdClass();
        $eventObj->event_exist = true;
        $eventObj->event_status = true;
        $eventObj->event_post = get_post($event);
        $eventObj->settings = get_post_meta($event, 'imd_event_settings', TRUE);
        $eventObj->doctor = $doctor->getDoctorInformation($eventObj->settings['doctor']);
        $eventObj->department = $department->getDepartmentInformation($eventObj->settings['department']);
        $event_dates = $this->getAllEventDates($eventObj->event_post->ID);
        if ($date) {
            if (!in_array($date, $event_dates)) {
                $eventObj->event_exist = false;
            }
            $eventObj->event_date = $date;
        } else {
            $dates_av = $this->getAvailableEventDates($eventObj->event_post->ID);
            $eventObj->event_date = $dates_av[0];
        }
        if (!$eventObj->event_date) {
            $eventObj->event_date = $eventObj->settings['event-date-value'];
        }
        if ($eventObj->event_date < strtotime(date('m/d/Y', time()))) {
            $eventObj->event_status = false;
        }
        $eventObj->available_ticket = $eventObj->settings['available_tickets'] - $this->getSuccessBookingTicket($eventObj->event_post->ID, $eventObj->event_date);
        return $eventObj;
    }

}
