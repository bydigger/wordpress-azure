<?php

/*
 * Inwave_Opening_Hours for Visual Composer
 */
if (!class_exists('Inwave_Opening_Hours')) {

    class Inwave_Opening_Hours extends Inwave_Shortcode{

        protected $name = 'inwave_opening_hours';

        function init_params() {
            return array(
                'name' => __("Opening Hours", 'inwavethemes'),
                'description' => __('Add a opening hours', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "admin_label" => true,
                        "heading" => "Style",
                        "param_name" => "style",
                        "value" => array(
                            "Style 1"=> "style1",
                            "Style 2" => "style2",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style_1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/opening-hours-v1.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style_2",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/opening-hours.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style2')
                    ),
                    array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Title", "inwavethemes"),
                        "value" => "",
                        "param_name" => "title",
                    ),
                    array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Sub Title", "inwavethemes"),
                        "value" => "",
                        "param_name" => "sub_title",
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
                    array(
                        'type' => 'textarea',
                        "heading" => __("Description", "inwavethemes"),
                        "value" => "",
                        "param_name" => "description",
                    ),
                    array(
                        'type' => 'textarea',
                        "holder" => "div",
                        "heading" => __("Opening Hours", "inwavethemes"),
                        "value" => '<div><ul>
                                        <li>Internist doctor<span class="hour">08:00 - 20:00</span></li>
                                        <li>Family doctor<span class="hour">09:00 - 17:00</span></li>
                                        <li>Pediatrician doctor<span class="hour">08:00 - 16:00</span></li>
                                        <li>Physiotherapist<span class="hour">10:00 - 15:00</span></li>
                                    </ul></div>',
                        "param_name" => "content"
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Emergency line", "inwavethemes"),
                        "value" => "1-800-643-4300",
                        "param_name" => "emergency_line",
                        "dependency" => array('element' => 'style', 'value' => 'style2')
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
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

            $output = $style = $class = $title = $sub_title = $emergency_line = $description = $css = '';
            extract(shortcode_atts(array(
                'style' => '',
                'title' => '',
                'sub_title' => '',
                'description' => '',
                'emergency_line' => '',
                'css' => '',
                'class' => '',
            ), $atts));

            $class .= ' '.$style.' '. vc_shortcode_custom_css_class( $css);
            $content = rtrim($content, '</p>');
            $content = ltrim($content, '</p>');

            ob_start();
            switch ($style) {
                case 'style1':
                    $output .= '<div class="iw-opening-hours '.$class.' iw-heading style4">';
                    if ($title){
                    $output .= '<h3 class="iwh-title">' . $title . '</h3>';
                    }
                    if ($sub_title) {
                    $output .= '<div class="iwh-sub-title">' . $sub_title . '</div>';
                    }
                    if ($description) {
                    $output .= '<div class="iwh-description">' . $description . '</div>';
                    }
                    $output .= $content;
                    $output .= '</div>';
                    break;

                case 'style2':
                    $output .= '<div class="iw-opening-hours '.$class.' theme-bg">';
                    if ($title){
                    $output .= '<h3 class="title">' . $title . '</h3>';
                    }
                    if ($content){
                    $output .= $content;
                    }
                    if ($emergency_line){
                        $output .= '<div class="emergency-line">';
                        $output .= '<span class="text theme-bg">'.__("Emergency line", "inwavethemes").'</span>';
                        $output .= '<span class="hot-line">'.$emergency_line.'</span>';
                        $output .= '</div>';
                    }
                    if ($description) {
                        $output .= '<div class="description">' . $description . '</div>';
                    }
                    $output .= '</div>';
                    break;
            }
            return $output;
        }
    }
}

new Inwave_Opening_Hours;
