<?php
wp_enqueue_script('custombox');
$utility = new inMedicalUtility();
$doctor = new inMediacalDoctor();
isset($_GET['filter']) ? $get_filter = $_GET['filter'] : $get_filter = 'all';
$days_setting = '7';
$tomorrow = strtotime(date('m/d/Y', time() + 60*60*24));
$next_week = strtotime(date('m/d/Y', time() + 60*60*24*$days_setting));

// Pagination Setup
$posts_per_page = $post_number;
$start = 0;
$paged = get_query_var( 'paged') ? get_query_var( 'paged', 1 ) : 1; // Current page number
$start = ($paged-1)*$posts_per_page;
$total_posts =  $doctor->countDoctorEventsListing($get_filter, $days_setting);
$post_ids = $doctor->getDoctorEventsListing($start, $posts_per_page, $get_filter, $days_setting);

//get current_url
$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$check_url = explode('?filter', $current_url);
if(isset($check_url[1])){
    $current_url = $check_url[0];
    $check_url = explode('page', $current_url);
    if(isset($check_url[1])){
        $current_url = $check_url[0];
    }
}

?>
    <div class="department-detail iw-doctor-detail iw-event-listing">
        <div class="iw-container">
            <div class="doctor-detail-content">
                <div class="row">
                    <div class="event-filter-block">
                        <form name="filter_eventlisting">
                            <select name="menu" onChange="window.document.location.href=this.options[this.selectedIndex].value;" value="GO">
                                <option value="<?php echo $current_url.'?filter=all';?>" <?php echo $get_filter == "all" ? 'selected="selected"' : '';?> ><?php _e('All Event','inwavethemes');?></option>
                                <option value="<?php echo $current_url.'?filter=upcoming';?>" <?php echo $get_filter == "upcoming" ? 'selected="selected"' : '';?>><?php _e('Upcoming','inwavethemes');?></option>
                                <option value="<?php echo $current_url.'?filter=expired';?>" <?php echo $get_filter == "expired" ? 'selected="selected"' : '';?>><?php _e('Expired','inwavethemes');?></option>
                            </select>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div id="iw-department-tab" class="iw-tabs iw-department-tab">
                        <div class="iw-tab-item-content active">
                            <?php
                            if (!empty($post_ids)):
                                $eventObj = new inMedicalWorkingTable();
                                foreach ( $post_ids as $event ):
                                    setup_postdata($event);
                                    $doctor_info = $doctor->getDoctorInformation($event->doctor_post);
                                    //$doctor_info = array();
                                    $event_info = $eventObj->getEventInfo($event->ID, $event->event_date);
                                    $event_link = get_permalink($event->ID);
                                    $event_link = strpos('?', $event_link) ? $event_link . '&eventDate=' . $event->event_date : $event_link . '?eventDate=' . $event->event_date;
                                    ?>

                                    <div class="iw-tab-info working-schedule">
                                        <div class="iw-row">
                                            <div class="info-left iw-col-md-9 iw-col-sm-9 iw-col-xs-12">
                                                <div class="day theme-color"><?php printf('%s <span>%s</span>', date('d', $event->event_date), date('M', $event->event_date)); ?></div>
                                                <div class="info">
                                                    <h3 class="title"><a class="theme-color" href="<?php echo esc_url($event_link); ?>"><?php echo esc_attr($event->post_title); ?></a></h3>
                                                    <div class="meta">
                                                        <ul>
                                                            <li class="time"><i class="fa fa-clock-o"></i><?php printf('%s - %s', $event->time_start, $event->time_end); ?></li>
                                                            <li class="category"><i class="fa fa-folder-o"></i><span class="theme-color"><?php
                                                                    echo esc_attr($doctor_info->title);
                                                                    ?></span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="desc">
                                                        <p><?php echo esc_html($utility->truncateString($event->post_content, 20)); ?></p>
                                                        <?php

                                                        if($event->event_date >= $tomorrow && $event->event_date <= $next_week){
                                                            echo '<div class="button-upcomming">'.__('Upcomming','inwavethemes').'</div>';
                                                        }
                                                        ?>

                                                    </div>
                                                </div>
                                                <div class="image">
                                                    <?php
                                                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($event->event_post), 'full');
                                                    $url_img = inwave_resize($image[0], 270, 180, true);
                                                    ?>
                                                    <img alt="" src="<?php echo esc_url($url_img);?>"/>
                                                </div>
                                                <div class="event-book-ticket" style="display:none;" id="event-<?php print($event->ID . '-' . $event->event_date); ?>">
                                                    <div class="block-title theme-bg">
                                                        <div class="title"><?php _e('Booking <span>Event</span>','inwavethemes');?></div>
                                                        <div class="description"><?php _e('Claritas est etiam processus dynamicus, qui sequitur mutationem','inwavethemes');?></div>
                                                        <span class="close-button"><i class="fa fa-close"></i></span>
                                                    </div>
                                                    <?php echo apply_filters('the_content', '[inmedical_book_ticket_form event="' . $event->ID . '" event_date="' . $event->event_date . '"]'); ?>
                                                </div>
                                            </div>
                                            <div class="iw-col-md-3 iw-col-sm-3 iw-col-xs-12">
                                                <div class="info-right">
                                                    <?php
                                                    if ($event_info->available_ticket > 1 || $event_info->available_ticket == 1) {
                                                        $check_ticket = 'available_ticket';
                                                    } else {
                                                        $check_ticket = 'unvailable_ticket';
                                                    }
                                                    ?>
                                                    <div class="status accepting <?php echo $check_ticket;?>"><i class="fa fa-check-circle"></i><?php
                                                        if ($event_info->available_ticket > 1) {
                                                            printf(__('%d Openings Available', 'inwavethemes'), $event_info->available_ticket);
                                                        } elseif ($event_info->available_ticket == 1) {
                                                            printf(__('%d Opening Available', 'inwavethemes'), $event_info->available_ticket);
                                                        } else {
                                                            _e('Online Registration Unavailable', 'inwavethemes');
                                                        }
                                                        ?></div>
                                                    <div class="button-action <?php echo $check_ticket;?>"><span data-id="event-<?php print($event->ID . '-' . $event->event_date); ?>" class="theme-bg <?php echo ($event_info->available_ticket <= 0 || !$event_info->event_status ? 'disable' : ''); ?>"><?php _e('Booking a ticket', 'inwavethemes'); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                                // Display Pagination
                                $total_page = ceil( $total_posts / $posts_per_page); // Calculate Total pages
                                $prev_arrow = is_rtl() ? '&rarr;' : '&larr;';
                                $next_arrow = is_rtl() ? '&larr;' : '&rarr;';
                                //global $wp_query;
                                $big = 999999999; // need an unlikely integer
                                if( $total_page > 1 )  {
                                    if( !$current_page = get_query_var('paged') )
                                        $current_page = 1;
                                    if( get_option('permalink_structure') ) {
                                        $format = 'page/%#%/';
                                    } else {
                                        $format = '&paged=%#%';
                                    }
                                    echo paginate_links(array(
                                        'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                        'format'        => $format,
                                        'current'       => max( 1, get_query_var('paged') ),
                                        'total'         => $total_page,
                                        'mid_size'      => 3,
                                        'type'          => 'list',
                                        'prev_text'     => $prev_arrow,
                                        'next_text'     => $next_arrow,
                                    ) );
                                }
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
