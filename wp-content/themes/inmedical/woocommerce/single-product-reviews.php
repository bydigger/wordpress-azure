<?php
/**
 * Display single product reviews (comments)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews">
	<div id="comments">
        <!--
		<h2><?php
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) )
				printf( _n( '%s review for %s', '%s reviews for %s', $count, 'inmedical' ), $count, get_the_title() );
			else
				esc_html_e( 'Reviews', 'inmedical' );
		?></h2>
-->
		<?php if ( have_comments() ) : ?>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'inmedical' ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();

					$comment_form = array(
						'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'inmedical' ) : esc_html__( 'Be the first to review', 'inmedical' ) . ' &ldquo;' . get_the_title() . '&rdquo;',
						'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'inmedical' ),
						'comment_notes_before' => '',
						'comment_notes_after'  => '',
						'fields'               => array(
							'author' => '<div class="row"><div class="col-sm-6 col-xs-12"><div class="comment-form-author"><input id="author" class="woo-review-form-field" name="author" placeholder="Name (*)" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div></div>',
							'email'  => '<div class="col-sm-6 col-xs-12"><div class="comment-form-email"><input id="email" class="woo-review-form-field" name="email" placeholder="Email (*)" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div></div></div>',
						),
						'label_submit'  => esc_html__( 'Submit', 'inmedical' ),
						'class_submit'  =>'btn-submit',
						'id_submit'  =>'btn-submit',
						'logged_in_as'  => '',
						'comment_field' => ''
					);
					
					$comment_form['comment_field'] = '<div class="row"><div class="col-xs-12"><div class="comment-form-comment"><textarea id="comment" class="control" placeholder="Your Review (*)" name="comment" cols="45" rows="8" aria-required="true"></textarea></div></div></div>'."";

                    if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
                        $comment_form['must_log_in'] = '<p class="must-log-in">' .  sprintf( wp_kses_post(__( 'You must be <a href="%s">logged in</a> to post a review.', 'inmedical' )), esc_url( $account_page_url ) ) . '</p>';
                    }

					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						$comment_form['comment_field'] .= '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'inmedical' ) .'</label><select name="rating" id="rating">
							<option value="">' . esc_html__( 'Rate&hellip;', 'inmedical' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'inmedical' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'inmedical' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'inmedical' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'inmedical' ) . '</option>
							<option value="1">' . esc_html__( 'Very Poor', 'inmedical' ) . '</option>
						</select></div>';
					}
					
					'<div class="woo-review-rate">'.comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) ).'</div>';
					
				?>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'inmedical' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
