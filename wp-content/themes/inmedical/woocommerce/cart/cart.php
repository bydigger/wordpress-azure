<?php
/**
 * Cart Page
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wc_print_notices();

do_action('woocommerce_before_cart'); ?>
<div class="product-cart">
    <div class="cart">
        <form action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <div class="cart-table">
			<div class="cart-table-items">
                <div class="cart-table-title">
					<div class="row row-item">

						<div class="col-md-5 item no-border"><div class="title-cart-table"><?php esc_html_e('Product', 'inmedical'); ?></div></div>
						<div class="col-md-2 item"><div class="title-cart-table"><?php esc_html_e('Price', 'inmedical'); ?></div></div>
						<div class="col-md-2 item"><div class="title-cart-table"><?php esc_html_e('Quantity', 'inmedical'); ?></div></div>
						<div class="col-md-2 item"><div class="title-cart-table"><?php esc_html_e('Total', 'inmedical'); ?></div></div>
						<div class="col-md-1 item"><div class="title-cart-table delete-item"><a href="#"><i class="fa fa-times-circle"></i></a></div></div>
					</div>
				</div>

                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        ?>
                        <div
                            class="row row-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

								<div class="item no-border col-md-5 col-sm-5 col-xs-12">
									<div class="name-item ">
										<?php
										$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

										if (!$_product->is_visible())
											echo wp_kses_post($thumbnail);
										else
											printf('<a href="%s">%s</a>', $_product->get_permalink($cart_item), $thumbnail);
										?>
										<div class="product-info">
											<?php
											if (!$_product->is_visible())
												echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
											else
												echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink($cart_item), $_product->get_title()), $cart_item, $cart_item_key);

											// Meta data
											echo WC()->cart->get_item_data($cart_item);

											// Backorder notification
											if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
												echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'inmedical') . '</p>';
											?>
										</div>
										<div class="clear_wc"></div>
									</div>
								</div>

								<div class="item col-md-2 col-sm-2 col-xs-12">
									<div class="price-item">
										<span class="cart-price">
											<?php
											echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
											?>
										</span>
									</div>
								</div>
								
								<div class="item col-md-2 col-sm-2 col-xs-12">
									<div class="qty-item">
										<?php
										if ($_product->is_sold_individually()) {
											$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
										} else {
											$product_quantity = woocommerce_quantity_input(array(
												'input_name' => "cart[{$cart_item_key}][qty]",
												'input_value' => $cart_item['quantity'],
												'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
												'min_value' => '0'
											), $_product, false);
										}

                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
										?>
									</div>
								</div>
								
								
								<div class="item col-md-2 col-sm-2 col-xs-12">
									<div class="price-item">
										<span class="cart-price">
										<?php
										echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
										?>
										</span>
									</div>
								</div>
								
								
								<div class="item col-md-1 col-sm-1 col-xs-12">
									<div class="delete-item">
										<?php
                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                            '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                            esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'inmedical' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ), $cart_item_key );
										?>
									</div>
								</div>
						</div>
                    <?php
                    }
                }

                do_action('woocommerce_cart_contents');
                ?>
				
				</div>
				
                <?php if (WC()->cart->coupons_enabled()) { ?>
				<div class="woo-cart-coupon">
                    <div class="row-title woo-coupon-row">

                        <div class=""><span><?php esc_attr_e('Coupon', 'inmedical'); ?></span></div>
                    </div>
                    <div class="row-item woo-coupon-row">
                        <div class="item">
                        <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                               placeholder="<?php esc_attr_e('Coupon code', 'inmedical'); ?>"/>
                            <input type="submit"
                                   class="button"
                                   name="apply_coupon"
                                   value="<?php esc_attr_e('Apply Coupon', 'inmedical'); ?>"/>
                            <?php do_action('woocommerce_cart_coupon'); ?>

                            <button type="submit" class="button update-cart-button" class="button" name="update_cart" value="<?php esc_attr_e('Update Cart', 'inmedical'); ?>"><i class="fa fa-refresh"></i> <?php esc_html_e('Update Cart', 'inmedical'); ?></button>
                            <div class="clear"></div>
						</div>

                    </div>
				</div>
                <?php } ?>

            </div>

            <div class="clearfix"></div>
            <?php do_action('woocommerce_cart_actions'); ?>

            <?php wp_nonce_field('woocommerce-cart'); ?>
            <?php do_action('woocommerce_after_cart_contents'); ?>

            <?php do_action('woocommerce_after_cart_table'); ?>

        </form>

        <div class="cart-collaterals">
<!--            <div class="col-md-12">-->
                <?php do_action('woocommerce_cart_collaterals'); ?>
<!--            </div>-->
        </div>

        <?php do_action('woocommerce_after_cart'); ?>
    </div>
</div>