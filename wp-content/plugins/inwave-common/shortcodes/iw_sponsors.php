<?php

/*
 * @package Inwave Event
 * @version 1.0.0
 * @created May 5, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of sponsors
 *
 * @developer Hien Tran
 */
if (!class_exists('Inwave_Sponsors')) {

    class Inwave_Sponsors extends Inwave_Shortcode2{

        protected $name = 'inwave_sponsors';
        protected $name2 = 'inwave_sponsor_item';
        protected $sponsors;
        protected $sponsor_item;
        protected $style;

        function init_params()
        {
            return array(
                "name" => __("Sponsors", 'inwavethemes'),
                "base" => $this->name,
                "content_element" => true,
                'category' => 'Custom',
                'icon' => 'iw-default',
                "description" => __("Add a set of sponsor and give some custom style.", "inwavethemes"),
                "as_parent" => array('only' => $this->name2),
                "show_settings_on_create" => true,
                "js_view" => 'VcColumnView',
                "params" => array(
                    array(
                        "type" => "dropdown",
                        "class" => "iw-testimonials-style",
                        "heading" => "Style",
                        "admin_label" =>true,
                        "param_name" => "style",
                        "value" => array(
                            "Style 1" => "style1",
                            "Style 2" => "style2",
                        )
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "value" => "",
                        "description" => __("Write your own CSS and mention the class name here.", "inwavethemes"),
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop", "inwavethemes"),
                        "param_name" => "item_desktop",
                        "value" => '6',
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop Small", "inwavethemes"),
                        "param_name" => "item_desktop_small",
                        "value" => '3',
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => __("AutoPlay Slider", "inwavethemes"),
                        "param_name" => "auto_play",
                        "value" => array(
                            'Yes' => 'yes',
                            'No' => 'no'
                        )
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __( 'CSS box', 'js_composer' ),
                        'param_name' => 'css',
                        'group' => __( 'Design Options', 'js_composer' )
                    )
                )
            );
        }

        function init_params2() {
            return array(
                "name" => __("Sponsor Item", 'inwavethemes'),
                "base" => $this->name2,
                "class" => "inwave_sponsor_item",
                'icon' => 'iw-default',
                'category' => 'Custom',
//                "as_child" => array('only' => $this->name),
                "description" => __("Add a list of sponsors with some content and give some custom style.", "inwavethemes"),
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "type" => "attach_image",
                        "heading" => __("Sponsor Image", "inwavethemes"),
                        "param_name" => "image",
                        "value" => "",
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Link Sponsor", "inwavethemes"),
                        "value" => "",
                        "param_name" => "link",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "value" => "",
                        "description" => __("Write your own CSS and mention the class name here.", "inwavethemes"),
                    ),

                )
            );
        }

        // Shortcode handler function for list
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $style = $class = $auto_play = $item_desktop = $item_desktop_small = '';
            //$id = 'iwt-' . rand(10000, 99999);
            extract(shortcode_atts(array(
                "style" => "",
                "class" => "",
                "auto_play" => "",
                "item_desktop" => "",
                "item_desktop_small" => "",
                'css' => '',
            ), $atts));

            $class = $style;

            $data_plugin_options = array(
                "navigation"=>true,
                "autoHeight"=>false,
                "pagination"=>false,
                "autoPlay"=>false,
                "paginationNumbers"=>false,
                "items"=>$item_desktop,
                "itemsDesktop"=>array(1199, $item_desktop),
                "itemsDesktopSmall"=>array(991, $item_desktop_small),
                "itemsTablet"=>array(767, 2),
                "itemsTabletSmall"=>false,
                "itemsMobile"=>array(479, 1),
                "navigationText" => array('<i class="ion-chevron-left"></i>', '<i class="ion-chevron-right"></i>')
            );

            switch ($style){
                case 'style1':
                case 'style2':
                    $output = '<div class="iw-sponsors '.$class.'">';
                    $output .= '<div class="owl-carousel" data-plugin-options="'.htmlspecialchars(json_encode($data_plugin_options)).'">';
                    $output .= do_shortcode($content);
                    $output .= '</div>';
                    $output .= '</div>';
                break;
            }

            return $output;
        }

        // Shortcode handler function for item
        function init_shortcode2($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name2, $atts ) : $atts;

            $output = $image = $link = $class = '';
            extract(shortcode_atts(array(
                'image' => '',
                'link' => '',
                'class' => ''
            ), $atts));

            if ($image) {
                $img = wp_get_attachment_image_src($image, 'full');
                $image = '<img src="' . $img[0] . '" alt=""/>';
            }

            $output.= '<div class="iw-sponsor-item">';
            if($image){
                if ($link) {
                    $output.= '<div class="testi-image"><a href="'.$link.'" target="_blank">' . $image . '</a></div>';
                }
                else {
                    $output.= '<div class="testi-image">' . $image . '</div>';
                }
            }
            $output.= '</div>';
            return $output;
        }
    }
}

new Inwave_Sponsors;
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Sponsors extends WPBakeryShortCodesContainer {}
}
