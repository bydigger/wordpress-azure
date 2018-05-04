<?php

/*
 * Inwave_Doctor_Listing for Visual Composer
 */
if (!class_exists('Inwave_Time_Table')) {

    class Inwave_Time_Table extends Inwave_Shortcode{

        protected $name = 'iw_time_table';

        function init_params() {

            return array(
                'name' => __('Time Table', 'inwavethemes'),
                'description' => __('Create a list of appoinment form', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
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
				"css" => "",
                "class" => ""
                            ), $atts));

	
            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }
			
			 $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);
            ob_start();
            echo do_shortcode('[inmedical_timetable css="'.$css.'" class="' . $class . '"]');
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Time_Table();
