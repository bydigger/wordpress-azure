<?php
$date_options = array(
    'format' => get_option('date_format'),
    'timepicker' => false
);
$eventObj = new inMedicalWorkingTable();
$event_info = $eventObj->getEventInfo($event, $event_date);
$disable_form = $event_info->available_ticket > 0 && $event_info->event_status ? '' : 'disabled';
$utility = new inMedicalUtility();
if ($event_info->available_ticket <= 0) {
    echo $utility->getMessage(__('Online Registration Unavailable', 'inwavethemes'), 'notice');
}
if(!$event_info->event_status){
    echo $utility->getMessage(__('Event expired, so you can\'t book ticket', 'inwavethemes'), 'notice');
}
?>
<form action="<?php echo get_permalink(); ?>" method="POST">
    <div class="appoinment-form request-appointment">
        <div class="field-group">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <label><?php _e('First name', 'inwavethemes'); ?>*</label>

                    <div class="form-control-wrap first-name">
                        <input <?php print($disable_form);?> required="required" name="first_name" type="text" class="form-control" placeholder="<?php echo __('Enter your first name', 'inwavethemes'); ?>" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <label><?php _e('Last name', 'inwavethemes'); ?>*</label>

                    <div class="form-control-wrap last-name">
                        <input <?php print($disable_form);?> required="required" name="last_name" type="text" class="form-control" placeholder="<?php echo __('Enter your last name', 'inwavethemes'); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <label><?php _e('Your email', 'inwavethemes'); ?>* </label>

                    <div class="form-control-wrap your-email">
                        <input <?php print($disable_form);?> required="required" name="email" type="email" class="form-control" placeholder="<?php echo __('Your email', 'inwavethemes'); ?>" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <label><?php _e('Your phone', 'inwavethemes'); ?> </label>

                    <div class="form-control-wrap your-phone">
                        <input <?php print($disable_form);?> name="phone" type="text" class="form-control" placeholder="<?php echo __('Your phone', 'inwavethemes'); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <label><?php _e('Date of birth', 'inwavethemes'); ?> </label>

                    <div class="form-control-wrap date-of-birth">
                        <div class="field-input">
                            <input <?php print($disable_form);?> data-date-options="<?php echo htmlspecialchars(json_encode($date_options)); ?>" class="input-date form-control" type="text" name="event-date" value="" placeholder="<?php _e('Date of birth', 'inwavethemes'); ?>"/>
                            <input type="hidden" value="" name="dob"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <label><?php _e('Gender', 'inwavethemes'); ?>* </label>

                    <div class="form-control-wrap gender">
                        <select <?php print($disable_form);?> required="required" name="gender" class="form-control-select">
                            <option value=""><?php _e('Select Gender', 'inwavethemes'); ?></option>
                            <option value="Male"><?php _e('Male', 'inwavethemes'); ?></option>
                            <option value="Female"><?php _e('Female', 'inwavethemes'); ?></option>
                            <option value="Child"><?php _e('Child', 'inwavethemes'); ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label><?php _e('Address', 'inwavethemes'); ?>* </label>

                    <div class="form-control-wrap your-address">
                        <input <?php print($disable_form);?> name="address" type="text" class="form-control" placeholder="<?php echo __('Your address', 'inwavethemes'); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label><?php _e('Reason for appointment', 'inwavethemes'); ?>*</label>

                    <div class="form-control-wrap">
                        <textarea <?php print($disable_form);?> required="required" name="appointment_reason" class="form-control message-appointment" placeholder="<?php echo __('Reason for appointment', 'inwavethemes'); ?>"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="submit-button-wrap">
            <input type="hidden" name="action" value="imdSubmitBookingTicket"/>
            <input type="hidden" name="event" value="<?php print($event); ?>"/>
            <input type="hidden" name="doctor" value="<?php echo ($event_info->doctor ? $event_info->doctor->id:''); ?>"/>
            <input type="hidden" name="department" value="<?php print($event_info->department?$event_info->department->id:''); ?>"/>
            <input type="hidden" name="event_date" value="<?php echo esc_attr($event_date); ?>"/>
            <button <?php print($disable_form);?> type="submit" class="form-submit"><?php _e('Book now', 'inwavethemes'); ?></button>
            <div class="clearfix"></div>
        </div>
    </div>
</form>
