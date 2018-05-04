<?php
$utility = new inMedicalUtility();
//	wp_enqueue_style('slick-css');
//	wp_enqueue_script('slick-js');
$data_plugin_options = array(
    "navigation" => true,
    "autoHeight" => false,
    "pagination" => false,
    "autoPlay" => $auto_play == 1 ? true : false,
    "paginationNumbers" => false,
    "items" => esc_attr($item_desktop ? $item_desktop : 3),
    "itemsDesktop" => array(1199, esc_attr($item_desktop ? $item_desktop : 3)),
    "itemsDesktopSmall" => array(991, esc_attr($item_desktop_small ? $item_desktop_small : 3)),
    "itemsTablet" => array(767, 1),
    "itemsTabletSmall" => false,
    "itemsMobile" => array(479, 1),
    "navigationText" => array("<i class=\"fa fa-chevron-left\"></i>", "<i class=\"fa fa-chevron-right\"></i>")
);
?>

<div class="iw-department style1 carousel-v3 <?php print($class); ?>">
    <?php if (!empty($departments)) { ?>
        <div class="iw-department-list <?php print($style_navigation); ?>">
            <div class="owl-carousel" data-plugin-options="<?php echo htmlspecialchars(json_encode($data_plugin_options)); ?>">
                <?php foreach ($departments as $dep) { ?>
                    <div class="departments-item element-item iw-col-md-<?php print 12 / $number_column; ?> iw-col-sm-4 iw-col-xs-12">
                        <div class="department-info">
                            <?php
                            if ($dep->image):
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id($dep->id), 'full');
                                $url_img = inwave_resize($image[0], 370, 255, true);
                                ?>
                                <div class="department-icon">
                                    <img alt="" src="<?php echo esc_url($url_img);?>"/>
                                </div>
                            <?php endif; ?>
                            <h3 class="department-title"><a class="theme-color-hover" href="<?php echo get_permalink($dep->id);?>"><?php print($dep->title);?></a></h3>
                            <div class="department-desc"><?php print(wp_trim_words(do_shortcode($dep->content), $desc_text_limit ? $desc_text_limit : 15));?></div>
                            <div class="department-read-more"><a class="theme-color" href="<?php echo get_permalink($dep->id);?>"><?php _e('read more','inwavethemes');?><i class="icon ion-arrow-right-c"></i></a></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php if ($link_all) { ?>
            <div class="readmore-department">
                <a href="<?php echo esc_attr($link_all); ?>"><?php echo $link_all_text; ?></a>
            </div>
        <?php } ?>

    <?php } ?>
</div>











