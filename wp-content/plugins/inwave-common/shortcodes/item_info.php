<?php

/*
 * Inwave_Item_Info for Visual Composer
 */
if (!class_exists('Inwave_Item_Info')) {

    class Inwave_Item_Info extends Inwave_Shortcode{

        protected $name = 'inwave_item_info';

        function init_params() {
            $google_fonts = function_exists('inwave_get_googlefonts') ? inwave_get_googlefonts() : array();
            $font_weight = function_exists('inwave_get_fonts_weight') ? inwave_get_fonts_weight() : array();
            $text_transform = function_exists('inwave_get_text_transform') ? inwave_get_text_transform() : array();
            return array(
                'name' => __("Item Info", 'inwavethemes'),
                'description' => __('Add a item info', 'inwavethemes'),
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
                            "Style 1 - Icon, image left style1" => "style1_1",
                            "Style 2 - Icon, image left style2" => "style1_2",
                            "Style 3 - Icon, image left style3" => "style1_3",
                            "Style 4 - Icon, image left style4" => "style1_4",
                            "Style 5 - Icon, image left style5" => "style5",
                            "Style 6 - Icon, Image left style6" => "style1_5",
                            "Style 7 - Icon, image center title border bottom" => "style2",
							"Style 8 - Icon, image center, border item" => "style4",
                            "Style 9 - Background image" => "style3",
                            "Style 10 - Background image hover" => "style8",
                            "Style 11 - Image center, background image" => "style6",
                            "Style 12 - Icon, image left hover slide" => "style7",
                            "Style 13 - Border rounded icon" => "style9",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background color theme)", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-1.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style1_1')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background none)", "inwavethemes"),
                        "param_name" => "preview_style2",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-1-2.png',
                        "dependency" => array('element' => 'style', 'value' => 'style1_2')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background none)", "inwavethemes"),
                        "param_name" => "preview_style3",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-1-3.png',
                        "dependency" => array('element' => 'style', 'value' => 'style1_3')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style ( Icon - background none)", "inwavethemes"),
                        "param_name" => "preview_style4",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-1-4.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style1_4')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style5",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-2.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style2')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style6",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-3.png',
                        "dependency" => array('element' => 'style', 'value' => 'style3')
                    ),
					array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style7",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-style4.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style4')
                    ),
					array(
							"type" => "iwevent_preview_image",
							"heading" => __("Preview Style", "inwavethemes"),
							"param_name" => "preview_style8",
							"value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-style5.jpg',
							"dependency" => array('element' => 'style', 'value' => 'style5')
					),
					array(
							"type" => "iwevent_preview_image",
							"heading" => __("Preview Style", "inwavethemes"),
							"param_name" => "preview_style9",
							"value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-6.png',
							"dependency" => array('element' => 'style', 'value' => 'style6')
					),
					array(
							"type" => "iwevent_preview_image",
							"heading" => __("Preview Style", "inwavethemes"),
							"param_name" => "preview_style10",
							"value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-7.png',
							"dependency" => array('element' => 'style', 'value' => 'style1_5')
					),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style11",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-1-3.png',
                        "dependency" => array('element' => 'style', 'value' => 'style7')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style12",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/info-item-8.png',
                        "dependency" => array('element' => 'style', 'value' => 'style8')
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Border item", "inwavethemes"),
                        "param_name" => "border_item_v4",
                        "value" => array(
                            "Yes" => "item-border-v4",
                            "No" => ""
                        ),
                        "dependency" => array('element' => 'style', 'value' => 'style4')
                    ),
                    array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Title", "inwavethemes"),
                        "description" => __("You can add |TEXT_EXAMPLE| to specify strong words, <br />{TEXT_EXAMPLE} to specify colorful words, <br />'///' to insert line break tag (br)", "inwavethemes"),
                        "value" => "",
                        "param_name" => "title",
                    ),
					array(
                        'type' => 'textfield',
                        "admin_label" => true,
                        "heading" => __("Sub Title", "inwavethemes"),
                        "description" => __("You can add |TEXT_EXAMPLE| to specify strong words, <br />{TEXT_EXAMPLE} to specify colorful words, <br />'///' to insert line break tag (br)", "inwavethemes"),
                        "value" => "",
                        "param_name" => "sub_title",
						"dependency" => array(
							'element' => 'style', 
							'value' => array('style4','style7'),
						)
                    ),
                    array(
                        'type' => 'textarea',
                        "heading" => __("Description", "inwavethemes"),
                        "description" => __("You can add |TEXT_EXAMPLE| to specify strong words, <br />{TEXT_EXAMPLE} to specify colorful words, <br />'///' to insert line break tag (br)", "inwavethemes"),
                        "value" => "",
                        "param_name" => "description",
                    ),
                    array(
                        'type' => 'textarea_raw_html',
                        /*"holder" => "div",*/
                        "heading" => __("Sub description", "inwavethemes"),
                        "value" => "",
                        "param_name" => "sub_description",
                        "dependency" => array(
							'element' => 'style',
							'value' => array('style2', 'style9'),
						)
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Link Read More", "inwavethemes"),
                        "value" => "#",
                        "param_name" => "link",
                    ),
					array(
                        'type' => 'textfield',
                        "heading" => __("Read more text", "inwavethemes"),
                        "value" => "Read more",
                        "param_name" => "readmore_text",
						 "dependency" => array(
							'element' => 'style', 
							'value' => array('style1_2', 'style3', 'style4', 'style6', 'style8'),
						)
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Icon Image", "inwavethemes"),
                        "param_name" => "img",
                        "description" => __("Icon Image", "inwavethemes"),
                        "dependency" => array(
                            'element' => 'style',
                            'value' => array('style1_1', 'style1_2', 'style1_3', 'style1_4', 'style2', 'style4', 'style5', 'style1_5', 'style6', 'style7', 'style8'),
                        )
                    ),
                    array(
                        'type' => 'attach_image',
                        "heading" => __("Background Image", "inwavethemes"),
                        "param_name" => "bg_img",
                        "dependency" => array(
                            'element' => 'style',
                            'value' => array('style3', 'style6','style8'),
                        )
                    ),
                    array(
                        'type' => 'iw_icon',
                        "heading" => __("Icon", "inwavethemes"),
                        "value" => "",
						"description" => __("Click and select icon of your choice. You can get complete list of available icons here: <a target='_blank' href='http://fortawesome.github.io/Font-Awesome/icons/'>Font-Awesome</a>", "inwavethemes"),
                        "param_name" => "icon",
                        "dependency" => array(
                            'element' => 'style',
                            'value' => array('style1_1', 'style1_2', 'style1_3', 'style1_4', 'style2', 'style4', 'style5', 'style1_5', 'style7', 'style8', 'style9'),
                        )
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Icon Size", "inwavethemes"),
                        "param_name" => "icon_size",
						"description" => __("Example: 70. Use for image width or font size icon", "inwavethemes"),
                        "value" => "",
                        "dependency" => array(
                            'element' => 'style',
                            'value' => array('style1_1', 'style1_2', 'style1_3', 'style1_4', 'style2', 'style4', 'style5', 'style1_5', 'style7', 'style8'),
                        )
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Icon align", "inwavethemes"),
                        "param_name" => "icon_align",
                        "value" => array(
                            "left" => "left",
                            "right" => "right"
                        ),
                        "dependency" => array(
                            'element' => 'style',
                            'value' => array('style1_5'),
                        )
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
                    //title style
                    array(
                        "type" => "colorpicker",
                        "heading" => __("Title Color", "inwavethemes"),
                        "group" => "Title Style",
                        "param_name" => "color_title",
                        "description" => __('Color for Title', "inwavethemes"),
                        "value" => "",
                    ),
                    array(
                        'type' => 'textfield',
                        "group" => "Title Style",
                        "heading" => __("Title Font Size", "inwavethemes"),
                        "value" => "",
                        "description" => __('Custom font-size title. Example: 30px', "inwavethemes"),
                        "param_name" => "font_size_title",
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Title Font Family", "inwavethemes"),
                        "group" => "Title Style",
                        "param_name" => "font_family_title",
                        "description" => __('Font family of Title', "inwavethemes"),
                        "value" => $google_fonts,
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Title Style",
                        "heading" => __("Load Font Family from google", "inwavethemes"),
                        "param_name" => "load_font_title",
                        "value" => array(
                            __('No', "inwavethemes") => '',
                            __('Yes', "inwavethemes") => '1',
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Title Style",
                        "heading" => __("Title Font Weight", "inwavethemes"),
                        "param_name" => "font_weight_title",
                        "description" => __('Font weight of Title', "inwavethemes"),
                        "value" => $font_weight,
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Title Style",
                        "heading" => __("Title Text Transform", "inwavethemes"),
                        "param_name" => "text_transform_title",
                        "description" => __('Text Transform of Title', "inwavethemes"),
                        "value" => $text_transform,
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Title Style",
                        "heading" => __("Title Line Height", "inwavethemes"),
                        "param_name" => "line_height_title",
                        "description" => __('Line height of Title. Example: 30px or 1', "inwavethemes"),
                        "value" => "",
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Title Style",
                        "heading" => __("Title Margin-bottom", "inwavethemes"),
                        "param_name" => "margin_bottom_title",
                        "description" => __('Margin bottom of Title', "inwavethemes"),
                        "value" => '',
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Title Style",
                        "heading" => __("Title Letter Spacing", "inwavethemes"),
                        "param_name" => "margin_letter_spacing",
                        "description" => __('Letter spacing of Title', "inwavethemes"),
                        "value" => '',
                    ),

                    //subtitle style
                    array(
                        "type" => "colorpicker",
                        "heading" => __("Sub Title Color", "inwavethemes"),
                        "group" => "Sub Title Style",
                        "param_name" => "color_sub_title",
                        "description" => __('Color for Sub Title', "inwavethemes"),
                        "value" => "",
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),
                    array(
                        'type' => 'textfield',
                        "group" => "Sub Title Style",
                        "heading" => __(" Sub Title Font Size", "inwavethemes"),
                        "value" => "",
                        "description" => __('Custom font-size title. Example: 30px', "inwavethemes"),
                        "param_name" => "font_size_sub_title",
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Sub Title Font Family", "inwavethemes"),
                        "group" => "Sub Title Style",
                        "param_name" => "font_family_sub_title",
                        "description" => __('Font family of Sub Title', "inwavethemes"),
                        "value" => $google_fonts,
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Sub Title Style",
                        "heading" => __("Load Font Family from google", "inwavethemes"),
                        "param_name" => "load_font_sub_title",
                        "value" => array(
                            __('No', "inwavethemes") => '',
                            __('Yes', "inwavethemes") => '1',
                        ),
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Sub Title Style",
                        "heading" => __("Sub Title Font Weight", "inwavethemes"),
                        "param_name" => "font_weight_sub_title",
                        "description" => __('Font weight of Sub Title', "inwavethemes"),
                        "value" => $font_weight,
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Sub Title Style",
                        "heading" => __("Sub Title Text Transform", "inwavethemes"),
                        "param_name" => "text_transform_sub_title",
                        "description" => __('Text Transform of Sub Title', "inwavethemes"),
                        "value" => $text_transform,
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Sub Title Style",
                        "heading" => __("Sub Title Line Height", "inwavethemes"),
                        "param_name" => "line_height_sub_title",
                        "description" => __('Line height of Sub Title', "inwavethemes"),
                        "value" => "",
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Sub Title Style",
                        "heading" => __("Margin bottom", "inwavethemes"),
                        "param_name" => "margin_bottom_sub_title",
                        "description" => __('Margin bottom of Sub Title. Example: 30px', "inwavethemes"),
                        "value" => "",
                        "dependency" => array('element' => 'style', 'value' => array('style1', 'style4', 'style5', 'style6'))
                    ),

                    //Description style
                    array(
                        "type" => "colorpicker",
                        "heading" => __("Description Color", "inwavethemes"),
                        "group" => "Description Style",
                        "param_name" => "color_description",
                        "description" => __('Color for Description', "inwavethemes"),
                        "value" => "",
                    ),
                    array(
                        'type' => 'textfield',
                        "group" => "Description Style",
                        "heading" => __(" Description Font Size", "inwavethemes"),
                        "value" => "",
                        "description" => __('Custom font-size Description. Example: 30px', "inwavethemes"),
                        "param_name" => "font_size_description",
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Description Font Family", "inwavethemes"),
                        "group" => "Description Style",
                        "param_name" => "font_family_description",
                        "description" => __('Font family of Description', "inwavethemes"),
                        "value" => $google_fonts,
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Description Style",
                        "heading" => __("Load Font Family from google", "inwavethemes"),
                        "param_name" => "load_font_description",
                        "value" => array(
                            __('No', "inwavethemes") => '',
                            __('Yes', "inwavethemes") => '1',
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Description Style",
                        "heading" => __("Description Font Weight", "inwavethemes"),
                        "param_name" => "font_weight_description",
                        "description" => __('Font weight of Description', "inwavethemes"),
                        "value" => $font_weight,
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Description Style",
                        "heading" => __("Description Text Transform", "inwavethemes"),
                        "param_name" => "text_transform_description",
                        "description" => __('Text Transform of Description', "inwavethemes"),
                        "value" => $text_transform,
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Description Style",
                        "heading" => __("Description Line Height", "inwavethemes"),
                        "param_name" => "line_height_description",
                        "description" => __('Line height of description', "inwavethemes"),
                        "value" => "",
                    ),
                    array(
                        "type" => "textfield",
                        "group" => "Description Style",
                        "heading" => __("Margin top", "inwavethemes"),
                        "param_name" => "margin_top_description",
                        "description" => __('Margin bottom of Description. Example: 30px', "inwavethemes"),
                        "value" => "",
                    ),

                    //Read more style
                    array(
                        "type" => "colorpicker",
                        "heading" => __("Read More Color", "inwavethemes"),
                        "group" => "Read More Style",
                        "param_name" => "color_read_more",
                        "description" => __('Color for Read More', "inwavethemes"),
                        "value" => "",
                    ),
                    array(
                        'type' => 'textfield',
                        "group" => "Read More Style",
                        "heading" => __(" Read More Font Size", "inwavethemes"),
                        "value" => "",
                        "description" => __('Custom font-size Read More. Example: 14px', "inwavethemes"),
                        "param_name" => "font_size_read_more",
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Read More Font Family", "inwavethemes"),
                        "group" => "Read More Style",
                        "param_name" => "font_family_read_more",
                        "description" => __('Font family of Read More', "inwavethemes"),
                        "value" => $google_fonts,
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Read More Style",
                        "heading" => __("Load Font Family from google", "inwavethemes"),
                        "param_name" => "load_font_read_more",
                        "value" => array(
                            __('No', "inwavethemes") => '',
                            __('Yes', "inwavethemes") => '1',
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "group" => "Read More Style",
                        "heading" => __("Read More Font Weight", "inwavethemes"),
                        "param_name" => "font_weight_read_more",
                        "description" => __('Font weight of Read More', "inwavethemes"),
                        "value" => $font_weight,
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

            $output = $style = $class = $title = $sub_title = $link = $img = $bg_img = $icon = $icon_size = $bg_icon = $bg_item = $icon_align = $align = $description = $css = $item_height = $readmore_text = $border_item = $border_item_v4 = '';
            extract(shortcode_atts(array(
                'style' => '',
                'title' => '',
                'sub_title' => '',
                'link' => '',
				'item_height' => '',
                'img' => '',
                'bg_img' => '',
                'icon' => '',
				'border_item' => '',
				'border_item_v4' => '',
                'bg_item' => '',
                'bg_icon' => '',
                'border_icon' => '',
                'icon_size' => '',
                'icon_align' => '',
				'readmore_text' => '',
                'align' => '',
                'description' => '',
                'sub_description' => '',
                'css' => '',
                'color_title' => '',
                'font_size_title' => '',
                'font_family_title' => '',
                'load_font_title' => '',
                'font_weight_title' => '',
                'text_transform_title' => '',
                'line_height_title' => '',
                'margin_bottom_title' => '',
                'margin_letter_spacing' => '',

                'color_sub_title' => '',
                'font_size_sub_title' => '',
                'font_family_sub_title' => '',
                'load_font_sub_title' => '',
                'font_weight_sub_title' => '',
                'text_transform_sub_title' => '',
                'line_height_sub_title' => '',
                'margin_bottom_sub_title' => '',

                'color_description' => '',
                'font_size_description' => '',
                'font_family_description' => '',
                'load_font_description' => '',
                'font_weight_description' => '',
                'text_transform_description' => '',
                'line_height_description' => '',
                'margin_top_description' => '',

                'color_read_more' => '',
                'font_size_read_more' => '',
                'font_family_read_more' => '',
                'load_font_read_more' => '',
                'font_weight_read_more' => '',

                'class' => '',
            ), $atts));

            $class .= ' '.$style.' '. vc_shortcode_custom_css_class( $css);
            if($align){
                $class.= ' '.$align.'-text';
            }
            if($border_item_v4){
                $class.= ' '.$border_item_v4;
            }
            $img_tag = '';
            $bg_image_tag = '';
            if ($img) {
                $img = wp_get_attachment_image_src($img, 'large');
                $img = $img[0];
                $img_tag .= '<img src="' . $img . '" alt="' . $title . '">';
                $bg_image_tag.= 'background-image: url('.$img .')';
            }
            $bg_image = '';
            $img_tag_hover = '';
            if ($bg_img) {
                $bg_img = wp_get_attachment_image_src($bg_img, 'large');
                $bg_img = $bg_img[0];
                $bg_image.= 'background-image: url('.$bg_img .')';
                $img_tag_hover .= '<img src="' . $bg_img . '" alt="' . $title . '">';
            }

            $font_size = '';
            $size = '';
            if ($icon_size) {
                $size = 'style="width:' . $icon_size . 'px!important;"';
                $font_size = 'style="font-size:' . $icon_size . 'px"';
            }

            //title
            $title_style = array();
            if($color_title){
                $title_style[] = 'color: '.esc_attr($color_title);
            }
            if($font_size_title){
                $title_style[] = 'font-size: '.esc_attr($font_size_title);
            }
            if($font_family_title){
                if($load_font_title && !isset(Inwave_Shortcode::$loadfonts[$font_family_title.$font_weight_title])){
                    $font_url = "http" . ((is_ssl()) ? 's' : '') . "://fonts.googleapis.com/css?family={$font_family_title}";
                    wp_enqueue_style('google-font-'.strtolower(str_replace(" ", "-", $font_family_title.$font_weight_title)), $font_url);
                    Inwave_Shortcode::$loadfonts[$font_family_title.$font_weight_title] = true;
                }
                $title_style[] = 'font-family: '.esc_attr($font_family_title);
            }
            if($font_weight_title){
                $title_style[] = 'font-weight: '.esc_attr($font_weight_title);
            }
            if($text_transform_title){
                $title_style[] = 'text-transform: '.esc_attr($text_transform_title);
            }
            if($line_height_title){
                $title_style[] = 'line-height: '.esc_attr($line_height_title);
            }
            if($margin_bottom_title || $margin_bottom_title != 0){
                $title_style[] = 'margin-bottom: '.esc_attr($margin_bottom_title);
            }
            if($margin_letter_spacing){
                $title_style[] = 'letter-spacing: '.esc_attr($margin_letter_spacing);
            }

            //subtitle
            $sub_title_style = array();
            if($color_sub_title){
                $sub_title_style[] = 'color: '.esc_attr($color_sub_title);
            }
            if($font_size_sub_title){
                $sub_title_style[] = 'font-size: '.esc_attr($font_size_sub_title);
            }
            if($font_family_sub_title){
                if($load_font_sub_title && !isset(Inwave_Shortcode::$loadfonts[$font_family_sub_title.$font_weight_sub_title])){
                    $font_url = "http" . ((is_ssl()) ? 's' : '') . "://fonts.googleapis.com/css?family={$font_family_sub_title}";
                    wp_enqueue_style('google-font-'.strtolower(str_replace(" ", "-", $font_family_sub_title.$font_weight_sub_title)), $font_url);
                    Inwave_Shortcode::$loadfonts[$font_family_sub_title.$font_weight_sub_title] = true;
                }
                $sub_title_style[] = 'font-family: '.esc_attr($font_family_sub_title);
            }
            if($font_weight_sub_title){
                $sub_title_style[] = 'font-weight: '.esc_attr($font_weight_sub_title);
            }
            if($text_transform_sub_title){
                $sub_title_style[] = 'text-transform: '.esc_attr($text_transform_sub_title);
            }
            if($line_height_sub_title){
                $sub_title_style[] = 'line-height: '.esc_attr($line_height_sub_title);
            }
            if($margin_bottom_sub_title || $margin_bottom_sub_title != 0){
                $sub_title_style[] = 'margin-bottom: '.esc_attr($margin_bottom_sub_title);
            }

            //description
            $description_style = array();
            if($color_description){
                $description_style[] = 'color: '.esc_attr($color_description);
            }
            if($font_size_description){
                $description_style[] = 'font-size: '.esc_attr($font_size_description);
            }
            if($font_family_description){
                if($load_font_description && !isset(Inwave_Shortcode::$loadfonts[$font_family_description.$font_weight_description])){
                    $font_url = "http" . ((is_ssl()) ? 's' : '') . "://fonts.googleapis.com/css?family={$font_family_description}";
                    wp_enqueue_style('google-font-'.strtolower(str_replace(" ", "-", $font_family_description.$font_weight_description)), $font_url);
                    Inwave_Shortcode::$loadfonts[$font_family_description.$font_weight_description] = true;
                }
                $description_style[] = 'font-family: '.esc_attr($font_family_description);
            }
            if($font_weight_description){
                $description_style[] = 'font-weight: '.esc_attr($font_weight_description);
            }
            if($text_transform_description){
                $description_style[] = 'text-transform: '.esc_attr($text_transform_description);
            }
            if($line_height_description){
                $description_style[] = 'line-height: '.esc_attr($line_height_description);
            }
            if($margin_top_description){
                $description_style[] = 'margin-top: '.esc_attr($margin_top_description);
            }

            //read more
            $read_more_style = array();
            if($color_read_more){
                $read_more_style[] = 'color: '.esc_attr($color_read_more);
            }
            if($font_size_read_more){
                $read_more_style[] = 'font-size: '.esc_attr($font_size_read_more);
            }
            if($font_family_read_more){
                if($load_font_read_more && !isset(Inwave_Shortcode::$loadfonts[$font_family_read_more.$font_weight_read_more])){
                    $font_url = "http" . ((is_ssl()) ? 's' : '') . "://fonts.googleapis.com/css?family={$font_family_read_more}";
                    wp_enqueue_style('google-font-'.strtolower(str_replace(" ", "-", $font_family_read_more.$font_weight_read_more)), $font_url);
                    Inwave_Shortcode::$loadfonts[$font_family_read_more.$font_weight_read_more] = true;
                }
                $read_more_style[] = 'font-family: '.esc_attr($font_family_read_more);
            }
            if($font_weight_read_more){
                $read_more_style[] = 'font-weight: '.esc_attr($font_weight_read_more);
            }

            $title= preg_replace('/\|(.*)\|/isU','<strong>$1</strong>',$title);
            $title= preg_replace('/\{(.*)\}/isU','<span class="theme-color">$1</span>',$title);
            $title= preg_replace('/\/\/\//i', '<br />', $title);

            $sub_title= preg_replace('/\|(.*)\|/isU','<strong>$1</strong>',$sub_title);
            $sub_title= preg_replace('/\{(.*)\}/isU','<span class="theme-color">$1</span>',$sub_title);
            $sub_title= preg_replace('/\/\/\//i', '<br />', $sub_title);

            $description= preg_replace('/\|(.*)\|/isU','<strong>$1</strong>',$description);
            $description= preg_replace('/\{(.*)\}/isU','<span class="theme-color">$1</span>',$description);
            $description= preg_replace('/\/\/\//i', '<br />', $description);

            switch ($style) {
                // Normal style
                case 'style1_1':
                case 'style1_3':
                case 'style1_4':
                    $output .= '<div class="iw-item-info style1 ' . $class . '">';
                    $output .= '<div class="info-wrap">';
                    if($img_tag){
                        $output .= '<div class="icon-img" '.$size.'>'.$img_tag.'</div>';
                    }elseif ($icon){
                        $output .= '<div class="icon-img icon" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
                    }
                    $output .= '<div class="item-info-content">';
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a class="theme-color-hover" href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($description){
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    break;

                case 'style1_2':
                    $output .= '<div class="iw-item-info style1 ' . $class . '">';
                    $output .= '<div class="info-wrap">';
                    if($img_tag){
                        $output .= '<div class="icon-img" '.$size.'>'.$img_tag.'</div>';
                    }elseif ($icon){
                        $output .= '<div class="icon-img icon" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
                    }
                    $output .= '<div class="item-info-content">';
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a class="theme-color-hover" href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($description){
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                    }
                    if ($readmore_text && $link){
                        $output .= '<a class="item-readmore" style="'.implode("; ",$read_more_style).'" href="'.esc_url($link).'" >'.$readmore_text.'<i class="ion-chevron-right"></i></a>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    break;

                case 'style5':
                    $output .= '<div class="iw-item-info ' . $class . '">';
                    if($img_tag){
                        $output .= '<div class="tag-img"><div class="img" '.$size.'>'.$img_tag.'</div></div>';
                    }
                    $output .= '<div class="item-info-content">';
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($description){
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    break;

                case 'style1_5':
                    $output .= '<div class="iw-item-info style1 ' . $class . '">';
                    $output .= '<div class="info-wrap">';
                    if($img_tag){
                        $output .= '<div class="icon-img '.(($icon_align == 'right') ? 'right-icon-img' : '').'" '.$size.'>'.$img_tag.'</div>';
                    }elseif ($icon){
                        $output .= '<div class="icon-img icon '.(($icon_align == 'right') ? 'right-icon-img' : '').'" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
                    }
                    $output .= '<div class="item-info-content">';
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($description){
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    break;

                case 'style2':
                    $output .= '<div class="iw-item-info ' . $class . '">';
                    if($img_tag){
                        $output .= '<div class="icon-img"><div class="img" '.$size.'>'.$img_tag.'</div></div>';
                    }elseif ($icon){
                        $output .= '<div class="icon-img icon theme-color" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
                    }
                    $output .= '<div class="item-info-content">';
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a class="theme-color-hover" href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($description){
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                    }
                    if ($sub_description){
                        $output .= '<div class="content">'. rawurldecode( base64_decode( $sub_description ) ) .'</div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                break;

                case 'style3':
                    $output .= '<div class="iw-item-info ' . $class . '">';
                    $output .= '<div class="item-info-content" style="' .$bg_image. '">';
                    if ($title){
                        $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                    }
                    if ($description){
                        $output .= '<div class="content-desc">';
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                        if ($readmore_text && $link){
                            $output .= '<a class="item-readmore" style="'.implode("; ",$read_more_style).'" href="'.esc_url($link).'" >'.$readmore_text.'<i class="ion-chevron-right"></i></a>';
                        }
                        $output .= '</div>';
                    }

                    $output .= '</div>';
                    $output .= '</div>';
                    break;
				
				case 'style4':
					$output .= '<div class="iw-item-info ' . $class . '">';
					$output .= '<div class="item-info-content">';
						if($img_tag){
							$output .= '<div class="icon-img">'.$img_tag.'</div>';
						} elseif ($icon){
							$output .= '<div class="icon-img" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
						}
						if ($title){
							if ($link){
								$output .= '<h3 class="title"><a class="theme-color-hover" href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
							} else {
								$output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
							}
						}
						if ($sub_title){
							$output .= '<div class="sub-title" style="'.implode("; ",$sub_title_style).'">'.$sub_title.'</div>';
						}
						if ($description){
							$output .= '<div class="content-desc">';
							$output .= '<p class="description">'.$description.'</p>';
							if ($readmore_text && $link){
								$output .= '<a class="item-readmore" style="'.implode("; ",$read_more_style).'" href="'.esc_url($link).'" >'.$readmore_text.'<i class="icon ion-arrow-right-c"></i></a>';
							}
							$output .= '</div>';
						}
					
					$output .= '</div>';
					$output .= '</div>';
				break;

				case 'style6':
					$output .= '<div class="iw-item-info ' . $class . '">';
					$output .= '<div class="item-info-content">';
						if($img_tag){
							$output .= '<div class="image-wrap" style="' .$bg_image. '"><div class="img"><div class="bg-image" style="'.$bg_image_tag.'"></div></div></div>';
						}
						if ($title){
							if ($link){
								$output .= '<h3 class="title"><a class="theme-color-hover" href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
							} else {
								$output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
							}
						}
						if ($description){
							$output .= '<div class="content-desc">';
							$output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
							$output .= '</div>';
						}
                        if ($readmore_text && $link){
                            $output .= '<a class="item-readmore" style="'.implode("; ",$read_more_style).'" href="'.esc_url($link).'" >'.$readmore_text.'</a>';
                        }

					$output .= '</div>';
					$output .= '</div>';
				break;

                case 'style7':
                    $output .= '<div class="iw-item-info style1 ' . $class . '">';
                    $output .= '<div class="info-wrap">';
                    $output .= '<div class="info-wraps">';
                    $output .= '<div class="info-content-top">';
                    $output .= '<div class="info-content-top-img">';
                    if($img_tag){
                        $output .= '<div class="icon-img" '.$size.'>'.$img_tag.'</div>';
                    }elseif ($icon){
                        $output .= '<div class="icon-img icon" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="info-content-top-title">';
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a class="theme-color-hover" href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($sub_title){
                        $output .= '<div class="sub-title" style="'.implode("; ",$sub_title_style).'">'.$sub_title.'</div>';
                    }
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="info-content-bottom">';
                    $output .= '<div class="item-info-content">';

                    if ($description){
                        $output .= '<div class="content-desc">';
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                        if ($readmore_text && $link){
                            $output .= '<a class="item-readmore" style="'.implode("; ",$read_more_style).'" href="'.esc_url($link).'" >'.$readmore_text.'<i class="icon ion-arrow-right-c"></i></a>';
                        }
                        $output .= '</div>';
                    }
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    break;

                case 'style8':
                    $output .= '<div class="iw-item-info ' . $class . '">';
                    $output .= '<div class="item-info-content">';
                    if($img_tag){
                        $output .= '<div class="image-wrap" style="'.$bg_image_tag.'">';
                        if($img_tag_hover){
                            $output .= '<div class="img-hover">'.$img_tag_hover.'</div>';
                        }elseif ($icon){
                            $output .= '<div class="img-hover icon" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
                        }
                        $output .= '</div>';
                    }
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($description){
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                    }
                    if ($readmore_text && $link){
                        $output .= '<a class="item-readmore" style="'.implode("; ",$read_more_style).'" href="'.esc_url($link).'" >'.$readmore_text.'</a>';
                    }

                    $output .= '</div>';
                    $output .= '</div>';
                    break;

                case 'style9':
                    $output .= '<div class="iw-item-info ' . $class . '">';
                    if ($icon){
                        $output .= '<div class="icon-img icon" '.$font_size.'><i class="'.esc_attr($icon).'"></i></div>';
                    }
                    $output .= '<div class="item-info-content">';
                    if ($title){
                        if ($link){
                            $output .= '<h3 class="title"><a class="theme-color-hover" href="'.esc_url($link).'" style="'.implode("; ",$title_style).'">'.$title.'</a></h3>';
                        } else {
                            $output .= '<h3 class="title" style="'.implode("; ",$title_style).'">'.$title.'</h3>';
                        }
                    }
                    if ($description){
                        $output .= '<p class="description" style="'.implode("; ",$description_style).'">'.$description.'</p>';
                    }
                    if ($sub_description){
                        $output .= '<div class="content">'. rawurldecode( base64_decode( $sub_description ) ) .'</div>';
                    }
                    $output .= '</div>';
                    $output .= '<div class="clearfix"></div>';
                    $output .= '</div>';
                    break;
            }
            return $output;
        }
    }
}

new Inwave_Item_Info;
