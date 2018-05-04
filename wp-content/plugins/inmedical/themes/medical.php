<?php
wp_enqueue_script('google-maps');
wp_enqueue_script('imdmap-script');
$event_date = filter_input(INPUT_GET, 'eventDate');
$event = new inMedicalWorkingTable();
$event_info = $event->getEventInfo(get_the_ID(), $event_date);
if (!$event_info->event_exist) {
    status_header(404);
    get_template_part(404);
} else {
    ?>
    <div class="medical-event-detail department-detail">
        <div class="iw-container">
            <div class="iw-row">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <?php
                    $utility->getNoticeMessage();
                    ?>
                    <div class="medical-event-content">
                        <div class="event-image">
                            <?php
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                            if ($image) {
                                $url_img = $image[0];
                            }
                            ?>
                            <img alt="" src="<?php echo esc_url($url_img); ?>"/>
                        </div>
                        <div class="info-wrap">
                            <div id="iw-department-tab" class="iw-tabs iw-department-tab">
                                <div class="iw-tab-items">
                                    <div class="iw-tab-item active">
                                        <div class="iw-tab-item-inner">
                                            <div class="iw-tab-title">
                                                <span class="tab-icon"><i class="icon ion-information-circled"></i></span>
                                                <span><?php _e('Event detail', 'inwavethemes'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="iw-tab-item">
                                        <div class="iw-tab-item-inner">
                                            <div class="iw-tab-title">
                                                <span class="tab-icon"><i class="icon ion-calendar"></i></span>
                                                <span><?php _e('Booking event', 'inwavethemes'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="iw-tab-content">
                                    <div class="iw-tab-content-inner">
                                        <div class="iw-tab-item-content active">
                                            <div class="event-desc">
                                                <?php
                                                the_content();
                                                ?>
                                            </div>
                                        </div>
                                        <div class="iw-tab-item-content">
                                            <div class="iw-tab-info booking-event">
                                                <?php echo apply_filters('the_content', '[inmedical_book_ticket_form event="' . get_the_ID() . '" event_date="' . $event_info->event_date . '"]'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="event-detail-sidebar">
                        <div class="event-information sidebar-item theme-bg">
                            <h3 class="sidebar-title"><?php _e('Event Information', 'inwavethemes'); ?></h3>
                            <ul class="information">
                                <?php if ($event_info->event_date) : ?>
                                    <li>
                                        <label><?php _e('Event date', 'inwavethemes'); ?></label>
                                        <span class="colon">:</span>
                                        <span><?php echo strtotime(wp_kses_post($utility->getLocalDate(get_option('date_format'), $event_info->event_date))); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($event_info->settings['event-time-start'] || $event_info->settings['event-time-end']) : ?>
                                    <li>
                                        <label><?php _e('Event time', 'inwavethemes'); ?></label>
                                        <span class="colon">:</span>
                                        <span>
                                            <span><?php echo $event_info->settings['event-time-start'] ? $event_info->settings['event-time-start'] : '' ?></span>
                                            <span><?php echo $event_info->settings['event-time-end'] ? ' - ' . $event_info->settings['event-time-end'] : '' ?></span></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($event_info->department) : ?>
                                    <li>
                                        <label><?php _e('Department', 'inwavethemes'); ?></label>
                                        <span class="colon">:</span>
                                        <span><?php print($event_info->department->title); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($event_info->doctor) : ?>
                                    <li>
                                        <label><?php _e('Doctor', 'inwavethemes'); ?></label>
                                        <span class="colon">:</span>
                                        <span><?php echo ($event_info->doctor->title); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ((!$event_info->department && !$event_info->doctor) && (isset($event_info->settings['custom_organizer']) && $event_info->settings['custom_organizer'])): ?>
                                    <li>
                                        <label><?php _e('Event Organizer', 'inwavethemes'); ?></label>
                                        <span class="colon">:</span>
                                        <span><?php echo ($event_info->settings['custom_organizer']); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <div class="available-ticket"><?php
                                if ($event_info->available_ticket > 1) {
                                    printf(__('%d Openings Available', 'inwavethemes'), $event_info->available_ticket);
                                } elseif ($event_info->available_ticket == 1) {
                                    printf(__('%d Opening Available', 'inwavethemes'), $event_info->available_ticket);
                                } else {
                                    _e('Online Registration Unavailable', 'inwavethemes');
                                }
                                ?></div>
                        </div>
                        <?php
                        if ($event_info->doctor):
                            ?>
                            <div class="head-doctor sidebar-item doctor-items">
                                <h3 class="sidebar-title"><?php _e('Head doctor', 'inwavethemes'); ?></h3>
                                <div class="doctor-item">
                                    <div class="info-wrap">
                                        <?php
                                        if ($event_info->doctor->image):
                                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($event_info->doctor->id), 'full');
                                            $url_img = inwave_resize($image[0], 370, 255, array('center', 'top'));
                                            ?>
                                            <div class="image">
                                                <img alt="" src="<?php echo esc_url($url_img); ?>"/>
                                                <?php if ($event_info->doctor->social_links && !empty($event_info->doctor->social_links)): ?>
                                                    <div class="social-link">
                                                        <ul>
                                                            <?php
                                                            foreach ($event_info->doctor->social_links as $social_link) {
                                                                echo '<li class="' . $social_link['key_title'] . '"><a class="theme-bg" href="' . $social_link['key_value'] . '"><i class="fa fa-' . $social_link['key_title'] . '""></i></a></li>';
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="info">
                                            <h3 class="doctor-name"><a class="theme-color-hover" href="<?php print(get_permalink($event_info->doctor->id)); ?>"><?php print($event_info->doctor->title); ?></a></h3>
                                            <?php
                                            if (isset($event_info->doctor->department) && $event_info->doctor->department):
                                                $department_link = get_permalink($event_info->doctor->department->id);
                                                ?>
                                                <div class="doctor-position"><a href="<?php print($department_link); ?>"><?php print($event_info->doctor->department->title); ?></a></div>
                                            <?php endif; ?>
                                            <div class="doctor-desc"><p><?php print($utility->truncateString($event_info->doctor->content, 20)); ?></p></div>
                                            <div class="doctor-read-more"><a class="theme-bg" href="<?php print(get_permalink($event_info->doctor->id)); ?>"><?php _e('View profile', 'inwavethemes'); ?></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endif;
                        if (isset($event_info->settings['location']) && $event_info->settings['location']):
                            $mapoptions = json_decode($event_info->settings['map_location']);
                            ?>
                            <div class="head-doctor sidebar-item doctor-items">
                                <h3 class="sidebar-title"><?php _e('Event Location', 'inwavethemes'); ?></h3>
                                <div class="doctor-item">
                                    <div class="inmedical-map">
                                        <div class="inmedical-map-wrap">
                                            <div class="map-preview" style="height:300px;">
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            (function ($) {
                                                $(document).ready(function () {
                                                    var options = {
                                                        mapPlaces: [
                                                            {
                                                                "id": "pid-<?php echo get_the_ID(); ?>",
                                                                "link": "<?php echo get_permalink(); ?>",
                                                                "readmore": "Reard More",
                                                                "title": "<?php echo get_the_title(); ?>",
                                                                "image": "",
                                                                "address": "<?php echo $event_info->settings['location']; ?>",
                                                                "latitude": <?php echo isset($mapoptions->lat) && $mapoptions->lat ? $mapoptions->lat : 'null'; ?>,
                                                                "longitude": <?php echo isset($mapoptions->lng) && $mapoptions->lng ? $mapoptions->lng : 'null'; ?>,
                                                                "description": ""
                                                            }
                                                        ],
                                                        mapProperties: {
                                                            zoom: <?php echo intval($mapoptions->zoomlv)?>,
                                                            center: new google.maps.LatLng(21.063997063246, 105.6184387207),
                                                            zoomControl: false,
                                                            scrollwheel: true,
                                                            disableDoubleClickZoom: true,
                                                            draggable: true,
                                                            panControl: false,
                                                            mapTypeControl: false,
                                                            scaleControl: false,
                                                            overviewMapControl: false,
                                                            streetViewControl: false,
                                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                                        },
                                                        detail_page: true,
                                                        show_location: false,
                                                        show_des: false,
                                                        spinurl: "<?php echo site_url('wp-content/plugins/inmedical/assets/images/'); ?>",
                                                        styleObj: {"name": "", "override_default": "1", "styles": ""}
                                                    };
                                                    $(".inmedical-map").imdMap(options);
                                                });
                                            })(jQuery);
                                        </script>
                                    </div>
                                    <h3><?php echo esc_attr($event_info->settings['location']); ?></h3>
                                </div>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}