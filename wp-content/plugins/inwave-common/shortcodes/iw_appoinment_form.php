<?php

/*
 * Inwave_Doctor_Listing for Visual Composer
 */
if (!class_exists('Inwave_Appoinment_Form')) {

    class Inwave_Appoinment_Form extends Inwave_Shortcode{

        protected $name = 'iw_appoinment_form';

        function init_params() {

            return array(
                'name' => __('Appoinment Form', 'inwavethemes'),
                'description' => __('Create a list of appoinment form', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
					array(
                        "type" => "dropdown",
                        "heading" => "Style",
                        "admin_label" => true,
                        "param_name" => "style",
                        "value" => array(
                            "Style 1" => "default",
                            "Style 2" => "style2",
                        )
                    ),
					array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "carousel_slider",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/doctors-listing-carousel-slider.jpg',
						"dependency" => array('element' => 'style', 'value' => array("carousel_slider")),
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
                        // 'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'inwavethemes' ),
                        'group' => __( 'Design Options', 'inwavethemes' )
                    )
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            extract(shortcode_atts(array(
                "style" => 'default',
				"css" => "",
                "class" => ""
                            ), $atts));

	
            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }
			
			 $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);
            ob_start();
            echo do_shortcode('[inmedical_appoinment_form style="'.$style.'" class="' . $class . '"]');
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Appoinment_Form();
