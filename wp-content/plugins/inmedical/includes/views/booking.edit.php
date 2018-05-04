<?php
/*
 * @package Inwave Directory
 * @version 1.0.0
 * @created May 13, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of speaker
 *
 * @developer duongca
 */
wp_enqueue_script('jquery-datetimepicker');
wp_enqueue_style('jquery-datetimepicker');
$utility->getNoticeMessage();
$date_options = array(
    'format' => get_option('date_format'),
    'timepicker' => false
);
?>
<div class="wrap booking-update">
    <h1 class="bt-title header-text"><?php echo __('Booking event update', 'inwavethemes'); ?></h1>
    <div id="poststuff">
        <div class="postbox">
            <h2 class="hndle"><?php _e('Booking information', 'inwavethemes'); ?></h2>
            <div class="inside">
                <form action="<?php echo admin_url(); ?>admin-post.php" method="post">
                    <div class="iw-row">
                        <div class="iw-col-md-6">
                            <h3><?php _e('Customer information', 'inwavethemes'); ?></h3>
                            <table style="width:100%;">
                                <tbody>
                                    <tr>
                                        <td class="label"><label><?php _e('First Name', 'inwavethemes'); ?></label></td>
                                        <td class="value">
                                            <div class="field-input">
                                                <input class="input-date" name="first_name" type="text" value="<?php echo $appointment->getFirst_name(); ?>"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Last Name', 'inwavethemes'); ?></label></td>
                                        <td class="value"><input type="text" name="last_name" value="<?php echo $appointment->getLast_name(); ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Email', 'inwavethemes'); ?></label></td>
                                        <td class="value"><input type="text" name="email" value="<?php echo $appointment->getEmail(); ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Phone number', 'inwavethemes'); ?></label></td>
                                        <td class="value"><input type="text" name="phone" value="<?php echo $appointment->getPhone(); ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Date of birth', 'inwavethemes'); ?></label></td>
                                        <td class="value">
                                            <div class="iw-metabox-fields">
                                                <div class="field-input">
                                                    <input data-date-options="<?php echo htmlspecialchars(json_encode($date_options)); ?>" class="input-date" type="text" name="date" value="<?php echo date_i18n(get_option('date_format'), $appointment->getDate_of_birth()); ?>" placeholder="<?php echo date_i18n(get_option('date_format')); ?>"/>
                                                    <input type="hidden" value="<?php echo $appointment->getDate_of_birth(); ?>" name="dob"/>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Gender', 'inwavethemes'); ?></label></td>
                                        <td class="value">
                                            <select name="gender">
                                                <option value=""><?php _e('Select gender', 'inwavethemes'); ?></option>
                                                <option <?php echo $appointment->getGender() == 'Male' ? 'selected' : ''; ?> value="Male"><?php _e('Male', 'inwavethemes'); ?></option>
                                                <option <?php echo $appointment->getGender() == 'Female' ? 'selected' : ''; ?> value="Female"><?php _e('Female', 'inwavethemes'); ?></option>
                                                <option <?php echo $appointment->getGender() == 'Child' ? 'selected' : ''; ?> value="Child"><?php _e('Child', 'inwavethemes'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Address', 'inwavethemes'); ?></label></td>
                                        <td class="value"><input type="text" name="address" value="<?php echo $appointment->getAddress(); ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Note', 'inwavethemes'); ?></label></td>
                                        <td class="value"><textarea name="reason"><?php echo $appointment->getAppointment_reason(); ?></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="iw-col-md-6">
                            <h3><?php _e('Booking information', 'inwavethemes'); ?></h3>
                            <table style="width:100%">
                                <tbody>
                                    <tr>
                                        <td class="label"><label><?php _e('Event name', 'inwavethemes'); ?></label></td>
                                        <td class="value"><?php
                                            $event = get_post($appointment->getEvent_post());
                                            echo $event->post_title;
                                            ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Department', 'inwavethemes'); ?></label></td>
                                        <td class="value"><?php
                                            $department = get_post($appointment->getDepartment_post());
                                            echo $department->post_title;
                                            ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Doctor', 'inwavethemes'); ?></label></td>
                                        <td class="value"><?php
                                            $doctor = get_post($appointment->getDoctor_post());
                                            echo $doctor->post_title;
                                            ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Event date', 'inwavethemes'); ?></label></td>
                                        <td class="value"><?php echo date_i18n(get_option('date_format'), $appointment->getAppointment_date()); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><label><?php _e('Status', 'inwavethemes'); ?></label></td>
                                        <td class="value">
                                            <select name="status">
                                                <option <?php echo $appointment->getStatus() == '1' ? 'selected' : ''; ?> value="1"><?php _e('Accepted', 'inwavethemes'); ?></option>
                                                <option <?php echo $appointment->getStatus() == '2' ? 'selected' : ''; ?> value="2"><?php _e('Cancel', 'inwavethemes'); ?></option>
                                                <option <?php echo $appointment->getStatus() == '3' ? 'selected' : ''; ?> value="3"><?php _e('Pending', 'inwavethemes'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="ev-reason">
                                        <td class="label"><label><?php _e('Reason', 'inwavethemes'); ?></label></td>
                                        <td class="value">
                                            <textarea name="cancel_reason"></textarea>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="label"></td>
                                        <td class="value">
                                            <input type="hidden" name="id" value="<?php echo $appointment->getId(); ?>">
                                            <input type="hidden" name="action" value="inMedicalSaveBookingOrder">
                                            <input class="button button-primary button-large" type="submit" style="width: 150px;" value="<?php echo __("Save"); ?>"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>