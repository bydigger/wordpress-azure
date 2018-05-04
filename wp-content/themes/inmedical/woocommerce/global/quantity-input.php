<?php
/**
 * Product quantity inputs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="quantity add-to-cart">
    <input type="text" id="qty" data-step="<?php echo esc_attr( $step ); ?>" <?php if ( is_numeric( $min_value ) ) : ?>data-min="<?php echo esc_attr( $min_value ); ?>"<?php endif; ?> <?php if ( is_numeric( $max_value ) ) : ?>data-max="<?php echo esc_attr( $max_value ); ?>"<?php endif; ?> name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_html_x( 'Qty', 'Product quantity input tooltip', 'inmedical' ) ?>" class="input-text qty text" size="4" />
    <span onclick="increaseQty(this,<?php echo esc_attr( $step ); ?>)" class="increase-qty"><i class="fa fa-sort-up"></i></span>
    <span onclick="decreaseQty(this,<?php echo esc_attr( $step ); ?>)" class="decrease-qty"><i class="fa fa-sort-down"></i></span>

</div>
