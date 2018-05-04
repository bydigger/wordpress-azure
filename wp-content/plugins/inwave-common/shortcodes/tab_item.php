<?php

/*
 * Inwave_Item_Info for Visual Composer
 */
if (!class_exists('Inwave_Tab_Item')) {

    class Inwave_Tab_Item extends Inwave_Shortcode{

        protected $name = 'inwave_tab_item';

        function init_params() {
            return array(
                'name' => __("Tab Item", 'inwavethemes'),
                'description' => __('Add a tab item for tabs', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Title", "inwavethemes"),
                        "value" => "",
                        "param_name" => "title",
                    ),
                    array(
                        'type' => 'textarea_html',
                        "heading" => __("Testimonial Content", "inwavethemes"),
                        "value" => "",
                        "param_name" => "content"
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Link", "inwavethemes"),
                        "value" => "#",
                        "param_name" => "link",
                    ),
					array(
                        'type' => 'textfield',
                        "heading" => __("Read more text", "inwavethemes"),
                        "value" => "Read more",
                        "param_name" => "readmore_text",
                    ),
                    array(
                        "type" => "dropdown",
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

            $output = $class = $title = $link = $align = $css = $readmore_text = '';
            extract(shortcode_atts(array(
                'title' => '',
                'link' => '',
				'readmore_text' => '',
                'align' => '',
                'css' => '',
                'class' => '',
            ), $atts));

            $class .= ' '. vc_shortcode_custom_css_class( $css);
            if($align){
                $class.= ' '.$align.'-text';
            }

            $output .= '<div class="iw-tab-info ' . $class . '">';
            if ($title){
                $output .= '<h3 class="title theme-color">'.$title.'</h3>';
            }
            if ($content){
                $output .= '<p class="description">'.$content.'</p>';
            }
            if ($link){
                $output .= '<div class="read-more"><a class="theme-color" href="'.$link.'">'.$readmore_text.'</a></div>';
            }
            $output .= '</div>';

            return $output;
        }
    }
}

new Inwave_Tab_Item;
