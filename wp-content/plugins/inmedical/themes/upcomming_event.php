<?php
wp_enqueue_script('custombox');
$utility = new inMedicalUtility();
$doctor = new inMediacalDoctor();

$days_setting = '7';
$posts_per_page = $post_number;
$start = 0;
$post_ids = $doctor->getDoctorEventsListing($start, $posts_per_page, 'upcoming', $days_setting);

if ($post_ids):
$sliderConfig = '{';
$sliderConfig .= '"navigation":'.$show_navigation;
$sliderConfig .= ',"autoPlay":'.$auto_play;
$sliderConfig .= ',"pagination":false';
$sliderConfig .= ',"items":'.esc_attr($item_desktop ? $item_desktop : 3);
// if ($single_item){
// $sliderConfig .= ',"singleItem":true';
// }
$sliderConfig .= ',"itemsDesktop":[1199,'.esc_attr($item_desktop ? $item_desktop : 3).']';
$sliderConfig .= ',"itemsDesktopSmall":[991,'.esc_attr($item_desktop_small ? $item_desktop_small : 2).']';
$sliderConfig .= ',"itemsTablet":[768,1]';
$sliderConfig .= ',"itemsMobile":[479,1]';
$sliderConfig .= ',"navigationText": ["<i class=\"fa fa-angle-left\"></i>", "<i class=\"fa fa-angle-right\"></i>"]';
$sliderConfig .= '}';

wp_enqueue_style('owl-carousel');
wp_enqueue_style('owl-theme');
wp_enqueue_style('owl-transitions');
wp_enqueue_script('owl-carousel');

?>

    <div class="iw-doctors iw-upcomming-event style1 <?php echo $class ?>">

        <div class="iw-doctor-list">
            <div class="owl-carousel" data-plugin-options='<?php echo $sliderConfig; ?>'>
                <?php
                    //foreach ($doctors as $d){
                    $eventObj = new inMedicalWorkingTable();
                    foreach ( $post_ids as $event ){
                        setup_postdata($event);
                        $doctor_info = $doctor->getDoctorInformation($event->doctor_post);
                        $event_info = $eventObj->getEventInfo($event->ID, $event->event_date);
                        $event_link = get_permalink($event->ID);
                        $event_link = strpos('?', $event_link) ? $event_link . '&eventDate=' . $event->event_date : $event_link . '?eventDate=' . $event->event_date;
                    ?>
                    <div class="content-item">
                        <div class="content-item-inner">
                            <?php
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($event->event_post), 'full');
                            if ($image){
                                $url_img = inwave_resize($image[0], 370, 255, array('center', 'top'));
                                ?>
                                <div class="content-image">
                                    <img alt="" src="<?php echo esc_url($url_img);?>"/>
                                    <div class="date-link">
                                        <a class="theme-bg" href="<?php echo esc_url($event_link); ?>"><?php printf('%s<br/><div>%s</div>', date('d', $event->event_date), date('M', $event->event_date)); ?></a>
                                    </div>

                                </div>
                            <?php } ?>

                            <div class="content-detail">
                                <div class="content-info">
                                    <h3 class="title"><a class="theme-color-hover" href="<?php echo esc_url($event_link); ?>"><?php echo esc_attr($event->post_title); ?></a></h3>
                                    <div class="schedule-meta">
                                        <div class="schedule-time">
                                            <i class="fa fa-clock-o"></i><?php echo $event->time_start.' - '.$event->time_end; ?>
                                        </div>
                                        <div class="doctor-name">
                                            <i class="icon-font ion-ios-person"></i><span class="theme-color"><?php echo esc_attr($doctor_info->title); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-description">
                                    <?php echo esc_html($utility->truncateString($event->post_content, 10)); ?>
                                </div>
                                <div class="readmore">
                                    <a class="theme-color-hover" href="<?php echo esc_url($event_link); ?>"><?php echo esc_html__('View Event', 'inwavethemes') ;?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php endif; ?>