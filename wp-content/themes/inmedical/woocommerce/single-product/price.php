<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 3.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>

<div class="price-box">
<div class="price-box-inner">
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<?php echo wp_kses_post($product->get_price_html()); ?>

	<meta itemprop="price" content="<?php echo esc_attr($product->get_price()); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo esc_attr(get_woocommerce_currency()); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php if($product->is_in_stock()){ echo 'InStock';} else{echo 'OutOfStock';}; ?>" />

</div>
</div>
</div>
