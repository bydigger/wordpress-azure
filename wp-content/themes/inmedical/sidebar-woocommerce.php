<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package inmedical
 */

if(is_active_sidebar('sidebar-woocommerce')){
?>

<div id="secondary" class="widget-area product-sidebar" role="complementary">
    <?php dynamic_sidebar('sidebar-woocommerce'); ?>
</div><!-- #secondary -->
<?php } ?>