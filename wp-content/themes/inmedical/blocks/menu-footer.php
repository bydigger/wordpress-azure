<?php
/**
 * Created by PhpStorm.
 * User: TruongDX
 * Date: 11/11/2015
 * Time: 5:25 PM
 */
 if(!has_nav_menu('footer-menu')){
    return;
 }
 wp_nav_menu( array(
    'theme_location' => 'footer-menu',
    'container' => 'nav',
    'container_class' => 'iw-main-nav'
) );