<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package inmedical
 */
get_header(); ?>
<div class="page-content page-content-404">
    <div class="container">
        <div class="error-404 not-found">
			<div class="text_404"> 4<i class="fa fa-heart-o icon"></i>4</div>
            <div class="text_label_404"><?php esc_html_e("We're sorry, but the page you were looking for doesn't exist.", "inmedical"); ?></div>
			<div class="home_link">
				<a href="<?php echo esc_url(home_url('/')); ?>" ><?php esc_html_e('Home Page', 'inmedical'); ?></a>
			</div>
        </div>
        <!-- .error-404 -->
    </div>
</div><!-- .page-content -->
<?php get_footer(); ?>
