<?php
/**
 * Shop breadcrumb
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if (!defined('ABSPATH')) {
    exit;
}
global $smof_data;
if (!$smof_data['breadcrumb']) {
    return;
}
?>
<div class="breadcrumbs">
    <ul>
        <?php
        if ($breadcrumb) {
            foreach ($breadcrumb as $key => $crumb) {
                if ($key == 0) {
                    echo '<li class="home"><a href="'.esc_url(home_url('/')).'">';
                    echo esc_html__('Home','inmedical');
                    echo "</a></li>";
                    echo '<li><span>//</span></li>';
                    continue;
                }
                if (!empty($crumb[1]) && sizeof($breadcrumb) !== $key + 1) {
                    echo '<li class="category-1"><a href="' . esc_url($crumb[1]) . '">' . esc_html($crumb[0]) . '</a></li>';
                } else {
                    echo '<li class="category-2">' . esc_html($crumb[0]) . '</li>';
                }
                if (sizeof($breadcrumb) !== $key + 1) {
                    echo '<li><span>//</span></li>';
                }
            }
        }
        ?>
    </ul>

</div>