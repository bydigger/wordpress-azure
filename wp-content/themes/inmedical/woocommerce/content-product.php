<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.5
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//global $product,$smof_data;
global $product, $inwave_theme_option, $woocommerce_loop;
// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
$classes=array('product-image-wrapper', 'woo-list-product-grid');
if ($product->is_on_sale()){
    $classes[] = 'sale_product';
}

?>
<div <?php post_class($classes); ?>>
    <div class="product-content">
        <div class="product-image">
            <a href="<?php echo esc_url(get_the_permalink()); ?>">
				<?php echo wp_kses_post($product->get_image()); ?>
			</a>
            <?php if ($product->is_on_sale()): ?>
                <?php echo apply_filters('woocommerce_sale_flash', '<span class="onsale-label">' . esc_html__('Sale!', 'inmedical') . '</span>', $post, $product); ?>
            <?php endif; ?>
			
            <?php do_action('woocommerce_showproduct_newlabel'); ?>
			
			<div class="actions">
                <?php if ($inwave_theme_option['woocommerce_quickview']): ?>
                    <a href="#<?php echo esc_attr(get_the_ID()); ?>" class="arrows quickview">
                        <i class="fa fa-arrows-alt"></i>
                        <input type="hidden" value="<?php echo esc_attr($inwave_theme_option['woocommerce_quickview_effect']); ?>" />
                    </a>
                <?php endif; ?>

                <?php if (class_exists('YITH_WCWL')): ?>
                    <a href="<?php echo esc_url(add_query_arg( 'add_to_wishlist', $product->get_id() )); ?>" rel="nofollow"
                       data-product-id="<?php echo esc_attr($product->get_id()) ?>"
                       data-product-type="<?php echo esc_attr($product->get_type()); ?>" class="link-wishlist add_to_wishlist">
                        <i class="fa fa-heart"></i>
                    </a>
                    <img src="<?php echo esc_url( YITH_WCWL_URL . 'assets/images/wpspin_light.gif' ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
                <?php endif; ?>
            </div>

        </div>
        <div class="info-products">
            <div class="product-name">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

                <div class="product-bottom"></div>
            </div>
            <div class="price-box">
				<div class="price-box-inner">
                <?php echo wp_kses_post($product->get_price_html()); ?>
				</div>
            </div>
			<div class="add-cart">
				<a class="add_to_cart_button product_type_simple" data-product_id="<?php echo esc_attr($product->get_id()) ?>" data-product_sku="<?php echo esc_attr($product->get_sku()) ?>" href="<?php echo esc_url($product->add_to_cart_url())?>" data-quantity="1"><?php echo __('Add cart', 'iwj');?></a>
            </div>
            
        </div>
    </div>
    
</div>
