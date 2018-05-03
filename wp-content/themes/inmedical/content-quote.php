<?php
/**
 * The default template for displaying content quote
 * @package inmedical
 */
$authordata = Inwave_Helper::getAuthorData();
$inwave_theme_option = Inwave_Helper::getConfig();
$show_post_infor = inwave_show_post_info();
?>
<article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?>>
    <div class="post-item post-item-quote">
		<div class="post-content">
			<div class="featured-image">
				<?php the_post_thumbnail(); ?>
				<?php $featured_image = get_the_post_thumbnail(); ?>
			</div>
		
		
			<div class="post-content-detail">
				
				<div class="post-quote theme-bg">
					<i class="fa fa-quote-left"></i>
					<div class="quote-text">
						<?php
						$post = get_post();
						$quote = inwave_getElementsByTag('blockquote', $post->post_content, 3);
						$text = $quote[2];
						$text = ltrim($text, '"');
						$text = rtrim($text, '"');
						echo wp_kses_post($text);
						?>
					</div>
				</div>
				
				<div class="post-content-head">
					
					<div class="post-head-detail">
						<?php if(isset($inwave_theme_option['show_post_date']) && $inwave_theme_option['show_post_date']){ ?>
							<div class="post-info-date">
								<span class="post-info-day"><?php echo get_the_date("d") ?></span>
								<span class="post-info-month"><?php echo get_the_date("M") ?></span>
							</div>
						<?php } ?>
						
						<div class="post-main-info">
							<h3 class="post-title">
								<a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title('', ''); ?></a>
							</h3>
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
									if(isset($inwave_theme_option['show_post_comment']) && $inwave_theme_option['show_post_comment']){
										if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
											echo '<div class="post-info-comment">';
											comments_popup_link(esc_html__('0 comment', 'inmedical'), esc_html__('1 Comment', 'inmedical'), esc_html__('% Comments', 'inmedical'));
											echo '</div>';
										}
									}
									?>
								</div>
							<?php } ?>
						</div>
					</div>

				</div>

				<div class="post-content-desc">
					<div class="post-text">
						<?php the_excerpt(); ?>
					</div>
					<?php if (isset($inwave_theme_option['show_post_tag']) && $inwave_theme_option['show_post_tag']): ?>
							<?php inwave_blog_post_tag(); ?>
						<?php endif ?>
					<div class="post-content-footer">
						<?php echo '<a class="more-link" href="' . esc_url(get_the_permalink()) . '">'.esc_html__('Read more', 'inmedical') .'</a>'; ?>
						<?php if($inwave_theme_option['social_sharing_box_category']): ?>
							<div class="post-share-buttons-cat">
								<?php
								inwave_social_sharing_category_listing(get_permalink(), Inwave_Helper::substrword(get_the_excerpt(), 10), get_the_title());
								?>
							</div>
						<?php endif; ?>
					</div>
					<?php edit_post_link( esc_html__( 'Edit', 'inmedical' ), '<span class="edit-link">', '</span>' );?>
				</div>

			</div>
			
		</div>
		
	</div>
    
</article><!-- #post-## -->