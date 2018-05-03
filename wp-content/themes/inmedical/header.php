<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package inmedical
 */
$header_layout = Inwave_Helper::getPostOption('header_option' , 'header_layout');
$use_scroll_footer = Inwave_Helper::getPostOption('use_scroll_footer', 'use_scroll_footer');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php esc_attr(bloginfo('charset')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php esc_url(bloginfo('pingback_url')); ?>">
    <?php wp_head(); ?>
</head>
<body id="page-top" <?php body_class(); ?>>
<?php
$show_preload = Inwave_Helper::getPostOption('show_preload' , 'show_preload');
if($show_preload && $show_preload != 'no'){
    echo '<div id="preview-area">
        <div id="preview-spinners">
            <div class="sk-folding-cube">
              <div class="sk-cube1 sk-cube"></div>
              <div class="sk-cube2 sk-cube"></div>
              <div class="sk-cube4 sk-cube"></div>
              <div class="sk-cube3 sk-cube"></div>
            </div>
        </div>
    </div>';
}
?>
<?php
get_template_part('blocks/canvas', 'menu');
?>

<div class="wrapper st-body <?php echo ($header_layout == 'v5' ? 'header-style-5' : ''); ?> <?php echo ($use_scroll_footer ? 'iw-content-home-scroll' : '') ?>">
    <?php
        $header_layout = Inwave_Helper::getPostOption('header_option' , 'header_layout');
        if(!$header_layout){
            $header_layout = 'default';
        }

        if($header_layout != 'none'){
            get_template_part('headers/header', $header_layout);
        }
    ?>
    <?php
    if(function_exists('putRevSlider')){
        $slider = Inwave_Helper::getPostOption('slider');
        if($slider){
            ?>
            <div class="slide-container <?php echo esc_attr($slider)?>">
                <?php putRevSlider($slider); ?>
            </div>
            <?php
        }
    }
    ?>
    <?php
        if(!is_page_template( 'page-templates/home-page.php' ) && !is_page_template( 'page-templates/home-page-pet.php' ) && !is_page_template( 'page-templates/home-page-kid.php' ) && !is_page_template( 'page-templates/home-page-plastic-surgery.php' )){
            get_template_part('blocks/page', 'heading');
        }
    ?>

