<?php
/**
 * Checkout coupon form
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!WC()->cart->coupons_enabled()) {
    return;
}

?>

    <div class="checkout-row col-md-12">
		<div class="checkout-box checkout-box-coupon">
			<div class="title"><?php esc_html_e('Coupon', 'inmedical'); ?> <!--<i class="fa fa-minus-square-o"></i>-->
			</div>
			<div class="box">
				<form method="post">

					<input type="text" name="coupon_code" class="input-text"
						   placeholder="<?php esc_attr_e('Coupon code', 'inmedical'); ?>" id="coupon_code" value=""/>

					<button type="submit" class="button" name="apply_coupon"
							value="<?php esc_attr_e('Apply Coupon', 'inmedical'); ?>"><em class="fa-icon"><i
								class="fa fa-check"></i></em><?php esc_html_e('Apply Coupon', 'inmedical'); ?></button>

				</form>
			</div>
		</div>
    </div>

