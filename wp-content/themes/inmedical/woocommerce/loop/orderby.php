<?php
/**
 * Show options for ordering
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<form class="woocommerce-ordering" method="get">
    <div class="sorter">
        <div class="sort-by">
	<select name="orderby" class="orderby">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
	</select>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' === $key || 'submit' === $key ) {
				continue;
			}
			if ( is_array( $val ) ) {
				foreach( $val as $innerVal ) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
	?>

</div>

<p class="view-mode">
    <?php if ($_SESSION['product-category-layout'] == 'grid') {
        ?>
        <strong title="Grid" class="grid-active"><i class="fa fa-th-large"></i></strong>
        <a href="#" title="List" class="list" onclick="submitProductsLayout('row');return false;"><i class="fa fa-th-list"></i></a>
    <?php
    } else {
        ?>
        <a href="#" title="Grid" class="list" onclick="submitProductsLayout('grid');return false;"><i class="fa fa-th-large"></i></a>
        <strong title="List" class="grid-active"><i class="fa fa-th-list"></i></strong>
    <?php
    }
    ?>
    <input type="hidden" class="product-category-layout" name="category-layout" value="">
</p>

</div>
</form>


