<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<li itemprop="reviews" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

    <div id="comment-<?php comment_ID(); ?>" class="comment_container">

        <div class="woo-comment-avt">
            <?php
            /**
             * The woocommerce_review_before hook
             *
             * @hooked woocommerce_review_display_gravatar - 10
             */
            do_action('woocommerce_review_before', $comment);
            ?>
        </div>
        <div class="woo-comment-detail">


            <?php
            /**
             * The woocommerce_review_meta hook.
             *
             * @hooked woocommerce_review_display_meta - 10
             */
            do_action('woocommerce_review_meta', $comment);

            do_action('woocommerce_review_before_comment_text', $comment);
            /**
             * The woocommerce_review_comment_text hook
             *
             * @hooked woocommerce_review_display_comment_text - 10
             */
            do_action('woocommerce_review_comment_text', $comment);

            do_action('woocommerce_review_after_comment_text', $comment);
            /**
             * The woocommerce_review_before_comment_meta hook.
             *
             * @hooked woocommerce_review_display_rating - 10
             */
            do_action('woocommerce_review_before_comment_meta', $comment);
            ?>
        </div>
        <div class="clear_wc"></div>
    </div>

