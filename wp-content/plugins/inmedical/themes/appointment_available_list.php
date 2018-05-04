    <?php
    $appointment_count = 0;
    if($appointments){
        ob_start();
    foreach ($appointments as $appointment){
        $appointment = IMD_Appointment::init($appointment);
        if($appointment->can_book($date)){
            $appointment_count++;
            $doctor_id = $appointment->get_doctor();
            $thumb_id = get_post_thumbnail_id($doctor_id);
            $thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail', true);
            $doctor_image = $thumb_url[0];
            ?>
            <div class="timeslot clearfix">
                <span class="doctor-image" ><img src="<?php echo $doctor_image; ?>" alt=""/></span>
                <div class="timeslot-col doctor-info clearfix">
                    <div class="doctor-name"><a href="<?php echo get_permalink($doctor_id); ?>" target="_blank"><?php echo get_the_title($doctor_id); ?></a></div>
                    <div class="department-name"><?php echo get_the_title(get_post_meta($doctor_id, 'imd_doctor_info_department', true)); ?></div>
                </div>
                <div class="timeslot-col slot-info clearfix">
                    <div class="timeslot-range"><i class="ion-ios-clock-outline"></i><?php echo $appointment->get_time_range(); ?></div>
                    <div class="timeslot-slot">
                        <?php
                            echo sprintf(_n('%d Slot Available', '%d Slots Available', $appointment->get_slot_available($date), 'inwavethemes'), $appointment->get_slot_available($date));
                        ?>
                    </div>
                </div>
                <span class="book-appointment" data-id="<?php echo $appointment->get_id(); ?>" data-date="<?php echo $date; ?>"><?php echo __('Book Appointment', 'inwavethemes'); ?></span>
            </div>
        <?php }
        }
    $appointment_html = ob_get_clean();
    }
?>
<div class="appointment-calender-available-list <?php echo $appointment_count > 1 ? 'multiple-appointment' : ''; ?> clearfix">
    <?php
    if(!$appointment_count){
        echo '<div class="appointment-empty">'.__('Sorry, do not have any appointments please refresh the page and try again.', 'inwavethemes').'</div>';
    }
    else{
        echo $appointment_html;
    }
    ?>
</div>