<?php

/*
 * Inwave_Departments_Slider_Custom for Visual Composer
 */
if (!class_exists('Inwave_Departments_Slider_Custom')) {

    class Inwave_Departments_Slider_Custom extends Inwave_Shortcode2
    {

        protected $name = 'inwave_departments_slider_custom';
        protected $name2 = 'inwave_department_item';

        function init_params()
        {
            return array(
                "name" => __("Departments Slider Custom", 'inwavethemes'),
                "base" => $this->name,
                "content_element" => true,
                'category' => 'Custom',
                "description" => "",
                "as_parent" => array('only' => 'inwave_department_item'),
                "show_settings_on_create" => true,
                "js_view" => 'VcColumnView',
                'icon' => 'iw-default',
                "params" => array(
                    array(
                        'type' => 'textfield',
                        "heading" => __("Link All Department", "inwavethemes"),
                        "value" => "#",
                        "param_name" => "link_all_department",
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Link All Department Text", "inwavethemes"),
                        "value" => "See all our departments",
                        "param_name" => "link_all_department_text",
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop", "inwavethemes"),
                        "param_name" => "item_desktop",
                        "value" => '4',
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop Small", "inwavethemes"),
                        "param_name" => "item_desktop_small",
                        "value" => '2',
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => __("AutoPlay Slider", "inwavethemes"),
                        "param_name" => "auto_play",
                        "value" => array(
                            'No' => 'no',
                            'Yes' => 'yes'
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => "Style Navigation",
                        "param_name" => "style_navigation",
                        "value" => array(
                            "Style 1 - Out item" => "navigation_v1",
                            "Style 2 - In item" => "navigation_v2",
                        ),
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
                "name" => __("Department Item", 'inwavethemes'),
                "base" => $this->name2,
                "content_element" => true,
                'icon' => 'iw-default',
                'category' => 'Custom',
                "description" => __("Add a set of tabs and give some custom style.", "inwavethemes"),
                "show_settings_on_create" => true,
                "as_child" => array('only' => 'inwave_departments_slider_custom'),
                "params" => array(
                    array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Title", "inwavethemes"),
                        "description" => "",
                        "value" => "",
                        "param_name" => "title",
                    ),
                    array(
                        'type' => 'textarea',
                        "heading" => __("Description", "inwavethemes"),
                        "description" => "",
                        "value" => "",
                        "param_name" => "description",
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Link Read More", "inwavethemes"),
                        "value" => "#",
                        "param_name" => "link_read_more",
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Read more text", "inwavethemes"),
                        "value" => "Read more",
                        "param_name" => "read_more_text",
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Image Department", "inwavethemes"),
                        "param_name" => "img_department",
                        "description" => __("Image Department", "inwavethemes"),
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Icon Image Department", "inwavethemes"),
                        "param_name" => "icon_img",
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

            $output = $class ='';
            extract(shortcode_atts(array(
                "link_all_department" => "",
                "link_all_department_text" => "",
                "item_desktop" => "",
                "item_desktop_small" => "",
                "auto_play" => "",
                "style_navigation" => "",
                "class" => "",
            ), $atts));

            wp_enqueue_style('owl-carousel');
            wp_enqueue_style('owl-theme');
            wp_enqueue_style('owl-transitions');
            wp_enqueue_script('owl-carousel');

            $data_plugin_options = array(
                "navigation"=>true,
                "autoHeight"=>false,
                "pagination"=>false,
                "autoPlay"=>$auto_play == 'yes' ? true : false,
                "paginationNumbers"=> false,
                "items"=>$item_desktop,
                "itemsDesktop"=>array(1199, $item_desktop),
                "itemsDesktopSmall"=>array(991, $item_desktop_small),
                "itemsTablet"=>array(767, 2),
                "itemsTabletSmall"=>false,
                "itemsMobile"=>array(479, 1),
                "navigationText" => array("<i class=\"fa fa-chevron-left\"></i>", "<i class=\"fa fa-chevron-right\"></i>")
            );


            $output .= '<div class="iw-departments-slider-custom">';
            $output .= '<div class="iw-department style1 carousel-v2">';
            $output .= '<div class="iw-department-list '.$style_navigation.'">';
            $output .= '<div class="owl-carousel" data-plugin-options="'.htmlspecialchars(json_encode($data_plugin_options)).'">';
            $output .= do_shortcode($content);
            $output .= '</div>';
            $output .= '</div>';
            if ($link_all_department && $link_all_department_text) {
                $output .= '<div class="readmore-department">';
                    $output .= '<a href="'.$link_all_department.'">'.$link_all_department_text.'</a>';
                $output .= '</div>';
            }
            $output .= '</div>';
            $output .= '</div>';


            return $output;
        }

        // Shortcode handler function for item
        function init_shortcode2($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes($this->name2, $atts) : $atts;

            $output = $class = '';
            $content = do_shortcode($content);
            $content = preg_replace('/^\<\/p\>(.*)\<p\>$/Usi', '$1', $content);

            extract(shortcode_atts(array(
                'title' => '',
                'description' => '',
                'link_read_more' => '',
                'read_more_text' => '',
                'img_department' => '',
                'icon_img' => '',
                'class' => ''
            ), $atts));

            $img_tag = '';
            if ($img_department) {
                $img_department = wp_get_attachment_image_src($img_department, 'large');
                $url_img = inwave_resize($img_department[0], 269, 395, true);
                $img_tag .= '<img src="' . esc_url($url_img) . '" alt="' . $title . '">';
            }
            $icon_image = '';
            $content_info_class = 'no-icon';
            if ($icon_img) {
                $icon_img = wp_get_attachment_image_src($icon_img, 'large');
                $icon_img = $icon_img[0];
                $icon_image .= '<img src="' . esc_url($icon_img) . '" alt="' . $title . '">';
                $content_info_class = 'info-icon';
            }

            $output .= '<div class="department-item">';
                $output .= '<div class="content-item-wrap">';
                    $output .= '<div class="department-image">';
                        $output .= $img_tag;
                    $output .= '</div>';

                    $output .= '<div class="content-item">';
                        $output .= '<div class="background">';

                            $output .= '<div class="content-top">';
                                if($icon_image){
                                    $output .= '<div class="department-icon">'.$icon_image.'</div>';
                                }
                                $output .= '<div class="content-info '.$content_info_class.'">';
                                    if ($title && $link_read_more){
                                        $output .= '<h3 class="title">
                                            <a href="'.$link_read_more.'">'.$title.'</a>
                                        </h3>';
                                    }
                                $output .= '</div>';
                            $output .= '</div>';

                            $output .= '<div class="content-bottom">';
                                $output .= '<div class="content-description">';
                                    if ($description){
                                        $output .= $description;
                                    }
                                    if ($read_more_text && $link_read_more){
                                        $output .= '<div class="readmore-department-detail"><a href="'.$link_read_more.'">'.$read_more_text.'</a></div>';
                                    }
                                $output .= '</div>';
                            $output .= '</div>';
                        $output .= '</div>';
                    $output .= '</div>';

                $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }
}

new Inwave_Departments_Slider_Custom;

if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Inwave_Departments_Slider_Custom extends WPBakeryShortCodesContainer
    {
    }
}
