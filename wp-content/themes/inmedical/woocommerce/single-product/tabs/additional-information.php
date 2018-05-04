<?php
/**
 * Additional Information tab
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$heading = '';

?>

<?php if ( $heading ): ?>
	<h2><?php echo esc_html($heading); ?></h2>
<?php endif; ?>

<?php $product->list_attributes(); ?>
