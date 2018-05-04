<?php

/*
 * @package Inwave Athlete
 * @version 1.0.0
 * @created Mar 30, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of athlete_map
 *
 * @Developer duongca
 */
if (!class_exists('Inwave_Multi_Map')) {

    class Inwave_Multi_Map extends Inwave_Shortcode2{

        protected $name = 'inwave_multi_map';
        protected $name2 = 'inwave_map_item';
        protected $item_count = 0;
        protected $active_item = 1;
        protected $map_data = array();

        function register_scripts()
        {
            $inwave_theme_option = Inwave_Helper::getConfig();
            wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$inwave_theme_option['google_api'].'&libraries=places', array('jquery'), INWAVE_COMMON_VERSION, true);
            wp_register_script('infobox', 'https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js', array('jquery'), INWAVE_COMMON_VERSION, true);
            wp_register_script('iw-map', plugins_url() . '/inwave-common/assets/js/iw-map.js', array('jquery'), INWAVE_COMMON_VERSION, true);
        }

        function init_params() {
            return array(
                'name' => 'Multi Map',
                'description' => __('Display a Google Map', 'inwavethemes'),
                'base' => $this->name,
                'icon' => 'iw-default',
                'category' => 'Custom',
                "as_parent" => array('only' => $this->name2),
                "show_settings_on_create" => true,
                "js_view" => 'VcColumnView',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "admin_label" => true,
                        "heading" => "Map Style",
                        "param_name" => "map_style",
                        "value" => array(
                            "Style 1"=> "style1",
                        )
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Item active", "inwavethemes"),
                        "value" => "1",
                        "description" => __("Choose item active", "inwavethemes"),
                        "param_name" => "item_active",
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Title", "inwavethemes"),
                        "value" => "",
                        "param_name" => "title",
                        "description" => __('Enter title.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Description", "inwavethemes"),
                        "value" => "",
                        "param_name" => "description",
                        "description" => __('Enter description.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Background Image", "inwavethemes"),
                        "param_name" => "bg_img",
                        "description" => __("Background Image block", "inwavethemes"),
                    ),
                    array(
                        "type" => "attach_image",
                        "heading" => __("Marker Icon", "inwavethemes"),
                        "param_name" => "marker_icon",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Zoom", "inwavethemes"),
                        "param_name" => "zoom",
                        "value" => "11",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Map height", "inwavethemes"),
                        "param_name" => "height",
                        "value" => "400",
                        "description"=> __("Example: 400(in px) or 100vh", "inwavethemes"),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("panBy x", "inwavethemes"),
                        "param_name" => "panby_x",
                        "value" => "0",
                        "description"=> __("Changes the center of the map by the given distance in pixels.. Example: 50", "inwavethemes"),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("panBy y", "inwavethemes"),
                        "param_name" => "panby_y",
                        "value" => "0",
                        "description"=> __("Changes the center of the map by the given distance in pixels. Example: 50", "inwavethemes"),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    )
                )
            );
        }

        function init_params2() {
            return array(
                "name" => __("Multi Map Item", 'inwavethemes'),
                "base" => $this->name2,
                "class" => "inwave_map_item",
                'icon' => 'iw-default',
                'category' => 'Custom',
                "description" => __("Add a list of map item with some content and give some custom style.", "inwavethemes"),
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        'type' => 'textfield',
                        "heading" => __("Contact info title", "inwavethemes"),
                        "value" => "Vietnam",
                        "param_name" => "info_title",
                        "description" => __('Enter contact info title.', "inwavethemes"),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Latitude", "inwavethemes"),
                        "admin_label" => true,
                        "param_name" => "latitude",
                        "value" => "40.6700",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Longitude", "inwavethemes"),
                        "admin_label" => true,
                        "param_name" => "longitude",
                        "value" => "-73.9400",
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Address", "inwavethemes"),
                        "value" => "Ha Noi #1214 187 Tay Son Building. Dong Da Dist, Ha Noi, Vietnam",
                        "param_name" => "address",
                        "description" => __('Enter address.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Email Address", "inwavethemes"),
                        "value" => "support@bowthemes.com",
                        "param_name" => "email_address",
                        "description" => __('Enter email address.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Phone number", "inwavethemes"),
                        "value" => "+84 04 1234 566 66",
                        "param_name" => "phone_number",
                        "description" => __('Enter phone number.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Website Name", "inwavethemes"),
                        "value" => "http://bowthemes.com",
                        "param_name" => "website_name",
                        "description" => __('Enter webite name.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Website URL", "inwavethemes"),
                        "value" => "http://bowthemes.com",
                        "param_name" => "website_url",
                        "description" => __('Enter webite URL.', "inwavethemes"),
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

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content=null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;
            wp_enqueue_script('google-maps');
            wp_enqueue_script('infobox');
            wp_enqueue_script('iw-map');
            extract(shortcode_atts(array(
                'map_style' => '',
                'item_active' => '',
                'title' => '',
                'description' => '',
                'bg_img' => '',
                'get_in_touch_url' => '',
                'marker_icon' => '',
                'image' => '',
                'height' => '',
                'panby_x' => '',
                'panby_y' => '',
                'zoom' => '11',
                'class' => ''
            ), $atts));
            $image_url = $icon_url = '';
            $id = 'iwm-' . rand(10000, 99999);
            $this->active_item = $item_active;
            if($marker_icon){
                $img = wp_get_attachment_image_src($marker_icon, 'large');
                $icon_url = count($img) ? $img[0] : '';
            }
            if($image){
                $img = wp_get_attachment_image_src($image, 'large');
                $image_url = count($img) ? $img[0] : '';
                $image_url = inwave_resize($image_url, 270, 180);
            }
            $bg_image = '';
            if($bg_img){
                $bg_img = wp_get_attachment_image_src($bg_img, 'large');
                $bg_img = $bg_img[0];
                $bg_image.= 'background-image: url('.$bg_img .')';
            }

            if($height){
                if(is_numeric($height)){
                    $height = 'style="height:'.esc_attr($height).'px"';
                }
                else
                {
                    $height = 'style="height:'.esc_attr($height).'"';
                }
            }

            $output = '';
            $output .= '<div id="'.$id.'" class="inwave-multi-map">';
            $output .= '<div class="iw-map-content-wrap" style="'.$bg_image.'">';
            $output.= '<div class="container">';
            if($title){
                $output.= '<div class="map-title">' . $title . '</div>';
            }
            if($description){
                $output.= '<div class="map-desc">' . $description . '</div>';
            }
            $output.= '<div class="iw-maps-item">';
            $output.= '<div class="row">';
            $output .= do_shortcode($content);
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="inwave-map-contact" '.$height.'>';
            $output .= '<div class="inwave-map">';
            $attributes = array();
            $attributes[] = 'data-address="' . esc_attr($this->map_data['address']) . '"';
            $attributes[] = 'data-map_style="' . esc_attr($map_style) . '"';
            $attributes[] = 'data-marker_icon="' . esc_attr($icon_url) . '"';
            $attributes[] = 'data-lat="' . esc_attr($this->map_data['latitude']) . '"';
            $attributes[] = 'data-long="' . esc_attr($this->map_data['longitude']) . '"';
            $attributes[] = 'data-zoom="' . esc_attr($zoom) . '"';
            $attributes[] = 'data-panby_x="' . esc_attr($panby_x ? $panby_x: 0) . '"';
            $attributes[] = 'data-panby_y="' . esc_attr($panby_y ? $panby_y : 0) . '"';
            $attributes[] = 'data-image="' . esc_url($image_url).'"';
            $attributes = implode( ' ', $attributes );
            $output .= '<div class="map-contain" '.$attributes.' >';
            $output .= '<div class="map-view map-frame" '.$height.'></div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';

            return $output;
        }

        // Shortcode handler function for item
        function init_shortcode2($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name2, $atts ) : $atts;


            $output = $info_title = $latitude = $longitude = $address = $email_address = $phone_number = $website_name = $website_url = $class = '';
            $this->item_count++;
            extract(shortcode_atts(array(
                'info_title' => '',
                'latitude' => '',
                'longitude' => '',
                'address' => '',
                'email_address' => '',
                'phone_number' => '',
                'website_name' => '',
                'website_url' => '',
                'class' => ''
            ), $atts));
            if($this->item_count == $this->active_item)  {
                $this->map_data = array(
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'address' => $address,
                );
            }

                $output.= '<div class="iw-map-item-wrap col-md-3 col-sm-6 col-xs-12">';
                $output.= '<div class="iw-map-item ' . ($this->item_count == $this->active_item ? 'active' : '') . '" data-lat="'.$latitude.'" data-long="'.$longitude.'" data-address="'.$address.'">';
                if($info_title){
                    $output.= '<div class="contact-title">' . $info_title . '</div>';
                }
                if($address){
                    $output.= '<div class="contact-address"><i class="fa fa-map-marker"></i><span>' . $address . '</span></div>';
                }
                if($email_address){
                    $output.= '<div class="contact-email-address"><i class="fa fa-envelope-o"></i><span>' . $email_address . '</span></div>';
                }
                if($phone_number){
                    $output.= '<div class="contact-phone-number"><i class="fa fa-phone"></i><span>' . $phone_number . '</span></div>';
                }
                if($website_name && $website_url){
                    $output.= '<div class="contact-website"><i class="fa fa-globe"></i><a href="'.$website_url.'">'.$website_name.'</a></div>';
                }
                $output.= '</div>';
                $output.= '</div>';
            return $output;
        }
    }
}

new Inwave_Multi_Map();
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Multi_Map extends WPBakeryShortCodesContainer {}
}
