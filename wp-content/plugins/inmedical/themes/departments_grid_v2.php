<?php
    $utility = new inMedicalUtility();
    $css_height_item = '430';
    if ($height_item) {
        $css_height_item = $height_item;
    }
?>
<div class="iw-departments-listing-v2">
    <div class="departments-content">
        <?php if (!empty($departments)): ?>
            <div class="departments-items">
                <div class="iw-row">
                    <?php
                    $i = 1;
                    foreach ($departments as $dep): ?>
                        <div class="departments-item  <?php print('departments-item'.$i); ?> iw-col-md-4 iw-col-sm-4 iw-col-xs-12">
                            <div class="department-info theme-bg" style="height: <?php print ($css_height_item); ?>px;">
                                <div class="department-info-inner">
                                    <?php if ($dep->icon) { ?>
                                        <div class="department-icon">
                                            <img alt="" src="<?php echo esc_url($dep->icon); ?>"/>
                                        </div>
                                    <?php } ?>
                                    <h3 class="department-title"><a href="<?php echo get_permalink($dep->id);?>"><?php print($dep->title);?></a></h3>
                                    <div class="content-bottom">
                                        <div class="department-desc"><?php print($utility->truncateString(do_shortcode($dep->content), $desc_text_limit ? $desc_text_limit : 15));?></div>
                                        <div class="department-read-more"><a class="theme-color-hover" href="<?php echo get_permalink($dep->id);?>"><?php _e('read more','inwavethemes');?></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    $i++;
                    endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>