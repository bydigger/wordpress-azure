<?php
/**
 * @package inmedical
 */
$inwave_theme_option = Inwave_Helper::getConfig();
$authordata = Inwave_Helper::getAuthorData();
$show_post_infor = 	(isset($inwave_theme_option['show_post_author']) && $inwave_theme_option['show_post_author'])
				|| 	(isset($inwave_theme_option['blog_category_title_listing']) && $inwave_theme_option['blog_category_title_listing'])
				|| 	(isset($inwave_theme_option['show_post_comment']) && $inwave_theme_option['show_post_comment']);
?>
<article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?>>
    <div class="post-item fit-video">
		<div class="post-content">
			
			<div class="post-content-head">
				<?php if(isset($inwave_theme_option['show_post_date']) && $inwave_theme_option['show_post_date']){ ?>
					<div class="post-info-date">
						<span class="post-info-day"><?php echo get_the_date("d") ?></span>
						<span class="post-info-month"><?php echo get_the_date("M") ?></span>
					</div>
				<?php } ?>
				
				<div class="post-main-info">
					<?php if ($inwave_theme_option['blog_post_title']){ ?>
						<h3 class="post-title">
							<?php the_title('', ''); ?>
						</h3>
					<?php } ?>
					<?php if ($show_post_infor){ ?>	
							<div class="post-info">
                                <?php if (is_sticky()){echo '<span class="feature-post">'.esc_html__('Sticky Post', 'inmedical').'</span>';} ?>
								<?php if(isset($inwave_theme_option['blog_category_title_listing']) && $inwave_theme_option['blog_category_title_listing']): ?>
									<div class="post-info-category">
										<?php echo esc_html__('Post in', 'inmedical'); ?> <?php the_category(', ') ?>
									</div>
								<?php endif; ?>
								<?php if(isset($inwave_theme_option['show_post_author']) && $inwave_theme_option['show_post_author']){ ?>
									<div class="post-info-author">
										<?php echo esc_html__('by', 'inmedical'); ?> <span><?php echo get_the_author_link(); ?></span>
									</div>
								<?php } ?>
								<?php
										echo '<div class="post-info-comment">';
										comments_popup_link(esc_html__('0 comment', 'inmedical'), esc_html__('1 Comment', 'inmedical'), esc_html__('% Comments', 'inmedical'));
										echo '</div>';
								?>
							</div>
						<?php } ?>
					
				</div>
				
			</div>
			<div class="featured-image">
				<?php
				$post_format = get_post_format();
				$contents = get_the_content();
				$str_regux = '';
				switch ($post_format) {
					case 'video':
						$video = inwave_getElementsByTag('embed', $contents);
						$str_regux = $video[0];
						if ($video) {
							echo apply_filters('the_content', $video[0]);
						}
						break;

					default:
						if ($inwave_theme_option['featured_images_single']) {
							the_post_thumbnail();
						}
						break;
				}
				?>
			</div>
			
			<div class="post-content-desc">
                <div class="post-text">
                    <?php echo apply_filters('the_content', str_replace($str_regux, '', get_the_content())); ?>
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'inmedical'),
                        'after' => '</div>',
                    ));
                    ?>
                </div>
				<?php edit_post_link( esc_html__( 'Edit', 'inmedical' ), '<span class="edit-link">', '</span>' );?>
			</div>
			
			<?php if ($inwave_theme_option['entry_footer']): ?>
				<div class="post-content-footer">
					<?php if ($inwave_theme_option['entry_footer']): ?>
						<?php inwave_blog_post_tag(); ?>
					<?php endif ?>
					
					<div class="clearfix"></div>
				</div>
			<?php endif ?>
			
			<?php if ($inwave_theme_option['social_sharing_box']): ?>
				<div class="post-share-buttons">
					<h4 class="post-share-title"><?php echo esc_html__('Share this post', 'inmedical'); ?></h4>
					<div class="post-share-buttons-inner">
							<?php
							inwave_social_sharing(get_permalink(), Inwave_Helper::substrword(get_the_excerpt(), 10), get_the_title());
							?>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
        </div>
			
        <!-- .entry-footer -->

        <?php if ($inwave_theme_option['author_info']): ?>
            <?php if (get_the_author_meta('description')) : ?>
                <div class="blog-author">
                    <?php if (get_avatar(get_the_author_meta('email'), 90)) { ?>
                        <div class="authorAvt">
                            <div class="authorAvt-inner">
                                <?php echo get_avatar(get_the_author_meta('email'), 90) ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="authorDetails">
                        <div class="author-name">
                            <?php echo esc_html__('Author: ', 'inmedical'); ?> <a class="" href="<?php echo esc_url(get_author_posts_url($authordata->ID, $authordata->user_nicename)); ?>"><?php echo esc_html($authordata->user_nicename); ?></a>
                        </div>
                        <?php if (get_the_author_meta('description')) { ?>
                            <div class="caption-desc">
                                <?php echo get_the_author_meta('description'); ?>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php endif ?>
        <?php endif ?>

    <?php if ($inwave_theme_option['related_posts']): ?>
		<?php get_template_part('blocks/related', 'posts'); ?>
	<?php endif ?>

    </div>
</article><!-- #post-## -->