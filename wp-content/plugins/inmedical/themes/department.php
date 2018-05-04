<?php
wp_enqueue_script('custombox');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('spin');

global $imd_settings;

$department = new inMediacalDepartment();
$departments = $department->getDepartments();
$department_info = $department->getDepartmentInformation(get_the_ID());
$doctors = $department->getDepartmentDoctors($department_info->id);

$sidebar_position = '';
if(class_exists('Inwave_Helper')){
    $sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'sidebar_position');
}
?>
<div class="department-detail">
    <div class="iw-container">
        <div class="iw-row">
            <?php if ($sidebar_position && $sidebar_position != 'none') : ?>
                <div class="iw-department-sidebar-detail <?php echo esc_attr(inwave_get_classes('sidebar', $sidebar_position)) ?>">
                    <?php
                    $sidebar_name = get_post_meta($department_info->id, 'inwave_sidebar_name', true);
                    if (!$sidebar_name) {
                        $sidebar_name = 'sidebar-department-detail';
                    }
                    if (is_active_sidebar($sidebar_name)) {
                        ?>
                        <div class="dynamic-sidebar-department-detail">
                            <?php dynamic_sidebar($sidebar_name); ?>
                        </div>
                    <?php } else { ?>
                        <div class="iw-department-sidebar">
                            <div class="sidebar-our-departments theme-bg">
                                <h3 class="widget-title"><?php _e('Our departments', 'inwavethemes'); ?></h3>
                                <?php if ($departments): ?>
                                    <ul>
                                        <?php
                                        foreach ($departments as $dep) {
                                            echo '<li><a href="' . get_permalink($dep->id) . '">' . $dep->title . '</a></li>';
                                        }
                                        ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php endif; ?>
            <div class="department-detail-content <?php echo esc_attr(inwave_get_classes('container', $sidebar_position)) ?>">
                <?php
                if ($department_info->large):
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($department_info->id), 'full');
                    $url_img = inwave_resize($image[0], 870, 450, true);
                    ?>
                    <div class="department-image"><img alt="" src="<?php echo esc_url($url_img); ?>"/></div>
                <?php endif; ?>
                <?php
                $content = get_post_field('post_content', get_the_ID());
                $content_parts = get_extended($content);
                ?>
                <div class="department-desc"><?php //print($content_parts['main']); ?></div>
                <div class="info-wrap">
                    <div id="iw-department-tab" class="iw-tabs iw-department-tab">
                        <div class="iw-tab-items">
                            <div class="iw-tab-item active">
                                <div class="iw-tab-item-inner">
                                    <div class="iw-tab-title">
                                        <span class="tab-icon"><i class="icon ion-information-circled"></i></span>
                                        <span><?php _e('General Information', 'inwavethemes'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="iw-tab-item">
                                <div class="iw-tab-item-inner">
                                    <div class="iw-tab-title">
                                        <span class="tab-icon"><i class="icon ion-calendar"></i></span>
                                        <span><?php _e('Our doctors', 'inwavethemes'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="iw-tab-item">
                                <div class="iw-tab-item-inner">
                                    <div class="iw-tab-title">
                                        <span class="tab-icon"><i class="icon ion-ios-paper-outline"></i></span>
                                        <span><?php _e('Working Schedule', 'inwavethemes'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iw-tab-content">
                            <div class="iw-tab-content-inner">
                                <div class="iw-tab-item-content active">
                                    <?php echo apply_filters('the_content',get_the_content()); ?>
                                </div>
                                <div class="iw-tab-item-content">
                                    <div class="iw-tab-info our-doctor">
                                        <?php echo do_shortcode('[inmedical_doctors departments="' . $department_info->id . '" item_per_page="3" order_by="rand" show_filter="0"]'); ?>
                                    </div>
                                </div>
                                <div class="iw-tab-item-content imd-appoinment-form-parent">
                                    <?php
                                    $doctor_ids = array();
                                    if($doctors){
                                        foreach ($doctors as $doctor){
                                            $doctor_ids[] = $doctor->ID;
                                        }
                                    }
                                    $date = strtotime(date('Y/m/d', current_time('timestamp') - 86400));

                                    $appointment_show_days = isset($imd_settings['general']['appointment_days_department']) && $imd_settings['general']['appointment_days_department'] ? $imd_settings['general']['appointment_days_department'] : 5;
                                    $i = 1;
                                    while($i <= $appointment_show_days) {
                                        $date += 86400;
                                        $day = date('D', $date);
                                        if(!in_array($day, imd_get_day_off_work())){
                                            $i++;
                                            $appointments = IMD_Appointment::get_appointments($day, $date, $date + 86400, $doctor_ids);
                                            if($appointments){
                                                foreach ($appointments as $appointment){
                                                    $appointment = IMD_Appointment::init($appointment);
                                                    $doctor_id = $appointment->get_doctor();
                                                    if($appointment->can_book($date)){
                                                        $slot_available = $appointment->get_slot_available($date);
                                                        ?>
                                                        <div class="iw-tab-info working-schedule">
                                                            <div class="iw-row">
                                                                <div class="info-left iw-col-md-7 iw-col-sm-7 iw-col-xs-12">
                                                                    <div class="day"><?php printf('%s <span>%s</span>', date('d', $date), date('M', $date)); ?></div>
                                                                    <div class="info">
                                                                        <h3 class="title"><?php echo esc_attr($appointment->get_title()); ?></h3>
                                                                        <div class="meta">
                                                                            <ul>
                                                                                <li class="time"><i class="fa fa-clock-o"></i><?php echo $appointment->get_time_range(); ?></li>
                                                                                <?php if($doctor_id){ ?>
                                                                                    <li class="category"><i class="fa fa-folder-o"></i><span class="theme-color"><?php echo get_the_title($doctor_id); ?></span></li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="iw-col-md-5 iw-col-sm-5 iw-col-xs-12">
                                                                    <div class="info-right">
                                                                        <div class="status accepting"><i class="fa fa-check-circle"></i>
                                                                            <?php printf(_n('%d Slot Available', '%d Slots Available' , $slot_available, 'inwavethemes'), $slot_available); ?></div>
                                                                        <div class="button-action">
                                                                        <span data-id="<?php echo $appointment->get_id(); ?>" data-date="<?php echo $date; ?>"
                                                                              class="book-appointment theme-bg-hover disable">
                                                                            <?php _e('Booking a ticket', 'inwavethemes'); ?>
                                                                        </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="hide">
                                        <form class="book-appointment-form" id="appointment-form-<?php echo rand(10, 100); ?>"></form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>