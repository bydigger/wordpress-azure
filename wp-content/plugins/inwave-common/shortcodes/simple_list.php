<?php

/*
 * Inwave_Simple_List for Visual Composer
 */
if (!class_exists('Inwave_Simple_List')) {

    class Inwave_Simple_List extends Inwave_Shortcode{

        protected $name = 'inwave_simple_list';

        function init_params() {
            return array(
                'name' => __("Simple List", 'inwavethemes'),
                'description' => __('Add a items list with some simple style', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
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
                                    </ul>',
                        "description" => "Format: <br>Inactive Item: ".htmlspecialchars('<li>Lorem ipsum dolor sit amet</li>')."<br>Active Item: ".htmlspecialchars('<li class="active">Lorem ipsum dolor sit amet</li>')."",
                        "param_name" => "content"
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
                    array(
                        "type" => "dropdown",
                        "class" => "",

                        "heading" => "Style",
                        "param_name" => "style",
                        "value" => array(
                            "None" => "none",
                            "Check Mark" => "check-mark",
                            "Angle Right" => "angle-right",
                            "Chevron Circle Right" => "chevron-circle-right",
                            "Stars" => "stars",
                            "circle" => "circle",
                            "Check Circle" => "check-circle",
                            "Check Circle O" => "check-circle-o",
                            "Numbers" => "numbers"
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Style",
                        "class" => "",
                        "heading" => __("Text align", "inwavethemes"),
                        "param_name" => "align",
                        "value" => array(
                            "Default" => "",
                            "Left" => "left",
                            "Right" => "right",
                            "Center" => "center"
                        )
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

            $output = $class = $style = $align = $css = '';
            $content = preg_replace('/^\<\/p\>(.*)\<p\>$/Usi','$1',$content);
            extract(shortcode_atts(array(
                'style' => 'none',
                'class' => '',
                'align' => '',
                'css' => ''
            ), $atts));
            $class .= ' '.$style.' '. vc_shortcode_custom_css_class( $css);

            if($align){
                $class.= ' '.$align.'-text';
            }
            $content = rtrim($content, '</p>');
            $content = ltrim($content, '</p>');

            $output .= '<div class="simple-list ' . $class . '">';


            $i = 0;
            do {
                $i++;
                if($style == 'none') {
                    $replacer = '<#$1><div class="list-content">';
                }
                else if($style == 'numbers') {
                    $replacer = '<#$1><span class="number"> '. ($i<10?'0':'') . $i . '</span><div class="list-content">';
                }
                else if($style == 'stars') {
                    $replacer = '<#$1> <i class="fa fa-star"></i><div class="list-content">';
                }
                else if($style == 'check-circle') {
                    $replacer = '<#$1> <i class="fa fa-check-circle"></i><div class="list-content">';
                }
                else if($style == 'check-circle-o') {
                    $replacer = '<#$1> <i class="fa fa-check-circle-o"></i><div class="list-content">';
                }
                else if($style == 'circle') {
                    $replacer = '<#$1> <i class="fa fa-circle"></i><div class="list-content">';
                }
                else if($style == 'angle-right') {
                    $replacer = '<#$1> <i class="fa fa-chevron-right"></i><div class="list-content">';
                }
                else if($style == 'chevron-circle-right') {
                    $replacer = '<#$1> <i class="fa fa-chevron-circle-right"></i><div class="list-content">';
                }
                else{
                    $replacer = '<#$1> <i class="fa fa-check"></i><span class="list-content">';
                }
                $content = preg_replace('/\<li(.*)\>/Uis',$replacer, $content, 1, $count);
            } while ($count);
            $content = str_replace('<#','<li',$content);
            $content = str_replace('</li>','</div></li>',$content);
            $output .= $content;
            $output .= '</div>';
            return $output;
        }
    }
}

new Inwave_Simple_List;
