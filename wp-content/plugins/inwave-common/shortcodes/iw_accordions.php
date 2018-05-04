<?php

/*
 * Inwave_Accordions for Visual Composer
 */
if (!class_exists('Inwave_Accordions')) {

    class Inwave_Accordions extends Inwave_Shortcode2
    {

        protected $name = 'inwave_accordions';
        protected $name2 = 'inwave_accordion_item';
        protected $layout;
        protected $item_count = 0;
        protected $first_item = 0;
        protected $item_row = 0;
        protected $icon_title = 'fa fa-check';
        protected $active_item = 1;

        function init_params()
        {
            return array(
                "name" => __("Accordions", 'inwavethemes'),
                "base" => $this->name,
                "content_element" => true,
                'category' => 'Custom',
                "description" => __("Add a set of Accordion and give some custom style.", "inwavethemes"),
                "as_parent" => array('only' => 'inwave_accordion_item'),
                "show_settings_on_create" => true,
                "js_view" => 'VcColumnView',
                'icon' => 'iw-default',
                "params" => array(
                    array(
                        "type" => "dropdown",
                        "class" => "iw-accordion-style",
                        "heading" => "Style",
                        "param_name" => "layout",
                        "value" => array(
                            __("Accordion 1", "inwavethemes") => "accordion1",
                            __("Accordion 2", "inwavethemes") => "accordion2",
                            __("Accordion 3", "inwavethemes") => "accordion3",
                            __("Accordion 4", "inwavethemes") => "accordion4",
                            __("Accordion 5", "inwavethemes") => "accordion5",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background color theme)", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/accordion-style1.png',
                        "dependency" => array('element' => 'layout', 'value' => 'accordion1')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background none)", "inwavethemes"),
                        "param_name" => "preview_style2",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/accordion-style2.png',
                        "dependency" => array('element' => 'layout', 'value' => 'accordion2')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style3",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/accordion-style3.png',
                        "dependency" => array('element' => 'layout', 'value' => 'accordion3')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style4",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/accordion-style4.png',
                        "dependency" => array('element' => 'layout', 'value' => 'accordion4')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style5",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/accordion-style5.png',
                        "dependency" => array('element' => 'layout', 'value' => 'accordion5')
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
                "name" => __("Accordion Item", 'inwavethemes'),
                "base" => $this->name2,
                'icon' => 'iw-default',
                'category' => 'Custom',
                "description" => __("Add a set of Accordion and give some custom style.", "inwavethemes"),
                "show_settings_on_create" => true,
                "as_child" => array('only' => 'inwave_accordion'),
                "params" => array(
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Title", "inwavethemes"),
                        "value" => "This is title",
                        "param_name" => "title"
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Font Size Title", "inwavethemes"),
                        "value" => "",
                        "description" => __('Custom font-size for heading title. Example 14', "inwavethemes"),
                        "param_name" => "font_size_title"
                    ),
                    array(
                        'type' => 'textfield',
						"admin_label" => true,
                        "heading" => __("Sub Title", "inwavethemes"),
                        "value" => "",
                        "param_name" => "sub_title"
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Accordion Image", "inwavethemes"),
                        "param_name" => "icon_img",
                        "description" => __("Select image instead of font icon above", "inwavethemes"),
                    ),
                    array(
                        'type' => 'textarea_html',
                        "admin_label" => true,
                        "heading" => __("Content", "inwavethemes"),
                        "value" => '',
                        "param_name" => "content"
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Link read more", "inwavethemes"),
                        "param_name" => "link",
                        "value"=>""
                    ),
                    array(
                        "type" => "textfield",
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

            $output = $layout = $icon_title = $item_active = $class ='';
            $id = 'iwa-' . rand(10000, 99999);
            extract(shortcode_atts(array(
                "class" => "",
                "icon_title" => '',
                "item_active" => '',
                'layout' => '',
            ), $atts));

            $this->first_item = true;
            $this->layout = $layout;
            $this->item_count = 0;
            $this->icon_title = $icon_title;
            $this->active_item = $item_active;
            $output .= '<div class="iw-shortcode-accordions">';
            $output .= '<div id="' . $id . '" class="iw-accordions ' . $class . ' ' . $layout . '">';
            switch ($layout) {
                default:
                    $output .= '<div class="iw-accordions-item">';
                    $output .= do_shortcode($content);
                    $output .= '</div>';
                    $output .= '<script type="text/javascript">';
                    $output .= '(function($){';
                    $output .= '$(document).ready(function(){';
                    $output .= '$("#' . $id . '").iwTabs("accordion");';
                    $output .= '});';
                    $output .= '})(jQuery);';
                    $output .= '</script>';
                    break;
            }
            $output .= '</div>';
            $output .= '</div>';


            return $output;
        }

        // Shortcode handler function for item
        function init_shortcode2($atts, $content = null)
        {
            $output = $title = $sub_title = $font_size_title = $link = $icon_img = $description = $class = '';
            $this->item_count++;

            extract(shortcode_atts(array(
                'title' => '',
                'sub_title' => '',
                'font_size_title' => '',
                'link' => '',
                'icon_img' => '',
                'class' => ''
            ), $atts));

            if ($icon_img) {
                $icon_img = wp_get_attachment_image_src($icon_img,'full');
                $icon_img = $icon_img[0];
                $icon_img = '<img src="' . $icon_img . '" alt="">';
            }

            $icon_class = 'fa fa-check';
            $icon = $this->icon_title;
            if ($icon) {
                $icon_class = $icon;
            }

            $active_item_content = 1;
            if($this->active_item)  {
                $active_item_content = $this->active_item;
            }
			
			$title_style = '';
			if ($font_size_title){
				$title_style = ' style="font-size:' . $font_size_title . 'px;"';
			}

            switch ($this->layout) {
                default:
                    $output .= '<div class="iw-accordion-item ' . $class . '">';
                    $output .= '<div class="iw-accordion-header ' . ($this->item_count == $active_item_content ? 'active' : '') . '">';
                    $output .= '<div class="iw-accordion-title" '.$title_style.'>' . $title . '</div>';
                    $output .= '</div>';
                    $output .= '<div class="iw-accordion-content" ' . ($this->item_count == $active_item_content ? '' : 'style="display: none;"') . '>';
                    if($icon_img) {
                        $output .= '<div class="iw-accordion-img">' . $icon_img . '</div>';
                    }
                    $output .= '<div class="iw-accordion-info">';
                    if ($sub_title) {
                        $output .= '<div class="iw-sub-title">' . $sub_title . '</div>';
                    }
                    if ($content) {
                        $output .= '<div class="iw-desc">' . apply_filters('the_content', $content) . '</div>';
                    }
                    if ($link) {
                        $output .= '<div class="iw-readmore"><a class="theme-color" href="'.$link.'">' . __("Read more", "inwavethemes") . '</a></div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    if ($this->first_item) {
                        $this->first_item = false;
                    }
                break;

                case 'accordion2':
                    $output .= '<div class="iw-accordion-item ' . $class . '">';
                    $output .= '<div class="iw-accordion-header ' . ($this->item_count == $active_item_content ? 'active' : '') . '">';
                    $output .= '<div class="iw-accordion-title" '.$title_style.'><i class="ion-chevron-up"></i><i class="ion-chevron-down"></i><span>' . $title . '</span></div>';
                    $output .= '</div>';
                    $output .= '<div class="iw-accordion-content" ' . ($this->item_count == $active_item_content ? '' : 'style="display: none;"') . '>';
                    if($icon_img) {
                        $output .= '<div class="iw-accordion-img">' . $icon_img . '</div>';
                    }
                    $output .= '<div class="iw-accordion-info">';
                    if ($sub_title) {
                        $output .= '<div class="iw-sub-title">' . $sub_title . '</div>';
                    }
                    if ($content) {
                        $output .= '<div class="iw-desc">' . $content . '</div>';
                    }
                    if ($link) {
                        $output .= '<div class="iw-readmore"><a class="theme-color" href="'.$link.'">' . __("Read more", "inwavethemes") . '</a></div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    if ($this->first_item) {
                        $this->first_item = false;
                    }
                break;
                case 'accordion3':
                    $output .= '<div class="iw-accordion-item ' . $class . '">';
                    $output .= '<div class="iw-accordion-header ' . ($this->item_count == $active_item_content ? 'active' : '') . '">';
                    $output .= '<div class="iw-accordion-title" '.$title_style.'><i class="fa fa-check-circle-o"></i><span>' . $title . '</span></div>';
                    $output .= '</div>';
                    $output .= '<div class="iw-accordion-content" ' . ($this->item_count == $active_item_content ? '' : 'style="display: none;"') . '>';
                    if($icon_img) {
                        $output .= '<div class="iw-accordion-img">' . $icon_img . '</div>';
                    }
                    $output .= '<div class="iw-accordion-info">';
                    if ($sub_title) {
                        $output .= '<div class="iw-sub-title">' . $sub_title . '</div>';
                    }
                    if ($content) {
                        $output .= '<div class="iw-desc">' . $content . '</div>';
                    }
                    if ($link) {
                        $output .= '<div class="iw-readmore"><a class="theme-color" href="'.$link.'">' . __("Read more", "inwavethemes") . '</a></div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    if ($this->first_item) {
                        $this->first_item = false;
                    }
                break;
                case 'accordion5':
                    $output .= '<div class="iw-accordion-item ' . $class . '">';
                    $output .= '<div class="iw-accordion-header ' . ($this->item_count == $active_item_content ? 'active' : '') . '">';
                    $output .= '<div class="iw-accordion-title" '.$title_style.'>';
                    if($icon_img) {
                        $output .= '<div class="iw-accordion-img">' . $icon_img . '</div>';
                    }
                    $output .= '<div class="item-info-content">';
                    $output .= '<h3>' . $title . '</h3>';
                    if ($sub_title) {
                        $output .= '<div class="iw-sub-title">' . $sub_title . '</div>';
                    }
                    $output .= '</div>'; //item-info-content
                    $output .= '</div>'; //iw-accordion-title
                    $output .= '</div>'; //iw-accordion-header
                    $output .= '<div class="clearfix"></div>';
                    $output .= '<div class="iw-accordion-content" ' . ($this->item_count == $active_item_content ? '' : 'style="display: none;"') . '>';

                    if ($content) {
                        $output .= '<div class="iw-desc">' . $content . '</div>';
                    }
                    if ($link) {
                        $output .= '<div class="iw-readmore"><a href="'.$link.'">' . __("Read more", "inwavethemes") . '<i class="icon ion-arrow-right-c"></i></a></div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    if ($this->first_item) {
                        $this->first_item = false;
                    }
                break;
            }
            return $output;
        }
    }
}

new Inwave_Accordions;

if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Accordions extends WPBakeryShortCodesContainer
    {
    }
}
