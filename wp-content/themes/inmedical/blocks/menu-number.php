<!--Menu desktop-->
<?php
if(has_nav_menu( 'primary' )) {
    $theme_menu = Inwave_Helper::getPostOption('primary_menu');
    wp_nav_menu(array(
        "container_class" => "iw-main-menu",
        'menu' => $theme_menu,
        'theme_location' => 'primary',
        "menu_class" => "iw-nav-menu",
        "walker" => new Inwave_Nav_Walker_Hidden_Number(),
    ));
}
?>
