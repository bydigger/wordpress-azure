<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package inmedical
 */

if(is_active_sidebar('sidebar-department-detail')){
?>
<div id="secondary" class="widget-area" role="complementary">
    <?php dynamic_sidebar('sidebar-department-detail'); ?>
</div><!-- #secondary -->
<?php } ?>