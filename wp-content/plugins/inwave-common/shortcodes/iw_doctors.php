<?php

/*
 * Inwave_Doctor_Listing for Visual Composer
 */
if (!class_exists('Inwave_Doctors')) {

    class Inwave_Doctors extends Inwave_Shortcode{

        protected $name = 'iw_doctors';

        function getIwDepartments() {
            global $wpdb;
            $departments = $wpdb->get_results('SELECT ID, post_name, post_title FROM ' . $wpdb->prefix . 'posts WHERE post_status="publish" AND post_type="indepartment"');
            $newDepartments = array();
            $newDepartments[__("All", "inwavethemes")] = '';
            foreach ($departments as $department) {
                $newDepartments[$department->post_title] = $department->ID;
            }
            return $newDepartments;
        }

        function init_params() {

            return array(
                'name' => __('Doctors', 'inwavethemes'),
                'description' => __('Create a list of doctors', 'inwavethemes'),
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
                            "Style 2 - Grid style 2" => "grid_v2",
                            "Style 3 - Grid style 3" => "grid_v3",
                            "Style 4 - Carousel Slider" => "carousel_slider",
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
                        "type" => "dropdown",
                        "heading" => "Departments",
                        "admin_label" => true,
                        "param_name" => "departments",
                        "value" => $this->getIwDepartments()
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Doctor Ids", "inwavethemes"),
                        "param_name" => "ids",
                        "value" => ""
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Order By",
                        "param_name" => "order_by",
                        "value" => array(
                            "Ordering" => "menu_order",
                            "Date" => "date",
                            "Start Date" => "sdate",
                            "End Date" => "edate",
                            "Title" => "title",
                            "Doctor ID" => "ID",
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
                        "type" => "textfield",
                        "heading" => __("Number of doctor per page", "inwavethemes"),
                        "param_name" => "item_per_page",
                        "value" => 12,
						"dependency" => array('element' => 'style', 'value' => array("grid", "grid_v2", "grid_v3")),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Show Paging",
                        "param_name" => "show_paging",
                        "value" => array(
                            "Yes" => "1",
                            "No" => "0"
                        ),
						"dependency" => array('element' => 'style', 'value' => array("grid", "grid_v2", "grid_v3")),
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
						"dependency" => array('element' => 'style', 'value' => array("grid", "grid_v2", "grid_v3")),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Show filter",
						"admin_label" => true,
                        "param_name" => "show_filter",
                        "value" => array(
                            "No" => "0",
                            "Yes" => "1",
                        ),
						"dependency" => array('element' => 'style', 'value' => array("grid", "grid_v2", "grid_v3")),
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Number of description text",
                        "param_name" => "desc_text_limit",
                        "value" => '20'
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop", "inwavethemes"),
                        "param_name" => "item_desktop",
                        "value" => '3',
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider")),
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Carousel Options",
                        "heading" => __("Items Desktop Small", "inwavethemes"),
                        "param_name" => "item_desktop_small",
                        "value" => '2',
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider")),
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => "Auto Play Slider",
                        "param_name" => "auto_play",
                        "value" => array(
                            "No" => "false",
                            "Yes" => "true"
                        ),
                        "dependency" => array('element' => 'style', 'value' => array("carousel_slider")),
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Carousel Options",
                        "heading" => "Show Navigation",
                        "param_name" => "show_navigation",
                        "value" => array(
                            "No" => "false",
                            "Yes" => "true"
                        ),
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
                "departments" => '',
                "ids" => '',
                "order_by" => "date",
                "order_dir" => "desc",
                "item_per_page" => 12,
                "style" => 'grid',
                "show_paging" => '1',
                "number_column" => '3',
                "show_filter" => '0',
                "desc_text_limit" => '',
				"item_desktop" => "",
				"item_desktop_small" => "",
				"auto_play" => "false",
				"show_navigation" => "false",
				"css" => "",
                "class" => ""
                            ), $atts));

	
            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }
			
			 $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);
            $utility = new inMedicalUtility();
            if($style == 'carousel_slider'){
                $show_filter = 0;
                $show_paging = 0;
            }
            ob_start();
            echo do_shortcode('[inmedical_doctors departments="' . $departments . '" ids="'.$ids.'" order_by="' . $order_by . '" order_dir="' . $order_dir . '" item_per_page="' . $item_per_page . '" style="'.$style.'" show_paging="' . $show_paging . '" number_column="' . $number_column . '" show_filter="' . $show_filter . '" desc_text_limit="' . $desc_text_limit . '" item_desktop="'.$item_desktop.'" item_desktop_small="'.$item_desktop_small.'" auto_play="'.$auto_play.'" show_navigation="'.$show_navigation.'" class="' . $class . '"]');
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Doctors();
