<?php
	$inwave_theme_option = Inwave_Helper::getConfig();
    $header_sticky = Inwave_Helper::getPostOption('header_sticky', 'header_sticky');
    $show_make_appointment_button = Inwave_Helper::getPostOption('show_make_appointment', 'show_make_appointment');
    $logo = Inwave_Helper::getPostOption('logo', 'logo');
    $logo_sticky = Inwave_Helper::getPostOption('logo_sticky', 'logo_sticky');
    if (function_exists('WC')) {
        $cartUrl = wc_get_cart_url();
        $cartTotal = WC()->cart->cart_contents_count;
    }
?>

<div class="header header-version-5 header-style-default <?php if ($header_sticky && $header_sticky != 'no') { echo 'header-sticky';} ?>">
    <!-- the header -->
    <div class="header-inner">
        <div class="logo-header">
            <h1 class="logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr(bloginfo('name')); ?>">
                    <img class="main-logo" src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr(bloginfo('name')); ?>">
                    <img class="sticky-logo" src="<?php echo esc_url($logo_sticky); ?>"
                         alt="<?php esc_attr(bloginfo('name')); ?>">
                </a>
            </h1>
        </div>
        <div class="header-bottom">
            <div class="menu-header">
                <div class="iw-menu-default main-menu">
                    <div class="iw-header-menu-wrapper">
                            <?php
                                if(has_nav_menu( 'primary' )) {
                                    $theme_menu = Inwave_Helper::getPostOption('primary_menu');
                                    wp_nav_menu(array(
                                        "container_class" => "main-menu",
                                        'menu' => $theme_menu,
                                        'theme_location' => "primary",
                                        "menu_class" => "canvas-menu",
                                        "walker" => new Inwave_Nav_Walker(),
                                    ));
                                }
                                ?>
                    </div>

                </div>
            </div>
            <div class="make-appointment-header-v5">
                <span class="off-canvas-btn">
                    <i class="fa fa-bars"></i>
                </span>
                <?php if ($show_make_appointment_button && $show_make_appointment_button != 'no'): ?>
                    <div class="header-book-button">
                        <a href="<?php echo esc_url($inwave_theme_option['make_appointment_link']) ?>">
                            <span data-hover="<?php echo esc_attr($inwave_theme_option['make_appointment_text']); ?>">
                                <?php echo wp_kses_post($inwave_theme_option['make_appointment_text']); ?>
                            </span>
                            <i class="ion-calendar"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="copyright-social-header">
                <div class="social-header-v5">
                    <span class="social-title"><?php echo esc_html__("Connect with us", 'inmedical') ?></span>
                    <?php echo inwave_get_social_link(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Header-->