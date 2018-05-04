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
if (!class_exists('Inwave_Map')) {

    class Inwave_Map extends Inwave_Shortcode{

        protected $name = 'inwave_map';

        function register_scripts()
        {
            $inwave_theme_option = Inwave_Helper::getConfig();
            wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$inwave_theme_option['google_api'].'&libraries=places', array('jquery'), INWAVE_COMMON_VERSION, true);
            wp_register_script('infobox', 'https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js', array('jquery'), INWAVE_COMMON_VERSION, true);
            wp_register_script('iw-map', plugins_url() . '/inwave-common/assets/js/iw-map.js', array('jquery'), INWAVE_COMMON_VERSION, true);
        }

        function init_params() {
            return array(
                'name' => 'Map',
                'description' => __('Display a Google Map', 'inwavethemes'),
                'base' => $this->name,
                'icon' => 'iw-default',
                'category' => 'Custom',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "admin_label" => true,
                        "heading" => "Map Style",
                        "param_name" => "map_style",
                        "value" => array(
                            "Style 1"=> "style1",
                            "Style 2" => "style2",
                        )
                    ),
                    array(
                        "type" => "attach_image",
                        "heading" => __("Map Image", "inwavethemes"),
                        "param_name" => "image",
                    ),
                    array(
                        "type" => "attach_image",
                        "heading" => __("Marker Icon", "inwavethemes"),
                        "param_name" => "marker_icon",
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
                        "heading" => __("Map Info width", "inwavethemes"),
                        "param_name" => "info_width",
                        "value" => "460",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Map Info Height", "inwavethemes"),
                        "param_name" => "info_height",
                        "value" => "386",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Map Info panBy x", "inwavethemes"),
                        "param_name" => "info_panby_x",
                        "value" => "200",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Map Info panBy y", "inwavethemes"),
                        "param_name" => "info_panby_y",
                        "value" => "40",
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
                        "heading" => __("Phone number", "inwavethemes"),
                        "value" => "+84 04 1234 566 66",
                        "param_name" => "phone_number",
                        "description" => __('Enter phone number.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Email Address", "inwavethemes"),
                        "value" => "hello@ineventwordpress.com",
                        "param_name" => "email_address",
                        "description" => __('Enter email address.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Website URL", "inwavethemes"),
                        "value" => "http://ineventwordpress.com",
                        "param_name" => "website_url",
                        "description" => __('Enter webite URL.', "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "group" => "Contact Form",
                        "holder" => "div",
                        "heading" => __("Add contact form 7 ID", "inwavethemes"),
                        "value" => "",
                        "param_name" => "contact_form_7_id"
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

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content=null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;
            wp_enqueue_script('google-maps');
            wp_enqueue_script('infobox');
            wp_enqueue_script('iw-map');
            extract(shortcode_atts(array(
                'map_style' => '',
                'address' => '',
                'phone_number' => '',
                'email_address' => '',
                'website_url' => '',
                'get_in_touch_url' => '',
                'latitude' => '',
                'longitude' => '',
                'marker_icon' => '',
                'image' => '',
                'height' => '',
                'info_width' => '',
                'info_height' => '',
                'info_panby_x' => '',
                'info_panby_y' => '',
                'panby_x' => '',
                'panby_y' => '',
                'zoom' => '11',
                'contact_form_7_id' => '',
                'class' => ''
            ), $atts));
            $image_url = $icon_url = '';
            if($marker_icon){
                $img = wp_get_attachment_image_src($marker_icon, 'large');
                $icon_url = count($img) ? $img[0] : '';
            }
            if($image){
                $img = wp_get_attachment_image_src($image, 'large');
                $image_url = count($img) ? $img[0] : '';
                $image_url = inwave_resize($image_url, 270, 180);
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

            $address = preg_replace('/\|(.*)\|/isU','<span class="city">$1</span><br>',$address);

            $output = '';
            $output .= '<div class="inwave-map-contact" '.$height.'>';
            $output .= '<div class="inwave-map">';
            $attributes = array();
            $attributes[] = 'data-map_style="' . esc_attr($map_style) . '"';
            $attributes[] = 'data-address="' . esc_attr($address) . '"';
            $attributes[] = 'data-phone_number="' . esc_attr($phone_number) . '"';
            $attributes[] = 'data-email_address="' . esc_attr($email_address) . '"';
            $attributes[] = 'data-website_url="' . esc_attr($website_url) . '"';
            $attributes[] = 'data-marker_icon="' . esc_attr($icon_url) . '"';
            $attributes[] = 'data-lat="' . esc_attr($latitude) . '"';
            $attributes[] = 'data-long="' . esc_attr($longitude) . '"';
            $attributes[] = 'data-zoom="' . esc_attr($zoom) . '"';
            $attributes[] = 'data-panby_x="' . esc_attr($panby_x ? $panby_x: 0) . '"';
            $attributes[] = 'data-panby_y="' . esc_attr($panby_y ? $panby_y : 0) . '"';

            $attributes[] = 'data-image="' . esc_url($image_url).'"';
            $attributes[] = 'data-width="' . esc_attr($info_width) . '"';
            $attributes[] = 'data-height="' . esc_attr($info_height) . '"';
            $attributes[] = 'data-info_panby_x="' . esc_attr($info_panby_x ? $info_panby_x : 0) . '"';
            $attributes[] = 'data-info_panby_y="' . esc_attr($info_panby_y ? $info_panby_y : 0) . '"';
            $attributes = implode( ' ', $attributes );

            $output .= '<div class="map-contain" '.$attributes.' >';
            $output .= '<div class="map-view map-frame" '.$height.'></div>';
            $output .= '</div>';

            $output .= '</div>';

            $output .= '<div class="form-contact">';
                $output .= '<div class="row">';
                    $output .= '<div class="container">';
                        $output .= '<div class="iw-contact-form7 col-md-6 col-sm-12 col-xs-12">';
                            if($contact_form_7_id) {
                                $contact_form_7_shortcode = '[contact-form-7 id="'.$contact_form_7_id.'"]';
                                $output .= '<div>' .do_shortcode( $contact_form_7_shortcode ). '</div>';
                            }
                        $output .= '</div>';
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }
}

new Inwave_Map();
