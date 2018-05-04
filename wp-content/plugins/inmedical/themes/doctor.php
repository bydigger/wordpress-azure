<?php
wp_enqueue_script('custombox');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('spin');

global $imd_settings;

$doctor = new inMediacalDoctor();
$doctor_info = $doctor->getDoctorInformation(get_the_ID());
?>
<div class="department-detail iw-doctor-detail">
    <div class="iw-container">
        <div class="doctor-detail-content">
            <div class="doctor-info-wrap">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="doctor-info">
                            <h3 class="doctor-name"><?php print($doctor_info->title); ?></h3>
                            <div class="review"></div>
                            <div class="doctor-desc"><?php echo apply_filters('the_content',$doctor_info->short_content); ?></div>
                            <?php if (!empty($doctor_info->extrafields)): ?>
                                <div class="doctor-fields">
                                    <ul>
                                        <?php foreach ($doctor_info->extrafields as $field): ?>
                                            <li>
                                                <label><?php print($field['name']); ?></label>
                                                <?php
                                                switch ($field['type']) {
                                                    case 'textarea':
                                                        $value = apply_filters('the_content', $field['value']);
                                                        break;
                                                    case 'image':
                                                        $image = wp_get_attachment_image_src($field['value'], 'large', true);
                                                        $value = '<img src="' . esc_url($image[0]) . '" alt="">';
                                                        break;
                                                    case 'date':
                                                        $value = date(get_option('date_format', $field['value']));
                                                        break;
                                                    case 'link':
                                                        $link_value = maybe_unserialize(html_entity_decode($field['value']));
                                                        $value = '<a href="' . esc_url($link_value['link_value_link']) . '" ' . ($link_value['link_value_target'] ? 'target="' . esc_attr($link_value['link_value_target']) . '"' : '') . '>' . $link_value['link_value_text'] . '</a>';
                                                        break;
                                                    default:
                                                        $value = $field['value'];
                                                        break;
                                                }
                                                ?>
                                                <div><?php print(strip_tags($value,'<br><strong><em><a><span>')); ?></div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if ($doctor_info->social_links && !empty($doctor_info->social_links)): ?>
							<?php
							foreach ($doctor_info->social_links as $social_link) {
								$social_html='';
								if($social_link['key_value']){
									$social_html.= '<li class="' . $social_link['key_title'] . '"><a target="_blank" href="' . $social_link['key_value'] . '"><i class="fa fa-' . $social_link['key_title'] . '"></i></a></li>';
								}
							}
							if($social_html):
							?>
                                <div class="social-link">
                                    <label><?php _e('Social Profile', 'inwavethemes'); ?>:</label>
                                    <div>
                                        <ul>
                                            <?php
                                            echo ($social_html);
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php endif; ?>
                            <div class="view-schedule iw-button effect-5"><a class="theme-color" href="#"><?php _e('view doctor schedules', 'inwavethemes'); ?></a></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="image">
                            <?php
                            if ($doctor_info->large):
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id($doctor_info->id), 'full');
                                $url_img = inwave_resize($image[0], 477, 620, array('center', 'top'));
                                ?>
                                <img alt="" src="<?php echo esc_url($url_img); ?>"/>
                                <!--                    <div class="doctor-image" style="background: url('--><?php //print($doctor_info->large);         ?><!--') no-repeat scroll center center / cover "></div>-->
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iw-row">
                <div class="iw-col-md-9 iw-col-sm-9 iw-col-xs-12">
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
                                        <span><?php _e('Working Schedule', 'inwavethemes'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="iw-tab-item">
                                <div class="iw-tab-item-inner">
                                    <div class="iw-tab-title">
                                        <span class="tab-icon"><i class="icon ion-ios-chatboxes"></i></span>
                                        <span><?php _e('Client reviews', 'inwavethemes'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iw-tab-content">
                            <div class="iw-tab-content-inner">
                                <div class="iw-tab-item-content active">
                                    <?php
                                    $content = apply_filters('the_content', $doctor_info->content); //using the_content hook
                                    $formatted_content = str_replace(']]>', ']]&gt;', $content);
                                    echo $formatted_content;
                                    ?>
                                </div>
                                <div class="iw-tab-item-content imd-appoinment-form-parent">
                                    <?php
                                    $doctor_ids = array(get_the_ID());
                                    $date = strtotime(date('Y/m/d', current_time('timestamp') - 86400));
                                    $appointment_show_days = isset($imd_settings['general']['appointment_days_doctor']) && $imd_settings['general']['appointment_days_doctor'] ? $imd_settings['general']['appointment_days_doctor'] : 5;
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
                                <div class="iw-tab-item-content">
                                    <div class="iw-tab-info client-reviews">
                                        <?php
                                        if (comments_open() || get_comments_number()) :
                                            comments_template();
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iw-doctor-sidebar iw-col-md-3 iw-col-sm-3 iw-col-xs-12">
                    <div class="doctor-education-profile sidebar-item">
                        <div class="sidebar-title"><?php _e('Education & Training', 'inwavethemes'); ?></div>
                        <?php if (isset($doctor_info->education_profile['profiles']) && !empty($doctor_info->education_profile['profiles'])): ?>
                            <div class="iw-shortcode-accordions">
                                <div class="iw-medical-accordions iw-accordions  fade-slide accordion1">
                                    <div class="iw-accordions-item">
                                        <?php foreach ($doctor_info->education_profile['profiles'] as $key => $profile): ?>
                                            <div class="iw-accordion-item ">
                                                <div class="iw-accordion-header<?php $key == 0 ? print(' active') : print(''); ?>">
                                                    <div class="iw-accordion-title"><?php print($profile['key_title']); ?></div>
                                                </div>
                                                <div class="iw-accordion-content" <?php $key == 0 ? print('') : print('style="display: none;"'); ?>>
                                                    <div class="iw-accordion-info">
                                                        <!--<div class="sub-title">Claritas est etiam processus dynamicus, lectorum. Mirum est notare quam est notare quam littera.</div>-->
                                                        <div class="iw-desc"><?php print($profile['key_value']); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="sidebar-item sidebar-form-question theme-bg">
                        <div class="ajax-overlay">
                            <span class="ajax-loading"><i class="fa fa-spinner fa-spin fa-2x"></i></span>
                        </div>
                        <h3 class="sidebar-title"><?php _e('Send a question', 'inwavethemes'); ?></h3>
                        <form method="post" name="doctor-contact"  onsubmit="return false;" id="doctor-contact">
                            <div class="form-message"></div>
                            <div class="form-group">
                                <input type="text" placeholder="<?php _e('Your name', 'inwavethemes'); ?>"
                                       required="required" id="medical-name" class="control" name="name">
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="<?php _e('Your email', 'inwavethemes'); ?>"
                                       required="required" id="medical-email" class="control" name="email">
                            </div>
                            <div class="form-group iw-textarea-form">
                                <textarea placeholder="<?php echo esc_html__('Your message', 'inwavethemes'); ?>" rows="4"
                                          id="medical-message" class="control" required="required" name="message"></textarea>
                            </div>
                            <div class="form-group-submit">
                                <input type="hidden" name="action" value="sendMessageToDoctor"/>
                                <input type="hidden" name="doctor-email" value="<?php echo esc_attr($doctor_info->email); ?>"/>
                                <button class="btn-submit" name="submit"
                                        type="submit"><?php _e('SEND MESSAGE', 'inwavethemes'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="meet-other-doctors">
        <div class="iw-container">
            <h3 class="iw-title-border"><?php _e('meet', 'inwavethemes'); ?> <span class="theme-color"><?php _e('other doctors', 'inwavethemes'); ?></span></h3>
            <div class="doctor-items iw-row">
                <?php echo do_shortcode('[inmedical_doctors item_per_page="3" order_by="rand" show_filter="0"]'); ?>
            </div>
        </div>
    </div>
</div>