<?php
if ($doctors):
    $sliderConfig = '{';
    $sliderConfig .= '"navigation":' . $show_navigation;
    $sliderConfig .= ',"autoPlay":' . $auto_play;
    $sliderConfig .= ',"pagination":false';
    $sliderConfig .= ',"items":' . esc_attr($item_desktop ? $item_desktop : 3);
    // if ($single_item){
    // $sliderConfig .= ',"singleItem":true';
    // }
    $sliderConfig .= ',"itemsDesktop":[1199,' . esc_attr($item_desktop ? $item_desktop : 3) . ']';
    $sliderConfig .= ',"itemsDesktopSmall":[991,' . esc_attr($item_desktop_small ? $item_desktop_small : 2) . ']';
    $sliderConfig .= ',"itemsTablet":[768,1]';
    $sliderConfig .= ',"itemsMobile":[479,1]';
    $sliderConfig .= ',"navigationText": ["<i class=\"fa fa-angle-left\"></i>", "<i class=\"fa fa-angle-right\"></i>"]';
    $sliderConfig .= '}';

    wp_enqueue_style('owl-carousel');
    wp_enqueue_style('owl-theme');
    wp_enqueue_style('owl-transitions');
    wp_enqueue_script('owl-carousel');
    ?>


    <div class="iw-doctors style1 <?php echo $class ?>">

        <div class="iw-doctor-list">
            <div class="owl-carousel" data-plugin-options='<?php echo $sliderConfig; ?>'>
                <?php foreach ($doctors['data'] as $d) { ?>
                    <div class="content-item">
                        <div class="content-item-inner">
                            <?php
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($d->ID), 'full');
                            if ($image) {
                                $url_img = inwave_resize($image[0], 370, 255, array('center', 'top'));
                                ?>
                                <div class="content-image">
                                    <img alt="" src="<?php echo esc_url($url_img); ?>"/>
                                    <?php $social_links = unserialize(get_post_meta($d->ID, 'imd_doctor_social_links', true)); ?>
                                    <?php if ($social_links && !empty($social_links)) { ?>
                                        <div class="social-link">
                                            <ul>
                                                <?php
                                                foreach ($social_links as $social_link) {
                                                    echo '<li class="' . $social_link['key_title'] . '"><a class="theme-bg" href="' . $social_link['key_value'] . '"><i class="fa fa-' . $social_link['key_title'] . '"></i></a></li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="content-detail">
                                <div class="content-info">
                                    <h3 class="title">
                                        <a class="theme-color-hover" href="<?php echo get_permalink($d->ID); ?>"><?php echo $d->post_title; ?></a>
                                    </h3>
                                    <?php
                                    $department = new inMediacalDepartment();
                                    $extrafield = new inMedicalExtra();
                                    $departments = array();
                                    $dep_ids = explode(',', get_post_meta($d->ID, 'imd_doctor_info_department', true));
                                    if (!empty($dep_ids)) {
                                        foreach ($dep_ids as $dep_id) {
                                            $departments[] = $department->getDepartmentInformation($dep_id);
                                        }
                                    }
                                    if (!empty($departments)) {
                                        $dep_links = array();
                                        ?><div class="doctor-department"><?php
                                        foreach ($departments as $dep):
                                            $dep_links[] = '<a href="' . get_permalink($dep->id) . '">' . $dep->title . '</a>';
                                        endforeach;
                                        echo implode(' / ', $dep_links);
                                        ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if($desc_text_limit){?>
                                <div class="content-description">
                                    <?php echo $d->post_excerpt ? apply_filters('the_content', $d->post_excerpt) : wp_trim_words(apply_filters('the_content', $d->post_content), $desc_text_limit); ?>
                                </div>
                                <?php }?>
                                <div class="readmore">
                                    <a class="" href="<?php echo get_permalink($d->ID); ?>"><?php echo esc_html__('View profile', 'inwavethemes'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>


    <?php
 endif; 