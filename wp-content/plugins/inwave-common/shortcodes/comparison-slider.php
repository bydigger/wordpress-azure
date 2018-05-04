<?php

/*
 * Inwave_Comparision_Slider for Visual Composer
 */
if (!class_exists('Inwave_Comparision_Slider')) {

    class Inwave_Comparision_Slider extends Inwave_Shortcode{

        protected $name = 'inwave_comparision_slider';

        function init_params() {
            return array(
                'name' => __("Comparision Slider", 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Before Image", "inwavethemes"),
                        "param_name" => "before_img",
                        "description" => __("Before Image", "inwavethemes"),
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("After Image", "inwavethemes"),
                        "param_name" => "after_img",
                        "description" => __("After Image", "inwavethemes"),
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

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $style = $class = $title = '';
            extract(shortcode_atts(array(
                'before_img' => '',
                'after_img' => '',
                'class' => '',
            ), $atts));

            $class .= ' '.$style.' ';

            $img_tag_before = '';
            if ($before_img) {
                $before_img = wp_get_attachment_image_src($before_img, 'large');
                $before_img = $before_img[0];
                $img_tag_before .= '<img src="' . $before_img . '" alt="">';
            }
            $img_tag_after = '';
            if ($after_img) {
                $after_img = wp_get_attachment_image_src($after_img, 'large');
                $after_img = $after_img[0];
                $img_tag_after .= '<img src="' . $after_img . '" alt="">';
            }
            $output .= '<div class="comparision-slider '.$class.'">';
            $output .= $img_tag_before;
            $output .= $img_tag_after;
            $output .= '</div>';
            return $output;
        }
    }
}

new Inwave_Comparision_Slider;
