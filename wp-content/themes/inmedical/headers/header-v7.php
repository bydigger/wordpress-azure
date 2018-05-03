<?php

$inwave_theme_option = Inwave_Helper::getConfig();
$header_sticky = Inwave_Helper::getPostOption('header_sticky', 'header_sticky');
$show_search_form = Inwave_Helper::getPostOption('show_search_form', 'show_search_form');
$show_make_appointment_button = Inwave_Helper::getPostOption('show_make_appointment', 'show_make_appointment');
$logo = Inwave_Helper::getPostOption('logo', 'logo');
$logo_sticky = Inwave_Helper::getPostOption('logo_sticky', 'logo_sticky');
if (function_exists('WC')) {
    $cartUrl = wc_get_cart_url();
    $cartTotal = WC()->cart->cart_contents_count;
}
?>

<div class="header header-version-v7 <?php if($header_sticky && $header_sticky != 'no') { echo 'header-sticky' ;} ?>">
    <div class="top-bar-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="iw-top-bar-wrap clearfix">
                        <div class="social-header">
                            <?php echo inwave_get_social_link(); ?>
                        </div>
                        <div class="iw-top-bar">
                            <?php if ($inwave_theme_option['header-contact']) ; ?>
                            <div class="contact"><?php echo(stripslashes($inwave_theme_option['header-contact'])); ?></div>
                            <?php ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="iw-logo-appointment">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-7">
                    <h1 class="logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php esc_attr(bloginfo('name')); ?>">
                            <img class="main-logo" src="<?php echo esc_url($logo); ?>"
                                 alt="<?php esc_attr(bloginfo('name')); ?>">
                            <img class="sticky-logo" src="<?php echo esc_url($logo_sticky); ?>"
                                 alt="<?php esc_attr(bloginfo('name')); ?>">
                        </a>
                    </h1>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-5">
                    <div class="language-appointment-header">
                        <span class="off-canvas-btn">
                            <i class="fa fa-bars"></i>
                        </span>
<!--                        --><?php //if ($show_make_appointment_button && $show_make_appointment_button != 'no'): ?>
<!--                            <div class="header-book-button">-->
<!--                                <a href="--><?php //echo esc_url($inwave_theme_option['make_appointment_link']) ?><!--">-->
<!--                                    <span data-hover="--><?php //echo esc_attr($inwave_theme_option['make_appointment_text']); ?><!--">-->
<!--                                        --><?php //echo wp_kses_post($inwave_theme_option['make_appointment_text']); ?>
<!--                                    </span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                            --><?php
//                        endif; ?>
<!--                        --><?php //if (function_exists('WC')) { ?>
<!--                            <div class="cart-quickaccess">-->
<!--                                <a href="--><?php //echo esc_url($cartUrl); ?><!--" class="cart-icon">-->
<!--                                    <i class="icon ion-android-cart"></i>-->
<!--                                    <span class="cart-product-number">--><?php //echo (int)$cartTotal; ?><!--</span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        --><?php //} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="navbar navbar-default iw-header">
        <div class="container">
            <div class="wrapper-color">
                <div class="row">
                    <div class="col-md-4 col-xs-6 col-sm-7">
                        <h1 class="iw-logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr(bloginfo('name')); ?>">
                                <img class="main-logo" src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr(bloginfo('name')); ?>">
                                <img class="sticky-logo" src="<?php echo esc_url($logo_sticky); ?>" alt="<?php esc_attr(bloginfo('name')); ?>">
                            </a>
                        </h1>
                    </div>
                    <div class="col-md-8 col-xs-6 col-sm-5">
                        <div class="iw-menu-header-default">
                            <nav class="main-menu iw-menu-main nav-collapse">
                                <?php get_template_part('blocks/menu'); ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </div></div>
    </div>
</div>

<!--End Header-->