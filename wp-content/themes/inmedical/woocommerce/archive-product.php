<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

if(is_shop()){
	$post_id = get_option('woocommerce_shop_page_id');
	$sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'sidebar_position', true, $post_id);
}
else{
	$sidebar_position = Inwave_Helper::getConfig('sidebar_position');
}
?>
	<div class="page-content">
		<div class="main-content">
			<div class="container">
				<div class="row">
					<div class="<?php echo esc_attr(inwave_get_classes('container', $sidebar_position))?> product-content">
					<?php
					/**
					 * woocommerce_before_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 * @hooked woocommerce_breadcrumb - 20
					 */
					do_action( 'woocommerce_before_main_content' );
					?>

					<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

						<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

					<?php endif; ?>

					<?php
						/**
						 * woocommerce_archive_description hook.
						 *
						 * @hooked woocommerce_taxonomy_archive_description - 10
						 * @hooked woocommerce_product_archive_description - 10
						 */
						do_action( 'woocommerce_archive_description' );
					?>

					<?php if ( have_posts() ) : ?>

						<?php
							/**
							 * woocommerce_before_shop_loop hook.
							 *
							 * @hooked woocommerce_result_count - 20
							 * @hooked woocommerce_catalog_ordering - 30
							 */
							do_action( 'woocommerce_before_shop_loop' );
						?>

						<?php woocommerce_product_loop_start(); ?>

							<?php woocommerce_product_subcategories(); ?>

							<?php while ( have_posts() ) : the_post(); ?>

								<?php if(Inwave_Helper::getConfig('woocommerce_shop_column', 3) == 3){
									$class = "col-lg-4 col-md-6 col-sm-6 col-xs-12";
								}
								else{
									$class = "col-lg-3 col-md-6 col-sm-6 col-xs-12";
								}
								?>
								<div class="<?php echo esc_attr($class); ?> product-row-item">
									<?php wc_get_template_part('content', 'product'); ?>
								</div>

							<?php endwhile; // end of the loop. ?>

						<?php woocommerce_product_loop_end(); ?>

						<?php
							/**
							 * woocommerce_after_shop_loop hook.
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action( 'woocommerce_after_shop_loop' );
						?>

					<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

						<?php wc_get_template( 'loop/no-products-found.php' ); ?>

					<?php endif; ?>

					<?php
						/**
						 * woocommerce_after_main_content hook.
						 *
						 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
						 */
						do_action( 'woocommerce_after_main_content' );
					?>
				</div>
				<?php if ($sidebar_position && $sidebar_position != 'none') { ?>
					<div class="<?php echo esc_attr(inwave_get_classes('sidebar', $sidebar_position))?> product-sidebar">
					<?php
						/**
						 * woocommerce_sidebar hook.
						 *
						 * @hooked woocommerce_get_sidebar - 10
						 */
						if(is_shop()){
							$sidebar = Inwave_Helper::getPostOption('sidebar_name');
							if(!$sidebar) $sidebar = 'woocommerce_sidebar';

							if (is_active_sidebar(  $sidebar) ) {
								dynamic_sidebar($sidebar);
							}
						}else{
							do_action( 'woocommerce_sidebar' );
						}
					?>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer( 'shop' ); ?>
