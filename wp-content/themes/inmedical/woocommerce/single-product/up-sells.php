<?php
/**
 * Single Product Up-Sells
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.5
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$upsells = $product->get_upsell_ids();

if (sizeof($upsells) == 0) {
    return;
}

$meta_query = WC()->query->get_meta_query();

$args = array(
    'post_type' => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows' => 1,
    'posts_per_page' => $posts_per_page,
    'orderby' => $orderby,
    'post__in' => $upsells,
    'post__not_in' => array($product->get_id()),
    'meta_query' => $meta_query
);

$products = new WP_Query($args);

$woocommerce_loop['columns'] = $columns;

if ($products->have_posts()) : ?>
    <div class="upsells product-related row">
        <div class="col-md-12">
            <h3 class="title"><?php esc_html_e('You may also like&hellip;', 'inmedical') ?></h3>
        </div>
        <?php woocommerce_product_loop_start(); ?>

        <?php while ($products->have_posts()) : $products->the_post(); ?>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <?php wc_get_template_part('content', 'product'); ?>
            </div>

        <?php endwhile; // end of the loop. ?>

        <?php woocommerce_product_loop_end(); ?>

    </div>

<?php endif;

wp_reset_postdata();
