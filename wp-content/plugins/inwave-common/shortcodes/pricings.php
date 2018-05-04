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
if (!class_exists('Inwave_Pricings')) {

    class Inwave_Pricings extends Inwave_Shortcode2{

        protected $name = 'inwave_pricings';
        protected $name2 = 'inwave_pricing_item';
        protected $testimonials;
        protected $testimonial_item;
        protected $style;
        protected $count;

        function init_params()
        {
            return array(
                "name" => __("Pricing List", 'inwavethemes'),
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
                        "heading" => __("Style", 'inwavethemes'),
						"admin_label" =>true,
                        "param_name" => "style",
                        "value" => array(
                            "Style 1" => "style1",
                            "Style 2" => "style2",
                            "Style 3" => "style3",
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
                "name" => __("Pricing Item", 'inwavethemes'),
                "base" => $this->name2,
                "class" => "inwave_pricing_item",
                'icon' => 'iw-default',
                'category' => 'Custom',
                "as_child" => array('only' => $this->name),
                "description" => __("Add a pricing item.", "inwavethemes"),
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Title", "inwavethemes"),
                        "value" => "Gold Plan",
                        "param_name" => "title",
                    ),
                    array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Sub Title", "inwavethemes"),
                        "value" => "",
                        "param_name" => "sub_title",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Price", "inwavethemes"),
                        "param_name" => "price",
                        "value" => "5"
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("HTML Currency Symbol", "inwavethemes"),
                        "param_name" => "currency_symbol",
                        "value" => "&#36;"
                    ),
                    array(
                        'type' => 'dropdown',
                        "heading" => __("Currency Symbols Position", "inwavethemes"),
                        "param_name" => "currency_symbol_position",
                        "value" => array(
                            __('Left' , 'inwavethemes') => 'left',
                            __('Right' , 'inwavethemes') => 'right',
                        )
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Calculation Unit", "inwavethemes"),
                        "param_name" => "price_unit",
                        "value" => "Month"
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Purchase button text", "inwavethemes"),
                        "param_name" => "purchase_text",
                        "value" => "purchase"
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Purchase link", "inwavethemes"),
                        "param_name" => "purchase_link",
                        "value" => "#"
                    ),
                    array(
                        'type' => 'textarea',
                        "holder" => "div",
                        "heading" => __("Content", "inwavethemes"),
                        "value" => '<ul>
                                            <li>Lorem ipsum dolor sit amet</li>
                                            <li>Lorem ipsum dolor sit amet</li>
                                            <li>Lorem ipsum dolor sit amet</li>
                                            <li>Lorem ipsum dolor sit amet</li>
                                            <li>Lorem ipsum dolor sit amet</li>
                                            <li class="iw-check"><i class="fa fa-check"></i></li>
                                            <li class="iw-close"><i class="fa fa-close"></i></li>
                                            <li><div class="iw-star-rating"><span class="rating" style="width: 100%;">star</span></div></li>
                                        </ul>',
                        "param_name" => "content"
                    ),
                    array(
                        'type' => 'checkbox',
                        "heading" => __("Featured?", "inwavethemes"),
                        "param_name" => "featured"
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Header image", "inwavethemes"),
                        "description" => __("Using for pricing table style 1.", "inwavethemes"),
                        "param_name" => "header_img"
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

        // Shortcode handler function for list
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $class = $style = '';
            //$id = 'iwt-' . rand(10000, 99999);
            extract(shortcode_atts(array(
                "class" => "",
                "style" => "style1",
				'css' => '',
            ), $atts));

            $this->style = $style;

            $matches = array();
            //$count = preg_match_all('/\[inwave_pricing_item(?:\s+layout="([^\"]*)"){0,1}(?:\s+title="([^\"]*)"){0,1}(?:\s+name="([^\"]*)"){0,1}(?:\s+date="([^\"]*)"){0,1}(?:\s+position="([^\"]*)"){0,1}(?:\s+image="([^\"]*)"){0,1}(?:\s+rate="([^\"]*)"){0,1}(?:\s+testimonial_text="([^\"]*)"){0,1}(?:\s+class="([^\"]*)"){0,1}\]/i', $content, $matches);
            $count = preg_match_all( '/inwave_pricing_item([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );

            if ($count) {
                $this->count = $count;
                switch ($style){
                    case 'style1':
                    case 'style2':
                    case 'style3':
                        $output .= '<div class="iw-pricing-table '.$style.'">';
                            $output .= '<div class="row">';
                            $output .= do_shortcode($content);
                            $output .= '</div>';
                        $output .= '</div>';
                        break;
                }
            }

            return $output;
        }

        // Shortcode handler function for item
        function init_shortcode2($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name2, $atts ) : $atts;

            $output = $style = $name = $address = $position = $title = $image = $link = $rating = '';
            extract(shortcode_atts(array(
                'title' => '',
                'sub_title' => '',
                'currency_symbol' => '',
                'currency_symbol_position' => '',
                'price' => '',
                'price_unit' => '',
                'purchase_text' => '',
                'purchase_link' => '',
                'featured' => '',
                'header_img' => '',
                'class' => ''
            ), $atts));
            $content = rtrim($content, '</p>');
            $content = ltrim($content, '</p>');
            switch ($this->style){
                case 'style1':
                    $item_class = $this->get_item_class();
                    $output .= '<div class="'.$item_class.'">';
                        $output .= '<div class="iw-pricing-item '.($featured ? 'item-featured featured' : '').'">';
                            $header_img_url = '';
                            if ($header_img) {
                                $header_img = wp_get_attachment_image_src($header_img, 'large');
                                $header_img_url = $header_img[0];
                            }
                            $output .= '<div class="pricing-header" style="'.($header_img_url ? 'background-image: url('.esc_url($header_img_url).')' : '').'">';
                            $output .= '<div class="pricing-info">';
                            if ($title){
                                $output .= '<h3 class="iw-title theme-color">' . $title . '</h3>';
                            }
                            if ($sub_title) {
                                $output .= '<div class="iw-sub-title theme-color">' . $sub_title . '</div>';
                            }
                            $output .= '</div>';
                            $output .= '</div>';
                            $output .= '<div class="pricing-price theme-bg"><span class="currency-symbol">'.$currency_symbol.'</span><span class="price">'.$price.'</span><span class="price-unit">/'.$price_unit.'</span></div>';
                            $output .= '<div class="pricing-content">';
                            $output .= $content;
                            $output .= '</div>';
                            $output .= '<div class="pricing-footer">';
                            $output .= '<a href="'.$purchase_link.'">'.$purchase_text.'</a>';
                            $output .= '</div>';
                        $output .= '</div>';
                    $output .= '</div>';
                break;
                case 'style2':
                    $item_class = $this->get_item_class();
                    $output .= '<div class="'.$item_class.'">';
                    $output .= '<div class="iw-pricing-item '.($featured ? 'item-featured featured' : '').'">';
                    $output .= '<div class="iw-pricing-item-inner">';
                    if($featured){
                        $output .= '<div class="pricing-featured-label">'.__('Featured', 'inwavethemes').'</div>';
                    }
                    $output .= '<div class="pricing-header">';
                        $output .= '<div class="pricing-line"><span class="fa fa-star"></span></div>';
                        if ($title) {
                            $output .= '<h3 class="pricing-title iw-title theme-color">' . $title . '</h3>';
                        }
                        if ($sub_title) {
                            $output .= '<div class="pricing-sub-title iw-sub-title theme-color">' . $sub_title . '</div>';
                        }
                        $output .= '</div>';
                    $output .= '<div class="pricing-price theme-bg-hover"><span class="currency-symbol">'.$currency_symbol.'</span><span class="price">'.$price.' / </span><span class="price-unit">'.$price_unit.'</span></div>';
                    $output .= '<div class="pricing-content">';
                    $output .= $content;
                    $output .= '</div>';
                    $output .= '<div class="pricing-footer">';
                    $output .= '<a href="'.$purchase_link.'">'.$purchase_text.'</a>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                break;
                case 'style3':
                    $item_class = $this->get_item_class();
                    $output .= '<div class="'.$item_class.'">';
                    $output .= '<div class="iw-pricing-item '.($featured ? 'item-featured featured' : '').'">';
                    $output .= '<div class="iw-pricing-item-inner">';
                    if($featured){
                        $output .= '<div class="pricing-featured-label">'.__('Featured', 'inwavethemes').'</div>';
                    }
                    $output .= '<div class="pricing-header">';
                    if ($title) {
                        $output .= '<h3 class="pricing-title iw-title theme-color">' . $title . '</h3>';
                    }
                    if ($sub_title) {
                        $output .= '<div class="pricing-sub-title iw-sub-title theme-color">' . $sub_title . '</div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="pricing-price"><span class="currency-symbol">'.$currency_symbol.'</span><span class="price">'.$price.'</span><span class="price-unit"> / '.$price_unit.'</span></div>';
                    $output .= '<div class="pricing-body">';
                    $output .= '<div class="pricing-content">';
                    $output .= $content;
                    $output .= '</div>';
                    $output .= '<div class="pricing-footer">';
                    $output .= '<a href="'.$purchase_link.'">'.$purchase_text.'</a>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                break;

                case 'style4':
                    $output .= '<div class="'.$item_class.'">';
                    $output .= $content;
                    $output .= '</div>';
                break;
            }
            return $output;
        }

        function get_item_class(){
            if($this->count == 1){
                return 'col-md-12 col-sm-12 col-xs-12 item';
            }
            if($this->count == 2){
                return 'col-md-6 col-sm-6 col-xs-12 item';
            }
            if($this->count == 3){
                return 'col-md-4 col-sm-4 col-xs-12 item';
            }
            if($this->count == 4){
                return 'col-md-3 col-sm-6 col-xs-12 item';
            }

            return 'col-md-4 col-sm-6 col-xs-12 item';
        }
    }
}

new Inwave_Pricings;
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Pricings extends WPBakeryShortCodesContainer {}
}
