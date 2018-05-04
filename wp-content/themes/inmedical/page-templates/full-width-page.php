<?php
/**
 * Template Name: Full width
 * This is the template that is used for the Home page, fullwith and no sidebar
 */

get_header();
?>
<div class="contents-main" id="contents-main">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('content', 'page'); ?>
    <?php endwhile; // end of the loop. ?>
</div>
<?php get_footer(); ?>
