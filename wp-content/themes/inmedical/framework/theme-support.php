<?php
/**
 * inWave Framework functions and definitions.
 *
 */
if ( ! isset( $content_width ) ) {
    $content_width = 1024;
}

/**
 * Sets up theme defaults and registers the various WordPress features that
 * CoronaThemes Framework supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since CoronaThemes Framework 1.0
 */

if( !function_exists( 'inwave_setup' ) ) {

    /*
     * setup text domain and style
    */
    function inwave_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on inevent, use a find and replace
         * to change 'inmedical' to the name of your theme in all the template files
         */
        load_theme_textdomain('inmedical', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        //Enables Post Thumbnails support
        add_theme_support( "post-thumbnails" );

        //Custom header
        add_theme_support( "custom-header", array());

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        //unregister_nav_menu('mega_main_sidebar');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
        ));

        /*
         * Enable support for Post Formats.
         * See http://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'image', 'gallery', 'video', 'quote', 'link'
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('inwave_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        //woocommerce
        add_theme_support( 'woocommerce' );
//        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        //jetpack
        add_theme_support( 'infinite-scroll', array(
            'container' => 'main',
            'footer'    => 'page',
        ) );
    }

    add_action( 'after_setup_theme', 'inwave_setup' );
}

//suport retina
add_filter( 'wp_generate_attachment_metadata', 'inwave_retina_support_attachment_meta', 10, 2 );
add_filter( 'delete_attachment', 'inwave_delete_retina_support_images');

/**
 * Retina images
 *
 * This function is attached to the 'wp_generate_attachment_metadata' filter hook.
 */
function inwave_retina_support_attachment_meta( $metadata, $attachment_id ) {
    global $inwave_theme_option;

    if(!isset($inwave_theme_option['retina_support']) || !$inwave_theme_option['retina_support']){
        return $metadata;
    }

    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    inwave_retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }

    return $metadata;
}

/**
 * Create retina-ready images
 *
 * Referenced via retina_support_attachment_meta().
 */
function inwave_retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );

            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );

            $info = $resized_file->get_size();

            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}

/**
 * Delete retina-ready images
 *
 * This function is attached to the 'delete_attachment' filter hook.
 */
function inwave_delete_retina_support_images( $attachment_id ) {
    global $inwave_theme_option;

    if(!isset($inwave_theme_option['retina_support']) || !$inwave_theme_option['retina_support']){
        return;
    }

    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
    $path = pathinfo( $meta['file'] );
    foreach ( $meta as $key => $value ) {
        if ( 'sizes' === $key ) {
            foreach ( $value as $sizes => $size ) {
                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
                if ( file_exists( $retina_filename ) )
                    unlink( $retina_filename );
            }
        }
    }
}

/** Add RSS Feed For Event */
function inwave_rss_request($qv) {
if(isset($_REQUEST['type']) && $_REQUEST['type']=='event'){	
	if (isset($qv['feed']) && !isset($qv['post_type']))	$qv['post_type'] = array('iwevent');
}
return $qv;
}

add_filter('request', 'inwave_rss_request');

/** Add Featured image for RSS Content */
function inwave_rss_image($content) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ){
	$content = '<div>' . get_the_post_thumbnail( $post->ID, 'medium', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
	}
	return $content;
}
 
add_filter('the_excerpt_rss', 'inwave_rss_image');
add_filter('the_content_feed', 'inwave_rss_image');
