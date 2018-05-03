<?php
/**
 * The Template for displaying all single posts
 * @package inmedical
 */

get_header();

$sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'sidebar_position');

?>
<div class="page-content">
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr(inwave_get_classes('container',$sidebar_position))?> blog-content single-content">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('content', 'single'); ?>
                        <?php
                        // If comments are open or we have at least one comment, load up the comment template
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                    <?php endwhile; // end of the loop. ?>
                </div>
                <?php if ($sidebar_position && $sidebar_position != 'none') { ?>
                    <div class="<?php echo esc_attr(inwave_get_classes('sidebar', $sidebar_position))?> default-sidebar">
                        <?php get_sidebar(); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>