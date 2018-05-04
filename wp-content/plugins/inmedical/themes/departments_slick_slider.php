<?php
wp_enqueue_style('slick-css');
wp_enqueue_script('slick-js');
$utility = new inMedicalUtility();
?>

<div class="iw-department style2 <?php echo $class ?>" data-auto_play="<?php echo $auto_play;?>">
	
	<?php if (!empty($departments)){ ?>
    <div class="iw-department-list">
        <?php foreach ($departments as $dep){ ?>
		<?php //var_dump($dep); ?>
			<div class="department-item">
				<div class="department-item-inner">
					<div class="content-item-inner">
						<?php if ($dep->large){ ?>
							<div class="content-image">
								<?php
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id($dep->id), 'full');
                                $url_img = inwave_resize($image[0], 970, 448, array('center', 'top'));
                                ?>
								<img alt="" src="<?php echo esc_url($url_img);?>"/>
							</div>
						<?php } ?>
						<div class="content-details theme-bg <?php echo $dep->large ? '' : 'no-image' ?>">
                            <div class="content-wrap">
                                <div class="cat-icon"><img alt="" src="<?php echo esc_url($dep->icon);?>"/></div>
                                <div class="content-detail">
                                    <h3 class="title"><a href="<?php echo get_permalink($dep->id);?>"><?php echo $dep->title;?></a></h3>
                                    <div class="desc"><?php print($utility->truncateString(strip_tags(do_shortcode($dep->content)), $desc_text_limit ? $desc_text_limit : 15));?></div>
                                </div>
                                <div class="content-right-box">
                                    <a href="<?php echo get_permalink($dep->id);?>" class="content-link"><?php echo esc_html__('Make an appointment', 'inwavethemes') ;?></a><br />
                                    <?php if ($link_all){ ?>
                                        <span class="content-view-all"><a href="<?php echo esc_url($link_all); ?>" class=""><?php echo $link_all_text; ?></a></span>
                                    <?php } ?>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		
    </div>
	<?php } ?>
</div>












