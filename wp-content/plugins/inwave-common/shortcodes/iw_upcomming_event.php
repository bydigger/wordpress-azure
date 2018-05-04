<?php

/*
 * Inwave_Upcomming_Event for Visual Composer
 */
if (!class_exists('Inwave_Upcomming_Event')) {

    class Inwave_Upcomming_Event extends Inwave_Shortcode{

        protected $name = 'iw_upcomming_event';

        function init_params() {

            return array(
                'name' => __('Upcomming Event', 'inwavethemes'),
                'description' => __('Create a upcomming event list', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "type" => "textfield",
                        "heading" => __("Post number", "inwavethemes"),
                        "param_name" => "post_number",
                        "value" => "5",
                        "admin_label" => true,
                        "description" => __('Number of posts to display.', "inwavethemes")
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop", "inwavethemes"),
                        "param_name" => "item_desktop",
                        "value" => '3',

                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop Small", "inwavethemes"),
                        "param_name" => "item_desktop_small",
                        "value" => '2',

                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => "Auto Play Slider",
                        "param_name" => "auto_play",
                        "value" => array(
                            "No" => "false",
                            "Yes" => "true"
                        ),

                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => "Show Navigation",
                        "param_name" => "show_navigation",
                        "value" => array(
                            "No" => "false",
                            "Yes" => "true"
                        ),

                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __( 'CSS box', 'inwavethemes' ),
                        'param_name' => 'css',
                        'group' => __( 'Design Options', 'inwavethemes' )
                    )
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            extract(shortcode_atts(array(
                "post_number" => "5",
                "item_desktop" => "",
                "item_desktop_small" => "",
                "auto_play" => "false",
                "show_navigation" => "false",
                "css" => "",
                "class" => ""
            ), $atts));


            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }

            $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);
            ob_start();
            echo do_shortcode('[inmedical_upcomming_event post_number="'.$post_number.'" item_desktop="'.$item_desktop.'" item_desktop_small="'.$item_desktop_small.'" auto_play="'.$auto_play.'" show_navigation="'.$show_navigation.'" css="'.$css.'" class="' . $class . '"]');
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Upcomming_Event();
