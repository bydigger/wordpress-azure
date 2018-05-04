<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop,$smof_data;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="product-image-wrapper woo-list-product-row <?php if ($product->is_on_sale()){ echo "sale_product";} ?>">
		<?php if ( $product->is_on_sale() ) : ?>
			<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale-label">' . esc_html__( 'Sale!', 'inmedical' ) . '</span>', $post, $product ); ?>
		<?php endif; ?>
		<?php do_action( 'woocommerce_showproduct_newlabel' ); ?>
		<?php // do_action( 'woocommerce_showproduct_newlabel' ); ?>
		
		<div class="product-content">
			<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="product-image">
						<a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo wp_kses_post($product->get_image()); ?></a>
					</div>
				</div>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<div class="info-products-list">
						<div class="product-name">
							<a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a>
						</div>
						<div class="price-box">
							<div class="price-box-inner">
								<?php echo wp_kses_post($product->get_price_html()); ?>
							</div>
						</div>
						
						<div class="desc">
							<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
						</div>
						
						<div class="product-row-bottom">
						
						<div class="actions-box">
								<a class="add_to_cart_button product_type_simple" data-product_id="<?php echo esc_attr($product->get_id()) ?>" data-product_sku="<?php echo esc_attr($product->get_sku()) ?>" href="<?php echo esc_url($product->add_to_cart_url())?>" data-quantity="1">Add cart</a>
								<?php if($smof_data['woocommerce_quickview']):?>
								<a href="#<?php the_ID(); ?>" class="arrows quickview">
									<i class="fa fa-arrows-alt"></i>
									<input type="hidden" value="<?php echo esc_attr($smof_data['woocommerce_quickview_effect']);?>" />
								</a>
								<?php endif;?>
								<?php if(class_exists('YITH_WCWL')): ?>
										<a href="<?php echo esc_url(add_query_arg( 'add_to_wishlist', $product->get_id() )); ?>" rel="nofollow"
										   data-product-id="<?php echo esc_attr($product->get_id()) ?>"
										   data-product-type="<?php echo esc_attr($product->get_type()); ?>" class="link-wishlist add_to_wishlist">
											<i class="fa fa-heart"></i>
										</a>
                                        <img src="<?php echo esc_url( YITH_WCWL_URL . 'assets/images/wpspin_light.gif' ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
								<?php endif; ?>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>