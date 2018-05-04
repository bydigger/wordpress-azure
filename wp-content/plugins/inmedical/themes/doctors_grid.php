<?php
if ($doctors):
    $department = new inMediacalDepartment();
    $departments = $department->getDepartments();
    ?>
    <div class="iw-medical-doctors doctors-grid">
        <?php if ($show_filter): ?>
            <div class="doctors-filter-department">
                <button class="filter is-checked" data-filter="*"><?php _e('All', 'inwavethemes'); ?></button>
                <?php foreach ($departments as $dep): ?>
                    <button class="filter" data-filter="<?php echo '.' . $dep->slug; ?>"><?php echo $dep->title; ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="iw-row">
            <div class="iw-isotope-main isotope doctor-items">
                <?php foreach ($doctors['data'] as $d): ?>
                    <?php
                    $doctor_info = $doctor->getDoctorInformation($d);
                    $dep_class = array();
                    foreach ($doctor_info->departments as $dep) {
                        $dep_class[] = $dep->slug;
                    }
                    ?>
                    <div class="doctor-item element-item <?php echo implode(' ', $dep_class); ?> iw-col-md-<?php print 12 / $number_column; ?> iw-col-sm-6 iw-col-xs-12" data-category="<?php echo $doctor_info->departments[0] ? $doctor_info->departments[0]->slug : ''; ?>">
                        <div class="info-wrap">
                            <?php
                            if ($doctor_info->image):
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id($doctor_info->id), 'full');
                                $url_img = inwave_resize($image[0], 370, 255, array('center', 'top'));
                                ?>
                                <div class="image">
                                    <img alt="" src="<?php echo esc_url($url_img); ?>"/>
                                    <?php if ($doctor_info->social_links && !empty($doctor_info->social_links)): ?>
                                        <div class="social-link">
                                            <ul>
                                                <?php
                                                foreach ($doctor_info->social_links as $social_link) {
													if($social_link['key_value']){
														echo '<li class="' . $social_link['key_title'] . '"><a class="theme-bg" href="' . $social_link['key_value'] . '"><i class="fa fa-' . $social_link['key_title'] . '"></i></a></li>';
													}
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="info">
                                <h3 class="doctor-name"><a class="theme-color-hover" href="<?php print(get_permalink($doctor_info->id)); ?>"><?php print($doctor_info->title); ?></a></h3>
                                <?php
                                if ($doctor_info->departments && !empty($doctor_info->departments)):
                                    $dep_links = array();
                                    ?><div class="doctor-position"><?php
                                        foreach ($doctor_info->departments as $dep):
                                            $dep_links[] = '<a href="'.get_permalink($dep->id).'">'.$dep->title.'</a>';
                                        endforeach;
                                        echo implode(' / ', $dep_links);
                                        ?>
                                    </div>
                                <?php endif;
                                ?>
                                <div class="doctor-desc"><p><?php echo apply_filters('the_content',wp_trim_words($doctor_info->short_content, $desc_text_limit ? $desc_text_limit : 20)); ?></p></div>
                                <div class="doctor-read-more"><a class="theme-bg" href="<?php print(get_permalink($doctor_info->id)); ?>"><?php _e('Read more', 'inwavethemes'); ?></a></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ( $show_paging ):?>
        <div class="iwe-wrap">
            <?php        
                $page_list = $paging->pageList($current_page, $doctors['pages']);
                echo $page_list;
            ?>
        </div>
        <?php endif;?>
    </div>
    <?php




 endif;