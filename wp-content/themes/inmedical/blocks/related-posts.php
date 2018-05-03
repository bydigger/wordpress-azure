<?php
$post = get_post();
$tags = wp_get_post_tags($post->ID);
if ($tags) {
$tag_ids = array();
foreach ($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
$args = array(
	'tag__in' => $tag_ids,
	'post__not_in' => array($post->ID),
	'posts_per_page' => 3, // Number of related posts to display.
	'ignore_sticky_posts' => 1
);

$my_query = new wp_query($args);
if($my_query->have_posts()){
$authordata = Inwave_Helper::getAuthorData();
?>
<div class="related-post">
	<div class="related-post-title-list">
		<h5><?php echo esc_html__('Related Post', 'inmedical'); ?></h5>
	</div>
	<div class="related-post-list">
		<div class="row">
			<?php
			while ($my_query->have_posts()) {
					$my_query->the_post();
					?>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
						<div class="related-post-item">
							<div class="related-post-thumb">
								<?php 
									$img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
									$img_src = count($img) ? $img[0] : '';
									$img_src = inwave_resize($img_src, 270, 180, true);
								?>
								<img src="<?php echo esc_url($img_src); ?>" alt="">
							</div>
							<h3 class="related-post-title">
								<a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a>
							</h3>
							<div class="related-post-info">
								<?php printf(esc_html__('Posted %s %s by %s','inmedical'),get_the_date('d.m.Y'),get_the_time('h:i'),'<a class="theme-color theme-color-hover" href="'.esc_url(get_author_posts_url($authordata->ID, $authordata->user_nicename)).'">'.get_the_author().'</a>') ?>
							</div>
							<div class="related-post-content">
								<?php //the_excerpt();?>
								<?php echo wp_trim_words( get_the_excerpt(), 17, '...' ); ?>
							</div>
							<!--<div class="related-post-read-more">
								<?php echo '<a class="more-link theme-color" href="'.esc_url(get_the_permalink()).'#more-'.get_the_ID().'">'.esc_html__('Read more', 'inmedical').'</a>';?>
							</div> -->
						</div>
					</div>
				<?php }	wp_reset_postdata();
			?>
		</div>
	</div>
</div>
<?php }
} ?>