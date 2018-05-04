<?php
/*
 * Inwave_Button for Visual Composer
 */
if (!class_exists('Inwave_Button')) {

    class Inwave_Button extends Inwave_Shortcode{

        protected $name = 'inwave_button';

        function register_scripts()
        {
            wp_enqueue_style('iw_button', plugins_url('inwave-common/assets/css/iw-button.css'), array(), INWAVE_COMMON_VERSION);
        }

        function init_params() {
            return array(
                'name' => __("Button", 'inwavethemes'),
                'description' => __('Insert a button with some styles', 'inwavethemes'),
                'base' => 'inwave_button',
                'icon' => 'iw-default',
                'category' => 'Custom',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "heading" => "Button style",
                        "param_name" => "style",
                        "value" => array(
                            "Button 1 - Background color theme" => "button1",
                            "Button 2 - background none" => "button2",
                        )
                    ),
                    array(
                        "type" => "colorpicker",
                        "heading" => __("Button Border Color", "inwavethemes"),
                        "param_name" => "border_color_button",
                        "description" => __('Border color for Button', "inwavethemes"),
                        "value" => "",
                        "dependency" => array('element' => 'style', 'value' => 'button2')
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Button size",
                        "param_name" => "button_size",
                        "value" => array(
                            "Normal" => "button-normal",
                            "Small" => "button-small",
                            "Large" => "button-large",
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Shape",
                        "admin_label" => true,
                        "param_name" => "shape",
                        "value" => array(
                            "Square" => "square",
                            "Rounded" => "rounded",
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Button effect",
                        "param_name" => "button_effect",
                        "value" => array(
                            "None" => "effect-none",
                            "Effect 1 (only for button 1)" => "effect-1",
                            "Effect 2" => "effect-2",
                            "Effect 3 (only for button 1)" => "effect-3",
                            "Effect 4 (only for button 1)" => "effect-4",
                            "Effect 5 (only for button 2)" => "effect-5",
                        )
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Button Text", "inwavethemes"),
                        "param_name" => "button_text",
                        "holder" => "div",
                        "value"=>"Click here"
                    ),
                    array(
                        "type" => "colorpicker",
                        "heading" => __("Button Color", "inwavethemes"),
                        "param_name" => "color_button",
                        "description" => __('Color for Button', "inwavethemes"),
                        "value" => "",
                    ),
                    array(
                        'type' => 'dropdown',
                        "heading" => __("Button Font Weight", "inwavethemes"),
                        "description" => __('Font Weight Button Text', "inwavethemes"),
                        "param_name" => "font_weight",
                        "value" => array(
                            "Default" => "",
                            "Extra Bold" => "900",
                            "Bold" => "700",
                            "SemiBold" => "600",
                            "Medium" => "500",
                            "Normal" => "400",
                            "Light" => "300"
                        )
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Button Link", "inwavethemes"),
                        "param_name" => "button_link",
                        "value"=>"#"
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Button Width",
                        "param_name" => "button_width",
                        "value" => array(
                            "Width Auto" => "width-auto",
                            "Full Width" => "full-width",
                        )
                    ),
                    array(
                        "type" => "iw_icon",
                        "heading" => __("Select Icon", "inwavethemes"),
                        "param_name" => "icon",
                        "value" => "",
                        "admin_label" => true,
                        "description" => __("Click and select icon of your choice. You can get complete list of available icons here: <a target='_blank' href='http://fortawesome.github.io/Font-Awesome/icons/'>Font-Awesome</a>", "inwavethemes"),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Icon Position", "inwavethemes"),
                        "param_name" => "icon_position",
                        "value" => array(
                            "Left" => "icon-left",
                            "Right" => "icon-right",
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Border Icon", "inwavethemes"),
                        "param_name" => "border_icon",
                        "value" => array(
                            "No" => "",
                            "Yes" => "border-icon",
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("button align", "inwavethemes"),
                        "param_name" => "align",
                        "value" => array(
                            "Default" => "",
                            "Left" => "left",
                            "Right" => "right",
                            "Center" => "center"
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        'group' => __( 'Button Hover', 'js_composer' ),
                        "heading" => "Background button hover",
                        "param_name" => "bg_button_hover",
                        "value" => array(
                            "Default" => "",
                            "Color theme" => "bg-hover-color-theme",
                            "Grey" => "bg-hover-grey",
                            "White" => "bg-hover-white",
                            "Black" => "bg-hover-black",
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        'group' => __( 'Button Hover', 'js_composer' ),
                        "heading" => "Color button hover",
                        "param_name" => "button_color_hover",
                        "value" => array(
                            "Default" => "",
                            "Color theme" => "hover-color-theme",
                            "Grey" => "hover-color-grey",
                            "White" => "hover-color-white",
                            "Black" => "hover-color-black",
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        'group' => __( 'Button Hover', 'js_composer' ),
                        "heading" => "Border color button hover",
                        "param_name" => "border_color_hover",
                        "value" => array(
                            "Default" => "",
                            "Color theme" => "hover-border-theme",
                            "Grey" => "hover-border-grey",
                            "White" => "hover-border-white",
                            "Black" => "hover-border-black",
                        )
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
                        // 'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
                        'group' => __( 'Design Options', 'js_composer' )
                    )
                )
            );
        }

        function init_shortcode($atts, $content = null){
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $class = $button_link = $button_text = $align = $css = $button_size = $shape = $icon = $style = $border_color_button = $button_effect = $icon_position = $button_width = $border_icon = $color_button = $font_weight = $bg_button_hover = $button_color_hover = $border_color_hover = '';
            extract(shortcode_atts(array(
                'class' => '',
                'button_link' => '',
                'button_text' => '',
                'button_effect' => 'None',
                'align' => '',
                'css' => '',
                'button_size' => '',
                'shape' => '',
                'style' => 'button1',
                'border_color_button' => '',
                'button_width' => 'width-auto',
                'icon' => '',
                'icon_position' => '',
                'color_button' => '',
                'font_weight' => '',
                'border_icon' => '',
                'bg_button_hover' => '',
                'button_color_hover' => '',
                'border_color_hover' => '',
            ), $atts));

            return self::inwave_button_shortcode_html($button_link,$button_text,$align,$css,$button_size,$shape,$style,$border_color_button,$icon,$button_effect,$icon_position,$button_width,$color_button,$font_weight,$border_icon,$class,$bg_button_hover,$button_color_hover,$border_color_hover);
        }

        public static function inwave_button_shortcode_html($button_link,$button_text,$align,$css,$button_size,$shape,$style,$border_color_button,$icon,$button_effect,$icon_position,$button_width,$color_button,$font_weight,$border_icon,$class,$bg_button_hover,$button_color_hover,$border_color_hover =''){
            $output='';
            $class .= ' '.$style.' '. vc_shortcode_custom_css_class( $css);
            if($button_size){
                $class .= ' i' . $button_size;
            }
            if($shape){
                $class .= ' '. $shape;
            }
            if($align){
                $class.= ' '.$align.'-text';
            }
            if($button_effect){
                $class.= ' '.$button_effect;
            }
            if($icon_position){
                $class.= ' '.$icon_position;
            }
            if($border_icon){
                $class.= ' '.$border_icon;
            }
            if($icon){
                $class.= ' icon';
            }
            if($bg_button_hover){
                $class.= ' '.$bg_button_hover;
            }
            if($button_color_hover){
                $class.= ' '.$button_color_hover;
            }
            if($border_color_hover){
                $class.= ' '.$border_color_hover;
            }
            $icon_class = '';
            if($icon){
                $icon_class = esc_attr($icon);
            }
            $extracss = array();
            if($font_weight){
                $extracss[] .= 'font-weight: '.esc_attr($font_weight);
            }
            if($color_button){
                $extracss[] .= 'color: '.esc_attr($color_button);
            }
            if($border_color_button){
                $extracss[] .= 'border-color: '.esc_attr($border_color_button);
            }
            switch($style){
                case 'button1':
                case 'button2':
                    $output .=  '<div class="iw-button '.$class.'">';
                        $output .= '<a class="'.$button_width.'" href="'.$button_link.'" style="'.implode("; ",$extracss).'">';
                            $output .= '<span class="button-text">'.$button_text.'</span>';
                            if($icon_class) {
                                $output .= '<span class="button-icon"><i class="'.esc_attr($icon_class).'"></i></span>';
                            }
                        $output .= '</a>';
                    $output .= '</div>';
                    break;
            }
            return $output;
        }
    }
}

new Inwave_Button;
