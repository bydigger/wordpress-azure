<?php
/**
 * Created by PhpStorm.
 * User: TruongDX
 * Date: 11/10/2015
 * Time: 11:44 AM
 */

    $inwave_theme_option = Inwave_Helper::getConfig();
    $show_breadcrums = Inwave_Helper::getPostOption('breadcrumbs', 'breadcrumbs');
?>
<footer class="iw-footer iw-footer-default <?php echo is_page_template( 'page-templates/home-page-kid.php' ) || is_page_template( 'page-templates/home-page-plastic-surgery.php' ) || is_page_template( 'page-templates/home-page-pet.php' ) ? 'iw-footer-v2' : '' ?>">
    <div class="iw-footer-middle">
        <div class="container">
            <div class="row">
                <?php
                switch($inwave_theme_option['footer_number_widget'])
                {
                    case '1':
                        if(is_active_sidebar('footer-widget-1')) {
                            dynamic_sidebar('footer-widget-1');
                        }
                        break;
                    case '2':
                        if(is_active_sidebar('footer-widget-1')) {
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">';
                            dynamic_sidebar('footer-widget-1');
                            echo '</div>';
                        }
                        if(is_active_sidebar('footer-widget-2')) {
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12 last">';
                            dynamic_sidebar('footer-widget-2');
                            echo '</div>';
                        }
                        break;
                    case '3':
                        if(is_active_sidebar('footer-widget-1')) {
                            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-sx-12">';
                            dynamic_sidebar('footer-widget-1');
                            echo '</div>';
                        }
                        if(is_active_sidebar('footer-widget-2')) {
                            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-sx-12">';
                            dynamic_sidebar('footer-widget-2');
                            echo '</div>';
                        }
                        if(is_active_sidebar('footer-widget-3')) {
                            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-sx-12 last">';
                            dynamic_sidebar('footer-widget-3');
                            echo '</div>';
                        }
                        break;
                    case '4':
                        if(is_active_sidebar('footer-widget-1') && is_active_sidebar('footer-widget-2') && is_active_sidebar('footer-widget-3') && is_active_sidebar('footer-widget-4')){
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">';
                            echo '<div class="footer-left">';
                            echo '<div class="row">';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-1');
                            echo '</div>';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-2');
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">';
                            echo '<div class="footer-right">';
                            echo '<div class="row">';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-3');
                            echo '</div>';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-4');
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            break;
                        }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="iw-copy-right">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <p><?php echo wp_kses_post($inwave_theme_option['footer-copyright']) ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>
