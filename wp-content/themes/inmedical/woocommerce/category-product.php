<?php
/**
 * The Template for displaying products in a product category.
 *
 * @author        InwaveThemes
 * @package    InwaveThemes/Inhost
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
wp_enqueue_script( 'wc-single-product' );
wp_enqueue_script( 'flexslider' );
wp_enqueue_script( 'zoom' );

?>
<div class="product-list<?php if ($_SESSION['product-category-layout'] == 'row') echo '-row'; ?>">

    <?php do_action('woocommerce_archive_description'); ?>

    <?php if (have_posts()) : ?>
        <div class="toolbar">
            <?php do_action('woocommerce_before_shop_loop'); ?>
		
		<div class="clear_wc"></div>
        </div>
        <div class="row">
            <?php woocommerce_product_loop_start(); ?>

            <?php woocommerce_product_subcategories(); ?>
            <?php
            $i = 0;
            while (have_posts()) : the_post(); ?>
                <?php if ($_SESSION['product-category-layout'] != 'row') :
                    $i++;
                    ?>
                    <div class="col-md-4 col-sm-6 col-xs-12 product-row-item">
                    <?php wc_get_template_part('content', 'product'); ?>
                    </div>
                <?php else: ?>
                    <?php wc_get_template_part('content', 'product_row'); ?>
                <?php endif; ?>
            <?php endwhile; // end of the loop. ?>
        <?php woocommerce_product_loop_end(); ?>
        </div>
        <?php do_action('woocommerce_after_shop_loop'); ?>

    <?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>

        <?php wc_get_template('loop/no-products-found.php'); ?>

    <?php endif; ?>
</div>