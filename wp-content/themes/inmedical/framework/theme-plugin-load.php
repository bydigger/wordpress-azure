<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.4.0
 * @author     Thomas Griffin <thomasgriffinmedia.com>
 * @author     Gary Jones <gamajo.com>
 * @copyright  Copyright (c) 2014, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

if(!function_exists('inwave_plugins_load')){
    add_action( 'tgmpa_register', 'inwave_plugins_load' );

    function inwave_plugins_load() {

        /**
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(

            array(
                'name'               => esc_html__('Visual Composer', 'inmedical'),
                'slug'               => 'js_composer',
                'source'             => get_template_directory() . '/framework/plugins/js_composer.zip',
                'required'           => true,
                'version'            => '5.4.7',
                'force_activation'   => false,
                'force_deactivation' => false,
                'copy_folder' => true,
                'external_url'       => '',
            ),
            array(
                'name'               => esc_html__('Revolution Slider', 'inmedical'),
                'slug'               => 'revslider',
                'source'             => get_template_directory() . '/framework/plugins/revslider.zip',
                'required'           => true,
                'version'            => '5.4.7.2',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
            ),
            array(
                'name'               => esc_html__('InMedical Events', 'inmedical'),
                'slug'               => 'inmedical',
                'source'             => get_template_directory() . '/framework/plugins/inmedical.zip',
                'required'           => true,
                'version'            => '1.0.7',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
            ),
            array(
                'name'               => esc_html__('InWave Common for Medical', 'inmedical'),
                'slug'               => 'inwave-common',
                'source'             => get_template_directory() . '/framework/plugins/inwave-common.zip',
                'required'           => true,
                'version'            => '1.0.4',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
            ),
            array(
                'name'      => esc_html__('Contact form 7', 'inmedical'),
                'slug'      => 'contact-form-7',
                'required'  => true,
            ),
            array(
                'name'      => esc_html__('Woocommerce', 'inmedical'),
                'slug'      => 'woocommerce',
                'required'  => false,
            ),
            array(
                'name'      => esc_html__('YITH Woocommerce Wishlist', 'inmedical'),
                'slug'      => 'yith-woocommerce-wishlist',
                'required'  => false,
            ),

        );

        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */

        $template = get_template();
        $config = array(
            'id'           => $template,                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to pre-packaged plugins.
            'menu'         => $template.'-install-plugins', // Menu slug.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
        );

        tgmpa( $plugins, $config );

    }
}

