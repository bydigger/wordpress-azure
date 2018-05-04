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

<div class="header header-version-3 header-style-default <?php if($header_sticky && $header_sticky != 'no') { echo 'header-sticky' ;} ?>">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
	
    <div class="navbar navbar-default iw-header">
        <form class="search-form-header theme-bg" method="get" action="<?php echo esc_url( home_url( '/' ) )?>">
            <div class="search-box-header">
                <input type="search" title="<?php echo esc_attr_x( 'Enter your keyword', 'label','inmedical' ) ?>" value="<?php echo get_search_query() ?>" name="s" placeholder="<?php echo esc_attr_x( 'Enter your keyword', 'placeholder','inmedical' );?>" class="top-search">
            </div>
        </form>
        <div class="container">
		<div class="wrapper-color">
            <div class="row">
				<div class="col-md-3 col-xs-6 col-sm-7">
					<h1 class="iw-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr(bloginfo('name')); ?>">
							<img class="main-logo" src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr(bloginfo('name')); ?>">
							<img class="sticky-logo" src="<?php echo esc_url($logo_sticky); ?>" alt="<?php esc_attr(bloginfo('name')); ?>">
						</a>
					</h1>
				</div>
				<div class="col-md-9 col-xs-6 col-sm-5">
					<div class="iw-menu-header-default">
						<nav class="main-menu iw-menu-main nav-collapse">
							<?php get_template_part('blocks/menu'); ?>
						</nav>
						<div class="iw-search-cart">
							<?php if($show_search_form == 'yes' || $show_search_form == '1') { ?>
								<div class="search-form">
									<button>
										<i class="ion-android-search"></i>
									</button>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
            </div>
        </div></div>
    </div>
</div>

<!--End Header-->