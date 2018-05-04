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
 * Description of testimonials
 *
 * @developer duongca
 */
if (!class_exists('Inwave_Testimonials')) {

    class Inwave_Testimonials extends Inwave_Shortcode2{

        protected $name = 'inwave_testimonials';
        protected $name2 = 'inwave_testimonial_item';
        protected $testimonials;
        protected $testimonial_item;
        protected $style;


        function register_scripts()
        {
            wp_enqueue_script('iw-testimonials', plugins_url('inwave-common/assets/js/iw-testimonials.js'), array('jquery'), INWAVE_COMMON_VERSION);
            wp_enqueue_style('iw-testimonials', plugins_url('inwave-common/assets/css/iw-testimonials.css'), array(), INWAVE_COMMON_VERSION);
        }

        function init_params()
        {
            return array(
                "name" => __("Testimonials", 'inwavethemes'),
                "base" => $this->name,
                "content_element" => true,
                'category' => 'Custom',
                'icon' => 'iw-default',
                "description" => __("Add a set of testimonial and give some custom style.", "inwavethemes"),
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
                            "Style 1 - Carousel slider" => "style1",
                        )
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Item active", "inwavethemes"),
                        "value" => "0",
                        "description" => __("Choose item active", "inwavethemes"),
                        "param_name" => "item_active",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "value" => "",
                        "description" => __("Write your own CSS and mention the class name here.", "inwavethemes"),
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
                "name" => __("Testimonial Item", 'inwavethemes'),
                "base" => $this->name2,
                "class" => "inwave_testimonial_item",
                'icon' => 'iw-default',
                'category' => 'Custom',
                "description" => __("Add a list of testimonials with some content and give some custom style.", "inwavethemes"),
                "show_settings_on_create" => true,
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
                            "Style 3" => "style3",
                            "Style 4" => "style4",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style1",
						"value" => get_template_directory_uri() . '/assets/images/shortcodes/testimonial-style4.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
					array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style2",
						"value" => get_template_directory_uri() . '/assets/images/shortcodes/testimonial-style2.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style2')
                    ),
					array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style3",
						"value" => get_template_directory_uri() . '/assets/images/shortcodes/testimonial-style3.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style3')
                    ),
					array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style4",
						"value" => get_template_directory_uri() . '/assets/images/shortcodes/testimonial-style4.png',
                        "dependency" => array('element' => 'style', 'value' => 'style4')
                    ),
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Name", "inwavethemes"),
                        "value" => "This is Name",
                        "param_name" => "name"
                    ),
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Position", "inwavethemes"),
                        "value" => "",
                        "param_name" => "position",
                    ),
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Title", "inwavethemes"),
                        "value" => "",
                        "param_name" => "title",
                        "dependency" => array('element' => 'style', 'value' => 'style3')
                    ),
                    array(
                        'type' => 'textarea_html',
                        "heading" => __("Testimonial Content", "inwavethemes"),
                        "value" => "",
                        "param_name" => "content"
                    ),
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Link", "inwavethemes"),
                        "value" => "",
                        "param_name" => "link",
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Text Link", "inwavethemes"),
                        "value" => "",
                        "param_name" => "text_link",
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
                    array(
                        "type" => "attach_image",
                        "heading" => __("Client Image", "inwavethemes"),
                        "param_name" => "image",
                        "value" => "",
                    ),
                    array(
                        "type" => "attach_image",
                        "heading" => __("Icon Image top", "inwavethemes"),
                        "param_name" => "icon_image",
                        "value" => "",
                        "dependency" => array(
                            'element' => 'style',
                            'value' => 'style4',
                        )
                    ),
                    array(
                        'type' => 'iw_icon',
                        "heading" => __("Icon", "inwavethemes"),
                        "value" => "",
                        "description" => __("Click and select icon of your choice. You can get complete list of available icons here: <a target='_blank' href='http://fortawesome.github.io/Font-Awesome/icons/'>Font-Awesome</a>", "inwavethemes"),
                        "param_name" => "icon",
                        "dependency" => array(
                            'element' => 'style',
                            'value' => 'style4',
                        )
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Icon Size", "inwavethemes"),
                        "param_name" => "icon_size",
                        "description" => __("Example: 24. Use for image width or font size icon", "inwavethemes"),
                        "value" => "",
                        "dependency" => array(
                            'element' => 'style',
                            'value' => 'style4',
                        )
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Rating", "inwavethemes"),
                        "description" => "value: 0 -> 100%, Example: 100%",
                        "value" => "100%",
                        "param_name" => "rating",
                        "dependency" => array('element' => 'style', 'value' => 'style3')
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

            $output = $class = $style = $item_active = '';
            extract(shortcode_atts(array(
                "class" => "",
                "style" => "style1",
                "item_active" => "",
				'css' => '',
            ), $atts));

            $this->style = $style;

            $active = 1;
            if ($item_active) {
                $active = $item_active;
            }

            $matches = array();
            //$count = preg_match_all('/\[inwave_testimonial_item(?:\s+layout="([^\"]*)"){0,1}(?:\s+title="([^\"]*)"){0,1}(?:\s+name="([^\"]*)"){0,1}(?:\s+date="([^\"]*)"){0,1}(?:\s+position="([^\"]*)"){0,1}(?:\s+image="([^\"]*)"){0,1}(?:\s+rate="([^\"]*)"){0,1}(?:\s+testimonial_text="([^\"]*)"){0,1}(?:\s+class="([^\"]*)"){0,1}\]/i', $content, $matches);
            $count = preg_match_all( '/inwave_testimonial_item([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
            if ($count) {
                switch ($style){

                    case 'style1':
                        $output .= '<div class="iw-testimonals style1 ' . $class . '">';
                        $output.= '<div class="testi-owl-maincontent">';
                        $output .= do_shortcode($content);
                        $items = array();
                        foreach ($matches[1] as $value) {
                            $items[] = shortcode_parse_atts( $value[0] );
                        }
                        $output.= '</div>';
                        $output.= '<div class="testi-owl-clientinfo">';
                        foreach ($items as $key => $item) {
                            $image = $item['image'];
                            if ($image) {
                                $img = wp_get_attachment_image_src($image);
                                $image = '<img class="grayscale" src="' . $img[0] . '" alt=""/>';
                            }
                            $output.= '<div data-item-active="' . $key . '" class="iw-testimonial-client-item ' . ($key == $active ? 'active' : '') . '">';
                            $output.= '<div class="testi-image">' . $image . '</div>';
                            $output.= '</div>';
                        }
                        $output.= '</div>';
                        $output .= '</div>';
                        break;
                }
            }
            $output .= '<script type="text/javascript">';
            $output .= '(function ($) {';
            $output .= '$(document).ready(function () {';
            $output .= '$(".iw-testimonals").iwCarousel();';
            $output .= '});';
            $output .= '})(jQuery);';
            $output .= '</script>';

            return $output;
        }

        // Shortcode handler function for item
        function init_shortcode2($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name2, $atts ) : $atts;

            $output = $style = $name = $address = $position = $title = $image = $icon_image = $icon = $icon_size = $link = $text_link = $rating = '';
            extract(shortcode_atts(array(
                'style' => '',
                'name' => '',
                'position' => '',
                'title' => '',
                'image' => '',
                'icon_image' => '',
                'icon' => '',
                'icon_size' => '',
                'rating' => '',
                'link' => '',
                'text_link' => '',
                'class' => ''
            ), $atts));

            $star_rating = '';
            if($rating){
                $star_rating.= 'width: '.esc_attr($rating);
            }

            if ($image) {
                $img = wp_get_attachment_image_src($image);
                $image = '<img src="' . $img[0] . '" alt=""/>';
            }

            if ($icon_image) {
                $img = wp_get_attachment_image_src($icon_image);
                $icon_image = '<img src="' . $img[0] . '" alt=""/>';
            }

            $font_size_icon = '';
            if ($icon_size) {
                $font_size_icon = 'style="font-size:' . $icon_size . 'px"';
            }

            switch ($style){
                case 'style1':
                $output.= '<div class="iw-testimonial-item style1">';
                    $output.= '<div class="testi-info-wrap">';
                        $output.= '<div class="testi-info">';
                            if($image){
                                $output.= '<div class="testi-image">' . $image . '</div>';
                            }
                            $output.= '<div class="testi-info-right">';
                                if($name){
                                    $output.= '<div class="testi-client-name theme-color">' . $name . '</div>';
                                }
                                if($position){
                                    $output.= '<div class="testi-client-position">' . $position . '</div>';
                                }
                            $output.= '</div>';
                            $output.= '<div class="clearfix"></div>';
                        $output.= '</div>';
                        if($content){
                            $output.= '<div class="testi-content">' . $content . '</div>';
                        }
                        if($link && $text_link){
                            $output.= '<div class="testi-client-url theme-color"><a href="'.$link.'">' . $text_link . '</a></div>';
                        }
                    $output.= '</div>';
                $output.= '</div>';
            break;
            case 'style2':
                $output.= '<div class="iw-testimonial-item style2">';
                    $output.= '<div class="testi-info-wrap">';
                        if($image){
                            $output.= '<div class="testi-image">' . $image . '</div>';
                        }
                        if($content){
                            $output.= '<div class="testi-content">' . $content . '</div>';
                        }
                        if($name){
                            $output.= '<div class="testi-client-name theme-color">' . $name . '</div>';
                        }
                        if($position){
                            $output.= '<div class="testi-client-position">' . $position . '</div>';
                        }
                    $output.= '</div>';
                $output.= '</div>';
            break;
            case 'style3':
                $output.= '<div class="iw-testimonial-item style3">';
                    $output.= '<div class="testi-info-wrap">';
                        $output.= '<div class="testi-top">';
                            if($title){
                                $output.= '<div class="testi-title theme-color">' . $title . '</div>';
                            }
                            if($content){
                                $output.= '<div class="testi-content">' . $content . '</div>';
                            }
                            $output .= '<div class="iw-star-rating">';
                                $output .= '<span class="rating" style="' .$star_rating. '"></span>';
                            $output .= '</div>';
                        $output.= '</div>';
                        $output.= '<div class="testi-bottom">';
                            if($image){
                                $output.= '<div class="testi-image">' . $image . '</div>';
                            }
                            $output.= '<div class="testi-bottom-left">';
                                if($name){
                                    $output.= '<div class="testi-client-name theme-color">' . $name . '</div>';
                                }
                                if($position){
                                    $output.= '<div class="testi-client-position">' . $position . '</div>';
                                }
                            $output.= '</div>';
                            $output.= '<div class="clearfix"></div>';
                        $output.= '</div>';
                    $output.= '</div>';
                $output.= '</div>';
            break;

			case 'style4':
                $output.= '<div class="iw-testimonial-item style4">';
                if($icon_image){
                    $output .= '<div class="icon-img">'.$icon_image.'</div>';
                }elseif ($icon){
                    $output .= '<div class="icon theme-color" '.$font_size_icon.'><i class="'.esc_attr($icon).'"></i></div>';
                }
                if($name){
                    $output.= '<div class="testi-client-name">' . $name . '</div>';
                }
                if($position){
                    $output.= '<div class="testi-client-position">' . $position . '</div>';
                }
                $output.= '<div class="testi-desc">' . $content . '</div>';
                $output.= '</div>';
			break;
            }
            return $output;
        }
    }
}

new Inwave_Testimonials;
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Testimonials extends WPBakeryShortCodesContainer {}
}
