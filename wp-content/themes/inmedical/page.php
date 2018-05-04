<?php
/**
 * The template for displaying pages
 * @package inmedical
 */
get_header();

$sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'sidebar_position');
$sidebar_name = Inwave_Helper::getPostOption('sidebar_name');
?>
<div class="contents-main" id="contents-main">
<div class="container">
    <div class="row">
        <div class="<?php echo esc_attr(inwave_get_classes('container', $sidebar_position))?>">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('content', 'page'); ?>
                <?php
                // If comments are open or we have at least one comment, load up the comment template
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            <?php endwhile; // end of the loop. ?>
        </div>
        <?php if ($sidebar_position && $sidebar_position != 'none') { ?>
            <div class="<?php echo esc_attr(inwave_get_classes('sidebar', $sidebar_position))?> iw-sidebar <?php echo esc_attr($sidebar_name); ?>">
                <?php get_sidebar(); ?>
            </div>
        <?php } ?>
    </div>
</div>
</div>
<?php get_footer(); ?>
