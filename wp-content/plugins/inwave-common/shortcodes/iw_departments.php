<?php

/*
 * Inwave_Department_Listing for Visual Composer
 */
if (!class_exists('Inwave_Departments')) {

    class Inwave_Departments extends Inwave_Shortcode{

        protected $name = 'iw_departments';

        function getIwDepartments() {
            global $wpdb;
            $departments = $wpdb->get_results('SELECT post_name, post_title FROM ' . $wpdb->prefix . 'posts WHERE post_status="publish" AND post_type="indepartment"');
            $newDepartments = array();
            $newDepartments[__("All", "inwavethemes")] = '';
            foreach ($departments as $department) {
                $newDepartments[$department->post_name] = $department->post_title;
            }
            return $newDepartments;
        }

        function init_params() {

            return array(
                'name' => __('Departments', 'inwavethemes'),
                'description' => __('Create a list of departments', 'inwavethemes'),
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
                            "Style 1 - Grid" => "grid",
                            "Style 2 - Grid 6 item" => "grid_v2",
                            "Style 3 - Carousel Slider" => "carousel_slider",
                            "Style 4 - Carousel Slider V2" => "carousel_slider_v2",
                            "Style 5 - Slick Slider" => "slick_slider",
                            "Style 6 - Carousel Slider V3" => "carousel_slider_v3",
                        )
                    ),
					array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/departments_grid-v1.png',
						"dependency" => array('element' => 'style', 'value' => 'grid')
                    ),
					array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style2",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/departments_grid-v2.png',
						"dependency" => array('element' => 'style', 'value' => 'grid_v2')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style3",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/department_carousel_slider.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'carousel_slider')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style4",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/department_carousel_slider-v2.png',
                        "dependency" => array('element' => 'style', 'value' => 'carousel_slider_v2')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style5",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/department_slick_slider.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'slick_slider')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style6",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/departments_grid-v1.png',
                        "dependency" => array('element' => 'style', 'value' => 'carousel_slider_v3')
                    ),
//                    array(
//                        "type" => "dropdown",
//                        "heading" => "Show Paging",
//                        "param_name" => "show_paging",
//                        "value" => array(
//                            "Yes" => "1",
//                            "No" => "0"
//                        ),
//                    ),
					array(
                        "type" => "dropdown",
                        "heading" => "Order By",
                        "param_name" => "order_by",
                        "value" => array(
                            "Ordering" => "menu_order",
                            "Date" => "date",
                            "Title" => "title",
                            "Department ID" => "ID",
                            "Name" => "name",
                            "Random" => "rand"
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Order Direction",
                        "param_name" => "order_dir",
                        "value" => array(
                            "Descending" => "desc",
                            "Ascending" => "asc"
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Number column",
                        "param_name" => "number_column",
                        "value" => array(
                            "1" => "1",
                            "2" => "2",
                            "3" => "3",
                            "4" => "4"
                        ),
                        "dependency" => array('element' => 'style', 'value' => array('grid'))
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Height item",
                        "param_name" => "height_item",
                        "value" => "430",
                        "dependency" => array('element' => 'style', 'value' => 'grid_v2')
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Number of description text",
                        "param_name" => "desc_text_limit",
                        "value" => '15'
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop", "inwavethemes"),
                        "param_name" => "item_desktop",
                        "value" => '3',
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider", "carousel_slider_v2", "carousel_slider_v3")),
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop Small", "inwavethemes"),
                        "param_name" => "item_desktop_small",
                        "value" => '2',
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider", "carousel_slider_v2", "carousel_slider_v3")),
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => "Auto Play Slider",
                        "param_name" => "auto_play",
                        "value" => array(
                            "No" => "2",
                            "Yes" => "1"
                        ),
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider", "carousel_slider_v2", "carousel_slider_v3", "slick_slider")),
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
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider", "carousel_slider_v2", "carousel_slider_v3", "slick_slider")),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Link view all", "inwavethemes"),
                        "param_name" => "link_all",
                        "value" => "",
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider", "carousel_slider_v2", "carousel_slider_v3", "slick_slider")),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Link all text", "inwavethemes"),
                        "param_name" => "link_all_text",
                        "value" => "",
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider", "carousel_slider_v2", "carousel_slider_v3", "slick_slider")),
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
				"order_by"=>"menu_order",
				"order_dir"=>"DESC",
				'link_all' => '',
				'link_all_text' => '',
                "style" => 'grid',
                "number_column" => '3',
                "desc_text_limit" => '',
                "item_desktop" => "",
                "item_desktop_small" => "",
                "auto_play" => "false",
                "style_navigation" => "",
				"height_item" => "",
				"css" => "",
                "class" => ""
            ), $atts));

	
            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }
			
			 $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);

            ob_start();
            echo do_shortcode('[inmedical_departments order_by="'.$order_by.'" order_dir="'.$order_dir.'" style="'.$style.'" number_column="' . $number_column . '" desc_text_limit="' . $desc_text_limit . '" item_desktop="'.$item_desktop.'" item_desktop_small="'.$item_desktop_small.'" auto_play="'.$auto_play.'" style_navigation="'.$style_navigation.'" height_item="' . $height_item . '" css="' . $css . '" class="' . $class . '" link_all="'.$link_all.'" link_all_text="'.$link_all_text.'"]');
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Departments();
