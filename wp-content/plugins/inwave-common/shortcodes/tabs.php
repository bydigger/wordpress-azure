<?php

/*
 * Inwave_Tabs for Visual Composer
 */
if (!class_exists('Inwave_Tabs')) {

    class Inwave_Tabs extends Inwave_Shortcode2
    {

        protected $name = 'inwave_tabs';
        protected $name2 = 'inwave_tab_container';
        protected $layout;
        protected $item_count = 0;
        protected $first_item = 0;
        protected $active_item = 1;

        function init_params()
        {
            return array(
                "name" => __("Tabs", 'inwavethemes'),
                "base" => $this->name,
                "content_element" => true,
                'category' => 'Custom',
                "description" => __("Add a set of tabs and give some custom style.", "inwavethemes"),
                "as_parent" => array('only' => 'inwave_tab_container'),
                "show_settings_on_create" => true,
                "js_view" => 'VcColumnView',
                'icon' => 'iw-default',
                "params" => array(
                    array(
                        "type" => "dropdown",
                        "class" => "iw-tabs-style",
                        "heading" => "Style",
                        "param_name" => "layout",
                        "value" => array(
                            "Tab - Style 1" => "layout1",
                            "Tab - Style 2" => "layout2",
                            "Tab - Style 3" => "layout3",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background color theme)", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/tab-style1.png',
                        "dependency" => array('element' => 'layout', 'value' => 'layout1')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background none)", "inwavethemes"),
                        "param_name" => "preview_style2",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/tab-style2.png',
                        "dependency" => array('element' => 'layout', 'value' => 'layout2')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style3",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/tab-style3.png',
                        "dependency" => array('element' => 'layout', 'value' => 'layout3')
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Item active", "inwavethemes"),
                        "value" => "1",
                        "description" => __("Choose item active", "inwavethemes"),
                        "param_name" => "item_active",
                    ),
                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "value" => "",
                        "description" => __("Write your own CSS and mention the class name here.", "inwavethemes"),
                    ),
                )
            );
        }

        function init_params2()
        {
            return array(
                "name" => __("Tab Container", 'inwavethemes'),
                "base" => $this->name2,
                "content_element" => true,
                'icon' => 'iw-default',
                'category' => 'Custom',
                "description" => __("Add a set of tabs and give some custom style.", "inwavethemes"),
                "show_settings_on_create" => true,
                "as_child" => array('only' => 'inwave_tabs'),
                "as_parent" => array('except' => 'inwave_tabs,inwave_tab_container'),
                "js_view" => 'VcColumnView',
                "params" => array(
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Title", "inwavethemes"),
                        "value" => "This is title",
                        "param_name" => "title"
                    ),
                    array(
                        'type' => 'iw_icon',
                        "heading" => __("Icon", "inwavethemes"),
                        "value" => "",
                        "description" => __("Click and select icon of your choice. You can get complete list of available icons here: <a target='_blank' href='http://fortawesome.github.io/Font-Awesome/icons/'>Font-Awesome</a>", "inwavethemes"),
                        "param_name" => "icon",
                    ),
                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "value" => "",
                        "description" => __("Write your own CSS and mention the class name here.", "inwavethemes"),
                    ),
                )
            );
        }


        // Shortcode handler function for list
        function init_shortcode($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes($this->name, $atts) : $atts;

            $output = $layout = $item_active = $class ='';
            $id = 'iwt-' . rand(10000, 99999);
            extract(shortcode_atts(array(
                "class" => "",
                'layout' => '1',
                'item_active' => ''
            ), $atts));

            $this->first_item = true;
            $this->layout = $layout;
            $this->item_count = 0;
            $item_active_default = 1;
            if ($item_active) {
                $item_active_default = $item_active;
            }
            $this->active_item = $item_active;
            $type = 'tab';
            $matches = array();
            $count = preg_match_all('/\[inwave_tab_container\s+title="([^\"]+)"\s+icon="([^\"]+)"(.*)\]/Usi', $content, $matches);
            $output .= '<div id="' . $id . '" class="iw-tabs ' . $class . ' ' . $layout . '">';

            $output.= '<div class="iw-tab-items">';
            if ($count) {
                $i = 0;
                foreach ($matches[1] as $key => $value) {
                    $i++;
                    $icon = isset($matches[2][$key]) ? $matches[2][$key] : '';
                    $output.= '<div class="iw-tab-item ' . ($i == $item_active ? 'active' : '') . '">';
                    $output.= '<div class="iw-tab-item-inner">';
                    $output.= '<div class="iw-tab-title"><span class="tab-icon"><i class="'.$icon.'"></i></span><span>' . $value . '</span></div>';
                    $output.= '</div>';
                    $output.= '</div>';
                }
            }
            $output .= '</div>';

            $output .= '<div class="iw-tab-content">';
            $output .= '<div class="iw-tab-content-inner">';
            $output .= do_shortcode($content);
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="clearfix"></div>';
            $output .= '</div>';
                $output .= '<script type="text/javascript">';
                $output .= '(function($){';
                $output .= '$(document).ready(function(){';
                $output .= '$("#' . $id . '").iwTabs("' . $type . '");';
                $output .= '});';
                $output .= '})(jQuery);';
                $output .= '</script>';


            return $output;
        }

        // Shortcode handler function for item
        function init_shortcode2($atts, $content = null)
        {
            $output = $title = $icon = $class = '';
            $content = do_shortcode($content);
            $content = preg_replace('/^\<\/p\>(.*)\<p\>$/Usi', '$1', $content);
            $this->item_count++;

            extract(shortcode_atts(array(
                'title' => '',
                'icon' => '',
                'class' => ''
            ), $atts));

            $active_item_content = 1;
            if($this->active_item)  {
                $active_item_content = $this->active_item;
            }

            $output .= '<div class="iw-tab-item-content ' . ($this->item_count == $active_item_content ? 'active' : 'next') . ' ' . $class . '">';
            $output .= $content;
            $output .= '</div>';
            if ($this->first_item) {
                $this->first_item = false;
            }
            return $output;
        }
    }
}

new Inwave_Tabs;

if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Tabs extends WPBakeryShortCodesContainer
    {
    }
    class WPBakeryShortCode_Inwave_Tab_Container extends WPBakeryShortCodesContainer
    {
    }
}
