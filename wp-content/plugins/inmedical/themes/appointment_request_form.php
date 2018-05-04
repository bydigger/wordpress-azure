<div class="appointment-form">
    <div class="appointment-form-header theme-bg">
        <h3 class="booked-form-title-bar"><?php echo __('Request an <strong>Appointment</strong>', 'inmedical'); ?></h3>
        <div class="booked-form-info">
            <?php echo sprintf(__('You are about to request an appointment for <strong>%s</strong> on <strong>%s</strong> at <strong>%s</strong>. Please review and input form that you would like to request the following appointment.', 'inwavethemes'),
                get_the_title($appointment->get_doctor()), date(get_option('date_format'), $date), $appointment->get_time_range()); ?>
        </div>
        <span class="appointment-form-close">X</span>
    </div>
    <div class="appointment-form-body">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <label><?php echo __('First Name*', 'inwavethemes'); ?></label>
                <input type="text" placeholder="<?php echo __('First Name', 'inwavethemes'); ?>" name="first_name" required class="form-control">
            </div>
            <div class="col-md-6 col-sm-12">
                <label><?php echo __('Last Name*', 'inwavethemes'); ?></label>
                <input type="text" placeholder="<?php echo __('Last Name', 'inwavethemes'); ?>" name="last_name" required class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <label><?php echo __('Your Email*', 'inwavethemes'); ?></label>
                <input type="email" placeholder="<?php echo __('Your Email', 'inwavethemes'); ?>" name="email" required class="form-control">
            </div>
            <div class="col-md-6 col-sm-12">
                <label><?php echo __('Your Phone*', 'inwavethemes'); ?></label>
                <input type="text" placeholder="<?php echo __('Your Phone', 'inwavethemes'); ?>" name="phone" required class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <label><?php echo __('Age*', 'inwavethemes'); ?></label>
                <input type="text" placeholder="<?php echo __('Age', 'inwavethemes'); ?>" name="age" required class="form-control">
            </div>
            <div class="col-md-6 col-sm-12">
                <label><?php echo __('Gender*', 'inwavethemes'); ?></label>
                <select name="gender" class="form-control" required>
                    <option value=""><?php echo __('Select Gender', 'inwavethemes'); ?></option>
                    <?php
                    $gender = IMD_Appointment::get_gender_array();
                    foreach ($gender as $gender=>$title){
                        echo "<option value='{$gender}'>{$title}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <label><?php echo __('Address*', 'inwavethemes'); ?></label>
                <input type="text" placeholder="<?php echo __('Address', 'inwavethemes'); ?>" name="address" required class="form-control">
            </div>
        </div>
        <?php do_action('imd_appointment_request_form_after', $date, $appointment_id); ?>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <label><?php echo __('Message', 'inwavethemes'); ?></label>
                <textarea name="message" placeholder="<?php echo __('Your Message', 'inwavethemes'); ?>" class="form-control" rows="5"></textarea>
            </div>
        </div>
    </div>
    <div class="appointment-form-footer">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                <div class="respon-msg hide"></div>
            </div>
            <div class="col-md-5 col-sm-12">
                <div class="appointment-form-button">
                    <input type="hidden" name="appointment_id" value="<?php echo $appointment->get_id(); ?>">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                    <button type="submit" class="request-appointment" data-date="<?php echo $date; ?>" data-appointment-id="<?php echo $appointment->get_id(); ?>" data-process-text="<?php echo __('Sending...', 'inwavethemes'); ?>"><?php echo __('Request Now', 'inwavethemes'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>