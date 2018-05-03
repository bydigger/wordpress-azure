<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.5
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<?php
/**
 * woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>
<div id="quickview-<?php the_ID(); ?>" class="quickview-box container">
    <div class="quickview-box-inner">
        <div class="quickview-body">
            <div class="product-detail">
                <div class="product-view">
                    <div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="product-essential">
                            <button onclick="Custombox.close();" class="quickview-close" type="button">
                                <i class="fa fa-times"></i>
                            </button>
							<div class="row">
                            <div class="col-md-5 col-xs-12">
								<div class="product-img-box">
									<?php
									/**
									 * woocommerce_before_single_product_summary hook
									 *
									 * @hooked woocommerce_show_product_sale_flash - 10
									 * @hooked woocommerce_show_product_images - 20
									 */
									do_action('woocommerce_before_single_product_summary');
									?>
								</div>
							</div>
                            <div class="col-md-7 col-xs-12">
								<div class="product-shop">

									<?php
									/**
									 * woocommerce_single_product_summary hook
									 *
									 * @hooked woocommerce_template_single_title - 5
									 * @hooked woocommerce_template_single_rating - 10
									 * @hooked woocommerce_template_single_price - 10
									 * @hooked woocommerce_template_single_excerpt - 20
									 * @hooked woocommerce_template_single_add_to_cart - 30
									 * @hooked woocommerce_template_single_meta - 40
									 * @hooked woocommerce_template_single_sharing - 50
									 */
									do_action('woocommerce_single_product_summary');
									?>

								</div>
							</div>
                            <!-- .product-shop -->
							</div>
                        </div>
                        <!-- .product-essential -->

                        <?php
                        /**
                         * woocommerce_after_single_product_summary hook
                         *
                         * @hooked woocommerce_output_product_data_tabs - 10
                         * @hooked woocommerce_upsell_display - 15
                         * @hooked woocommerce_output_related_products - 20
                         */
                        //do_action( 'woocommerce_after_single_product_summary' );
                        ?>

                        <meta itemprop="url" content="<?php the_permalink(); ?>"/>

                    </div>
                    <!-- #product-<?php the_ID(); ?> -->

                    <?php do_action('woocommerce_after_single_product'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
