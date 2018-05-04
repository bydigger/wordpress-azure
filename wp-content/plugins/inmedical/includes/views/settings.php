<?php
/*
 * @package Inwave Infunding
 * @version 1.0.0
 * @created May 15, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of settings
 *
 * @developer duongca
 */
wp_enqueue_script('jquery-datetimepicker');
wp_enqueue_style('jquery-datetimepicker');
$utility = new inMedicalUtility();
global $imd_settings;

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post">
    <?php
    $general = $imd_settings['general'];
    ?>
    <h3><?php _e('Plugin Settings', 'inwavethemes'); ?></h3>
    <?php do_action('inmedical_start_tab_content'); ?>
    <table class="list-table plugin-setting">
        <tbody class="the-list">
            <tr class="alternate">
                <td>
                    <label><?php echo __('Event slug', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <input type="text" value="<?php echo isset($general['event_slug']) && esc_attr($general['event_slug']) ? esc_attr($general['event_slug']) : 'event'; ?>" name="imd_settings[general][event_slug]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Doctor slug', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <input type="text" value="<?php echo isset($general['doctor_slug']) && esc_attr($general['doctor_slug']) ? esc_attr($general['doctor_slug']) : 'doctor'; ?>" name="imd_settings[general][doctor_slug]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Department slug', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <input type="text" value="<?php echo isset($general['department_slug']) ? $general['department_slug'] : 'department'; ?>" name="imd_settings[general][department_slug]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('How many days the Appointment show in department detail page?', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <input type="text" value="<?php echo isset($general['appointment_days_department']) ? $general['appointment_days_department'] : '5'; ?>" name="imd_settings[general][appointment_days_department]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('How many days the Appointment show in doctor detail page?', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <input type="text" value="<?php echo isset($general['appointment_days_doctor']) ? $general['appointment_days_doctor'] : '5'; ?>" name="imd_settings[general][appointment_days_doctor]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Auto accept booking event', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $page_data = array(
                        array('text' => __('No', 'inwavethemes'), 'value' => 0),
                        array('text' => __('Yes', 'inwavethemes'), 'value' => 1)
                    );
                    echo $utility->selectFieldRender('', 'imd_settings[general][auto_accept_booking_event]', isset($general['auto_accept_booking_event']) ? $general['auto_accept_booking_event'] : '', $page_data, '', '', false);
                    ?>
                </td>
            </tr>
			<tr class="alternate">
				<td>
					<label><?php echo __('Special email auto accept booking event?', 'inwavethemes'); ?></label>
				</td>
				<td>
					<input type="email" value="<?php echo isset($general['special_email_auto_accept']) ? $general['special_email_auto_accept'] : ''; ?>" name="imd_settings[general][special_email_auto_accept]"/>
				</td>
			</tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Show empty day', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $data = array(
                        array('text' => __('No', 'inwavethemes'), 'value' => 0),
                        array('text' => __('Yes', 'inwavethemes'), 'value' => 1)
                    );
                    echo $utility->selectFieldRender('', 'imd_settings[general][show_empty_day]', isset($general['show_empty_day']) ? $general['show_empty_day'] : '', $data, '', '', false);
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Auto accept booking appointment', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $page_data = array(
                        array('text' => __('No', 'inwavethemes'), 'value' => 0),
                        array('text' => __('Yes', 'inwavethemes'), 'value' => 1)
                    );
                    echo $utility->selectFieldRender('', 'imd_settings[general][auto_accept_booking_appoinment]', isset($general['auto_accept_booking_appoinment']) ? $general['auto_accept_booking_appoinment'] : '', $page_data, '', '', false);
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Only allow book before (n) hours', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <input type="text" value="<?php echo isset($general['book_before_hours']) ? $general['book_before_hours'] : ''; ?>" name="imd_settings[general][book_before_hours]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Days off work', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $days = IMD_Appointment::get_day_array();
                    $value = isset($general['day_off_work']) ? $general['day_off_work'] : array();
                    foreach ($days as $day => $title) {
                        $checked = in_array($day, $value);
                        echo '<label class="input-checkbox"><input type="checkbox" name="imd_settings[general][day_off_work][]" value="' . $day . '" ' . ($checked ? 'checked' : '') . '>' . $title . '</label>';
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php do_action('inmedical_tab_content'); ?>
    <?php
    $emails = $imd_settings['emails'];
    ?>
    <h3><?php _e('User Appointment Email Templates', 'inwavethemes'); ?></h3>
    <table class="list-table plugin-setting">
        <tbody class="the-list">
            <tr class="alternate">
                <td>
                    <strong><?php echo __('Email shortcodes', 'inwavethemes'); ?></strong>
                </td>
                <td>
                    <strong>[ime_site_name], [ime_site_url], [ime_admin_email], [ime_booking_id], [ime_booking_edit_link], [ime_first_name], [ime_last_name], [ime_email], [ime_phone], [ime_age], [ime_gender], [ime_address], [ime_reason], [ime_department link="true|false"], [ime_doctor link="true|false"], [ime_doctor_email], [ime_doctor_phone], [ime_date format="Y/m/d"]</strong>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('New A Booking Appoinment', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['new_appointment']['enable']) ? $emails['new_appointment']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][new_appointment][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['new_appointment']['title']) ? $emails['new_appointment']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][new_appointment][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['new_appointment']['content']) ? stripslashes($emails['new_appointment']['content']) : '';
                    $editor_id = 'emails_new_appointment';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][new_appointment][content]'));
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('Accept A Booking Appoinment', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['accept_appointment']['enable']) ? $emails['accept_appointment']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][accept_appointment][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['accept_appointment']['title']) ? $emails['accept_appointment']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][accept_appointment][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['accept_appointment']['content']) ? stripslashes($emails['accept_appointment']['content']) : '';
                    $editor_id = 'emails_accept_appointment';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][accept_appointment][content]'));
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('Cancel A Booking Appoinment', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['cancel_appointment']['enable']) ? $emails['cancel_appointment']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][cancel_appointment][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['cancel_appointment']['title']) ? $emails['cancel_appointment']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][cancel_appointment][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['cancel_appointment']['content']) ? stripslashes($emails['cancel_appointment']['content']) : '';
                    $editor_id = 'emails_cancel_appointment';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][cancel_appointment][content]'));
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <h3><?php _e('Doctor Appointment Email Templates', 'inwavethemes'); ?></h3>
    <table class="list-table plugin-setting">
        <tbody class="the-list">
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('New A Booking Appoinment', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['doctor_new_appointment']['enable']) ? $emails['doctor_new_appointment']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][doctor_new_appointment][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['doctor_new_appointment']['title']) ? $emails['doctor_new_appointment']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][doctor_new_appointment][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['doctor_new_appointment']['content']) ? stripslashes($emails['doctor_new_appointment']['content']) : '';
                    $editor_id = 'emails_doctor_new_appointment';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][doctor_new_appointment][content]'));
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('Accept A Booking Appoinment', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['doctor_accept_appointment']['enable']) ? $emails['doctor_accept_appointment']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][doctor_accept_appointment][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['doctor_accept_appointment']['title']) ? $emails['doctor_accept_appointment']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][doctor_accept_appointment][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['doctor_accept_appointment']['content']) ? stripslashes($emails['doctor_accept_appointment']['content']) : '';
                    $editor_id = 'emails_doctor_accept_appointment';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][doctor_accept_appointment][content]'));
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('Cancel A Booking Appoinment', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['doctor_cancel_appointment']['enable']) ? $emails['doctor_cancel_appointment']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][doctor_cancel_appointment][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['doctor_cancel_appointment']['title']) ? $emails['doctor_cancel_appointment']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][doctor_cancel_appointment][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['doctor_cancel_appointment']['content']) ? stripslashes($emails['doctor_cancel_appointment']['content']) : '';
                    $editor_id = 'emails_doctor_cancel_appointment';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][doctor_cancel_appointment][content]'));
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <h3><?php _e('Admin Email Templates', 'inwavethemes'); ?></h3>
    <?php
    ?>
    <table class="list-table plugin-setting">
        <tbody class="the-list">
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('New A Booking Appoinment', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['admin_new_appointment']['enable']) ? $emails['admin_new_appointment']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][admin_new_appointment][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['admin_new_appointment']['title']) ? $emails['admin_new_appointment']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][admin_new_appointment][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['admin_new_appointment']['content']) ? stripslashes($emails['admin_new_appointment']['content']) : '';
                    $editor_id = 'emails_admin_new_appointment';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][admin_new_appointment][content]'));
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php do_action('inmedical_tab_content'); ?>
    <h3><?php _e('Event email template', 'inwavethemes'); ?></h3>
    <table class="list-table plugin-setting">
        <tbody class="the-list">
            <tr class="alternate">
                <td>
                    <strong><?php echo __('Email shortcodes', 'inwavethemes'); ?></strong>
                </td>
                <td>
                    <strong>[ime_site_name], [ime_site_url], [ime_admin_email], [ime_booking_id], [ime_booking_edit_link], [ime_first_name], [ime_last_name], [ime_email], [ime_phone], [ime_reason], [ime_department link="true|false"], [ime_doctor link="true|false"], [ime_date format="Y/m/d"]</strong>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('New A Booking Event', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['new_booking_event']['enable']) ? $emails['new_booking_event']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][new_booking_event][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['new_booking_event']['title']) ? $emails['new_booking_event']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][new_booking_event][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['new_booking_event']['content']) ? stripslashes($emails['new_booking_event']['content']) : '';
                    $editor_id = 'emails_new_booking_event';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][new_booking_event][content]'));
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('Accept A Booking Event', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['accept_booking_event']['enable']) ? $emails['accept_booking_event']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][accept_booking_event][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['accept_booking_event']['title']) ? $emails['accept_booking_event']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][accept_booking_event][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['accept_booking_event']['content']) ? stripslashes($emails['accept_booking_event']['content']) : '';
                    $editor_id = 'emails_accept_booking_event';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][accept_booking_event][content]'));
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('Cancel A Booking Event', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['cancel_booking_event']['enable']) ? $emails['cancel_booking_event']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][cancel_booking_event][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['cancel_booking_event']['title']) ? $emails['cancel_booking_event']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][cancel_booking_event][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['cancel_booking_event']['content']) ? stripslashes($emails['cancel_booking_event']['content']) : '';
                    $editor_id = 'emails_cancel_booking_event';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][cancel_booking_event][content]'));
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <h3><?php _e('Admin Email Templates', 'inwavethemes'); ?></h3>
    <?php
    ?>
    <table class="list-table plugin-setting">
        <tbody class="the-list">
            <tr class="alternate">
                <td colspan="2">
                    <?php echo __('New A Booking Event', 'inwavethemes'); ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['admin_new_booking_event']['enable']) ? $emails['admin_new_booking_event']['enable'] : '';
                    ?>
                    <input type="checkbox" value="1" name="imd_settings[emails][admin_new_booking_event][enable]" <?php checked($value, 1); ?>/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Title', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $value = isset($emails['admin_new_booking_event']['title']) ? $emails['admin_new_booking_event']['title'] : '';
                    ?>
                    <input type="text" value="<?php echo $value; ?>" name="imd_settings[emails][admin_new_booking_event][title]"/>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <?php
                    $content = isset($emails['admin_new_booking_event']['content']) ? stripslashes($emails['admin_new_booking_event']['content']) : '';
                    $editor_id = 'emails_admin_new_booking_event';

                    wp_editor($content, $editor_id, array('textarea_name' => 'imd_settings[emails][admin_new_booking_event][content]'));
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php do_action('imd_after_settings_page'); ?>
    <?php do_action('inmedical_end_tab_content'); ?>
    <input type="hidden" name="action" value="imdSaveSettings"/>
    <div class="iwe-save-settings">
        <input class="button" type="submit" value="Save"/>
    </div>
</form>
