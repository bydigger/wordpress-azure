<?php
/**
 * The template for displaying Category pages
 * @package inmedical
 */
get_header();
$sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'sidebar_position');
?>
<div class="page-content iw-category">
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr(inwave_get_classes('container', $sidebar_position))?> blog-content">
                    <?php if ( have_posts() ) : ?>
                    <?php while (have_posts()) : the_post();
                            get_template_part( 'content', get_post_format() );
                     endwhile; // end of the loop. ?>
                        <?php get_template_part( '/blocks/paging'); ?>
                    <?php else :
                        // If no content, include the "No posts found" template.
                        get_template_part( 'content', 'none' );
                    endif;?>
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