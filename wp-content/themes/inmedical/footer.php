<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package inmedical
 */

$footer_layout = Inwave_Helper::getPostOption('footer_option', 'footer_option');
$footer_layout = $footer_layout ? $footer_layout : 'default';
$inwave_theme_option = Inwave_Helper::getConfig();
$use_scroll_footer = Inwave_Helper::getPostOption('use_scroll_footer', 'use_scroll_footer');
$content_footer_scroll = Inwave_Helper::getPostOption('content_footer_scroll', 'content_footer_scroll');
$bg_footer_scroll = Inwave_Helper::getPostOption('bg_footer_scroll', 'bg_footer_scroll');

get_template_part('footer/footer', $footer_layout);
?>
</div> <!--end .content-wrapper -->
<?php
if($use_scroll_footer) : ?>
        <div class="iw-footer-scroll">
            <div class="iw-footer-scroll-inner">
                <?php if ($inwave_theme_option['content_footer_scroll']) : ?>
                    <div class="iw-footer-scroll-content" style='background-image: url("<?php echo esc_url($bg_footer_scroll); ?>")' ><?php echo wp_kses_post($content_footer_scroll); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="iw-overlay-scroll"></div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
