<?php
$utility = new inMedicalUtility();
?>
<div class="iw-departments-listing">
    <div class="departments-content">
        <?php if (!empty($departments)): ?>
            <div class="departments-items">
                <div class="iw-row">
                    <?php
                    $i=1;
                    foreach ($departments as $dep): ?>
                        <div class="<?php if($i % 3 == 1){ echo'first-class-departments';}?> departments-item element-item iw-col-md-<?php print 12 / $number_column; ?> iw-col-sm-4 iw-col-xs-12">
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
                                <div class="department-desc"><?php print($utility->truncateString(do_shortcode($dep->content), $desc_text_limit ? $desc_text_limit : 15));?></div>
                                <div class="department-read-more"><a class="theme-color" href="<?php echo get_permalink($dep->id);?>"><?php _e('read more','inwavethemes');?><i class="icon ion-arrow-right-c"></i></a></div>
                            </div>
                        </div>
                        <?php
                        $i ++;
                    endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>