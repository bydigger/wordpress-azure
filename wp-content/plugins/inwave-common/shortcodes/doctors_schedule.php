<?php
/*
 * @package Inwave Athlete
 * @version 1.0.0
 * @created Mar 27, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of wp_posts
 *
 * @Developer duongca
 */
if (!class_exists('Inwave_Doctors_Schedule')) {

    class Inwave_Doctors_Schedule extends Inwave_Shortcode{

        protected $name = 'inwave_doctors_schedule';

        function init_params() {
            
            return array(
                'name' => __('Doctors schedule work', 'inwavethemes'),
                'description' => __('Doctors schedule work', 'inwavethemes'),
                'base' => $this->name,
                'icon' => 'iw-default',
                'category' => 'Custom',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "admin_label" => true,
                        "heading" => __("Style", "inwavethemes"),
                        "param_name" => "style",
                        "value" => array(
                            'Style 1 - jcarousel' => 'style1',
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/doctors-schedule-style1.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style1'),
                    ),
					array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Title", "inwavethemes"),
                        "description" => __("You can add: <br /> |TEXT_EXAMPLE| to specify strong words, <br />{TEXT_EXAMPLE} to specify colorful words", "inwavethemes"),
                        "value" => "",
                        "param_name" => "title_block",
                    ),
					
					array(
                        "type" => "textfield",
                        "heading" => __("Link view all schedules", "inwavethemes"),
                        "param_name" => "view_all_schedule",
						"value" => "",
                    ),
					array(
                        "type" => "textfield",
                        "heading" => __("Number of row item", "inwavethemes"),
                        "param_name" => "number_of_row",
						"value" => "4",
                     //   "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
					array(
                        'type' => 'checkbox',
                        'heading' => __( 'Show navigation', 'js_composer' ),
                        'param_name' => 'show_navigation',
						"description" => __('When your items more than number of row, this list display as slider. You can show or hide navigation of slider', "inwavethemes"),
                    ),
					array(
                        "type" => "textfield",
                        "heading" => __("Max item show", "inwavethemes"),
                        "param_name" => "max_item_show",
						"value" => "",
                     //   "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;
            $output = $title_block = $style = $class = '';
            extract(shortcode_atts(array(
                'title_block' => '',
				'style' => '',
                'class' => '',
                'number_of_row' => '',
                'view_all_schedule' => '',
                'max_item_show' => '',
                'show_navigation' => '',
            
			), $atts));

        
            $class .= ' '. $style;
			$title_block= preg_replace('/\|(.*)\|/isU','<strong>$1</strong>',$title_block);
            $title_block= preg_replace('/\{(.*)\}/isU','<span class="theme-color">$1</span>',$title_block);
			
			if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }

            ob_start();

            $path = inMedicalGetTemplatePath('inmedical/doctors_schedule_'.$style);
            if ($path) {
                include $path;
            } else {
                $imd_theme = INMEDICAL_THEME_PATH . 'doctors_schedule_'.$style.'.php';
                if (file_exists($imd_theme)) {
                    include $imd_theme;
                } else {
                    echo __('No theme was found', 'inwavethemes');
                }
            }

            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Doctors_Schedule();