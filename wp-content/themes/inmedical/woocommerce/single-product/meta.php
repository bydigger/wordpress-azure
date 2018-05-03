<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<div class="sku_wrapper"><label><?php esc_html_e( 'SKU:', 'inmedical' ); ?></label><span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'inmedical' ); ?></span>.</div>

	<?php endif; ?>

	<?php echo wp_kses_post(wc_get_product_category_list( $product->get_id(), ', ', '<div class="cat-list"><label>' . _n( 'Category:', 'Categories:', $cat_count, 'inmedical' ) . '</label> ', '</div>' )); ?>

	<?php echo wp_kses_post(wc_get_product_tag_list($product->get_id(), ' ', '<div class="tags-list"><label>' . _n( 'Tag:', 'Tags:', $tag_count, 'inmedical' ) . '</label> ', '</div>' )); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>
    <div class="social-icon product_detail_share_icon">
            <label><?php echo esc_html__('Share This Post', 'inmedical'); ?></label>
            <?php
            inwave_social_sharing(get_the_permalink(), get_the_excerpt(), get_the_title());?>
    </div>

</div>
