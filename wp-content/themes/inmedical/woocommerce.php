<?php
/**
 * The template for displaying Category pages
 * @package inhost
 */
get_header();
if(is_shop()){
	$post_id = get_option('woocommerce_shop_page_id');
	$sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'sidebar_position', true, $post_id);
}
else{
	$sidebar_position = Inwave_Helper::getConfig('sidebar_position');
}
?>

<div class="page-content page-content-product">
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr(inwave_get_classes('container', $sidebar_position))?> product-content">
                    <?php
                    if ( is_singular( 'product' ) ) {
                        while ( have_posts() ) : the_post();
                            wc_get_template_part( 'content', 'single-product' );
                        endwhile;
                    } else {
                        wc_get_template_part( 'category', 'product' );
                    }
                    ?>
                </div>
                <?php if ($sidebar_position && $sidebar_position != 'none') { ?>
                    <div class="<?php echo esc_attr(inwave_get_classes('sidebar', $sidebar_position))?> product-sidebar">
                        <?php get_sidebar('woocommerce'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
