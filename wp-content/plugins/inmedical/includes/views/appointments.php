<?php
/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Ma5 12, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of extrafield
 *
 * @developer duongca
 */
$utility->getNoticeMessage();
wp_enqueue_script('jquery-datetimepicker');
wp_enqueue_style('jquery-datetimepicker');
wp_enqueue_style('bootstrap-popover');
wp_enqueue_script('bootstrap-popover');
wp_enqueue_script('jquery-ui-sortable');
?>
<div class="iwe-wrap wrap">
    <form action="" method="post">
        <table class="appointment-list-table wp-list-table widefat posts">
            <thead>
                <tr>
                    <?php
                    $days = IMD_Appointment::get_day_array();
                        foreach ($days as $day=>$title){
                            echo '<th>'.$title.'</th>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody id="the-list">
                <tr>
                    <?php
                    $days = IMD_Appointment::get_day_array();
                    foreach ($days as $day=>$title){
                        ?>
                        <td class="appointment-<?php echo $day; ?>">
                            <div class="appointment-list" data-day="<?php echo $day; ?>">
                                <?php
                                $appointments = IMD_Appointment::get_backend_appointments_by_day($day);
                                if($appointments){
                                    foreach ($appointments as $appointment){
                                        $appointment =  IMD_Appointment::init($appointment);
                                        if($appointment){
                                            echo $appointment->get_backend_appointment_html();
                                        }
                                    }
                            }
                            ?>
                            </div>
                            <?php if(!in_array($day, imd_get_day_off_work())){ ?>
                            <a href="#" class="appointment-add button-primary" data-appointment-day="<?php echo $day; ?>"><?php echo __('Add Appointment', 'inwavethemes'); ?></a>
                            <?php }else{ ?>
                                <a href="#" class="button" disabled><?php echo __('Add Appointment', 'inwavethemes'); ?></a>
                            <?php } ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </form>
    <div style="display: none">
        <div id="appointment-form">
        <div class="appointment-form clearfix">
            <div class="el-row">
                <input type="text" name="title" placeholder="<?php echo __('Title', 'inwavethemes'); ?>">
            </div>
            <div class="el-row">
                <select name="time_start">
                    <option value=""><?php echo __('Select start time', 'inwavethemes'); ?></option>
                    <?php
                    $days = IMD_Appointment::get_hour_array();
                    foreach ($days as $day=>$title){
                        echo '<option value="'.$day.'">'.$title.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="el-row">
                <select name="time_end">
                    <option value=""><?php echo __('Select end time', 'inwavethemes'); ?></option>
                    <?php
                    $days = IMD_Appointment::get_hour_array();
                    foreach ($days as $day=>$title){
                    echo '<option value="'.$day.'">'.$title.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="el-row">
                <input type="number" name="slot" placeholder="<?php echo __('Slot', 'inwavethemes'); ?>" value="5">
            </div>
            <div class="el-row">
                <select name="doctor">
                    <option value=""><?php echo __('Select doctor', 'inwavethemes'); ?></option>
                    <?php
                        global $wpdb;
                        $sql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'indepartment' AND post_status IN('publish', 'private')";
                        $departments = $wpdb->get_results($sql);
                        $doctor_options = array();
                        if($departments){
                            foreach ($departments AS $department){
                                $sql = "SELECT p.ID, p.post_title FROM {$wpdb->posts} AS p JOIN {$wpdb->postmeta} AS pmeta ON p.ID = pmeta.post_id 
                                        WHERE post_type = 'indoctor' AND post_status IN('publish', 'private') AND pmeta.meta_key = 'imd_doctor_info_department' AND pmeta.meta_value = {$department->ID}";
                                $doctors = $wpdb->get_results($sql);
                                if($doctors){
                                    $doctor_options[$department->ID] = array('title' => $department->post_title, 'doctors' => array());
                                    foreach ($doctors as $doctor){
                                        $doctor_options[$department->ID]['doctors'][$doctor->ID] = $doctor->post_title;
                                    }
                                }
                            }
                        }

                        if($doctor_options){
                            foreach ($doctor_options as $deparment => $data){
                                echo '<optgroup label="'.$data['title'].'">';
                                foreach ($data['doctors'] as $id => $doctor){
                                    echo '<option value="'.$id.'" data-deparment="'.$deparment.'">'.$doctor.'</option>';
                                }
                                echo '</optgroup>';
                            }
                        }


                    ?>
                </select>
            </div>
            <div class="el-row">
               <?php echo __('Custom Repeating', 'inwavethemes'); ?>
            </div>
            <div class="el-row">
                <input type="text" name="date_start" class="has-date-picker" placeholder="<?php echo __('Date Start', 'inwavethemes'); ?>">
            </div>
            <div class="el-row">
                <input type="text" name="date_end" class="has-date-picker" placeholder="<?php echo __('Date End', 'inwavethemes'); ?>">
            </div>
            <div class="el-row" id="imd-respon" style="display: none"></div>
            <div class="el-row">
                <input type="hidden" name="day">
                <input type="hidden" name="id">
                <a class="button button-primary add-appointment" title="<?php echo __('Save', 'inwavethemes'); ?>"><i class="fa fa-save"></i></a>
                <a class="button button-primary add-all-appointment" title="<?php echo __('Save and add to all days', 'inwavethemes'); ?>"><i class="fa fa-copy"></i></a>
                <a class="button button-primary edit-appointment hide" title="<?php echo __('Save', 'inwavethemes'); ?>"><i class="fa fa-save"></i></a>
                <a class="button appointment-cancel" title="<?php echo __('Cancel', 'inwavethemes'); ?>"><i class="fa fa-remove"></i></a>
            </div>
        </div>
        </div>
        <div id="confirm-appointment-delete">
            <div class="confirm-appointment-delete">
                <p><?php echo __('Are you sure want to delete this.', 'inwavethemes'); ?></p>
                <button type="button" class="button button-primary delete-appointment" data-process-text="<?php echo __('Deleting...', 'inwavethemes'); ?>"><?php echo __('Continue', 'inwavethemes'); ?></button>
                <button type="button" class="button appointment-cancel"><?php echo __('Cancel', 'inwavethemes'); ?></button>
            </div>
        </div>
    </div>
</div>
