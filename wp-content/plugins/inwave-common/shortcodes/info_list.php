<?php

/*
 * Inwave_Info_List for Visual Composer
 */
if (!class_exists('Inwave_Info_List')) {

    class Inwave_Info_List extends Inwave_Shortcode{

        protected $name = 'inwave_info_list';
        protected $style;

        function init_params() {
            return array(
                "name" => __("Info List", 'inwavethemes'),
                "base" => $this->name,
                "content_element" => true,
                'category' => 'Custom',
                'icon' => 'iw-default',
                "description" => __("Add a set of list info and give some custom style.", "inwavethemes"),
                "as_parent" => array('only' => 'inwave_item_info'),
                "show_settings_on_create" => true,
                "js_view" => 'VcColumnView',
                "params" => array(
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "value" => "",
                        "description" => __("Write your own CSS and mention the class name here.", "inwavethemes"),
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Style",
                        "heading" => "Style",
                        "param_name" => "style",
                        "value" => array(
                            "Style 1" => "style1",
                        )
                    )
                )
            );
        }


        // Shortcode handler function for list
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $class = $style = '';
            extract(shortcode_atts(array(
                "class" => "",
                "style" => "style1"
            ), $atts));
            $this->style = $style;

            $class .= ' '. $style;

            $output = '<div class="info-list ' . $class . '">';
            $output .= do_shortcode($content);
            $output .= '</div>';
            return $output;
        }
    }
}


new Inwave_Info_List;
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Info_List extends WPBakeryShortCodesContainer {
    }
}
