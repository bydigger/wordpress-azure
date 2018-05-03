<?php
$theme_menu = Inwave_Helper::getPostOption('primary_menu');
if(!$theme_menu){
    $locations = get_nav_menu_locations();
    $menu_id = isset($locations[ 'primary' ]) ? $locations[ 'primary' ] : '' ;
    $nav_menu = wp_get_nav_menu_object($menu_id);
}else{
    $nav_menu = wp_get_nav_menu_object($theme_menu);
}
?>
<nav class="off-canvas-menu off-canvas-menu-scroll st-menu">
    <h2 class="canvas-menu-title"><?php echo ( $nav_menu ? esc_html(  $nav_menu->name) : ''); ?> <span class="text-right"><a href="#" id="off-canvas-close"><i class="fa fa-times"></i></a></span></h2>
    <?php
    wp_nav_menu(array(
        "container"             => "",
        'menu'                  => $theme_menu,
        'theme_location'        => 'primary',
        "menu_class"            => "canvas-menu",
        "walker"                => new Inwave_Nav_Walker_Mobile(),
        "hidden_number"         => true
    ));
    ?>
</nav>

