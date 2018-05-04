<?php

/*
 * Inwave_Event_Listing for Visual Composer
 */
if (!class_exists('Inwave_Event_Listing')) {

    class Inwave_Event_Listing extends Inwave_Shortcode{

        protected $name = 'iw_event_listing';

        function init_params() {

            return array(
                'name' => __('Event Listing', 'inwavethemes'),
                'description' => __('Create a list of appoinment form', 'inwavethemes'),
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
                "css" => "",
                "class" => ""
            ), $atts));


            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }

            $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);
            ob_start();
            echo do_shortcode('[inmedical_event_listing post_number="'.$post_number.'" css="'.$css.'" class="' . $class . '"]');
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Event_Listing();
