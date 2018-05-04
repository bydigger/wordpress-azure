<?php

/*
 * Inwave_Doctor_Listing for Visual Composer
 */
if (!class_exists('Inwave_Doctor_Custom')) {

    class Inwave_Doctor_Custom extends Inwave_Shortcode{

        protected $name = 'iw_doctor_custom';

        function init_params() {

            return array(
                'name' => __('Doctor Custom', 'inwavethemes'),
                'description' => __('Add a item info doctor', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "type" => "attach_image",
                        "heading" => __("Doctor Image", "inwavethemes"),
                        "param_name" => "image",
                        "value" => "",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Doctor Name",
                        "param_name" => "doctor_name",
                        "value" => ''
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Link Doctor",
                        "param_name" => "link_doctor",
                        "value" => '#'
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Doctor department",
                        "param_name" => "doctor_department",
                        "value" => ''
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Link Department",
                        "param_name" => "link_department",
                        "value" => '#'
                    ),
                    array(
                        "type" => "textarea",
                        "heading" => __("Social links", "inwavethemes"),
                        "description" => __("Separated by newline", "inwavethemes"),
                        "param_name" => "social_links",
                        "value" => "http://facebook.com/
                                    http://twitter.com/
                                    http://plus.google.com/"
                    ),
                    array(
                        "type" => "colorpicker",
                        "heading" => __("Background Color", "inwavethemes"),
                        "param_name" => "background_color",
                        "description" => __('Background for item', "inwavethemes"),
                        "value" => "",
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Border item", "inwavethemes"),
                        "param_name" => "border_item",
                        "value" => array(
                            "No" => "No",
                            "Yes" => "Yes",
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Background title", "inwavethemes"),
                        "param_name" => "background_title",
                        "value" => array(
                            "Yes" => "Yes",
                            "No" => "No",
                        ),
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
                        'group' => __( 'Design Options', 'inwavethemes' )
                    )
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            global $wpdb;
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;
            extract(shortcode_atts(array(
                'image' => '',
                'doctor_name' => '',
                'link_doctor' => '',
                'doctor_department' => '',
                'link_department' => '',
                'social_links' => '',
                'background_color' => '',
                'border_item' => '',
                'background_title' => '',
                'class' => '',
                'css' => ''
            ), $atts));

            $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);

            $img_tag = '';
            if ($image) {
                $image = wp_get_attachment_image_src($image, 'large');
                $url_img = inwave_resize($image[0], 370, 255, array('center', 'top'));
                $img_tag .= '<img src="' . $url_img . '" alt="">';
            }

            $social_links = str_replace('<br />', "\n", $social_links);
            $social_links = explode("\n", $social_links);

            $background_style = '';
            if($background_color){
                $background_style = 'style="background-color: '.esc_attr($background_color).'"';
            }
            $border_style = '';
            if($border_item == 'Yes'){
                $border_style = 'style="border: 4px solid '.esc_attr($background_color).'"';
            }



            ob_start();
            ?>
                <div class="iw-doctor-custom <?php echo $class; ?>">
                    <div class="doctor-image-social">
                        <div class="image" <?php echo $border_style ? $border_style : '' ?>><?php echo $img_tag; ?></div>
                        <?php if($social_links) : ?>
                            <div class="social-link">
                                <ul>
                                <?php foreach ($social_links as $social_link) {
                                    $domain = explode(".com", $social_link);

                                    if ($social_link && isset($domain[0])) {

                                    $domain = str_replace(array('https://', 'http://'), '', $domain[0]);
                                    if ($domain == 'plus.google') {
                                    $domain = 'google-plus';
                                    }

                                    echo '<li><a href="' . $social_link . '"><i class="fa fa-' . $domain . '"></i></a></li>';
                                    }
                                } ?>
                                </ul>
                            </div>
                            <div class="background-overlay" <?php echo $background_style ? $background_style : '' ?>></div>
                        <?php  endif; ?>
                    </div>
                    <?php if($doctor_name || $doctor_department) : ?>
                        <div class="info <?php echo $background_title == "No" ? 'title-background-n' : '';?>" <?php echo $background_style ? $background_style : '' ?>>
                            <?php
                            if ($doctor_name){
                                if ($link_doctor){
                                    echo '<h3 class="doctor-name"><a href="'.$link_doctor.'">'.$doctor_name.'</a></h3>';
                                } else {
                                    echo '<h3 class="doctor-name">'.$doctor_name.'</h3>';
                                }
                            }
                            if ($doctor_department){
                                if ($link_department){
                                    echo '<div class="doctor-position"><a href="'.$link_department.'">'.$doctor_department.'</a></div>';
                                } else {
                                    echo '<div class="doctor-position">'.$doctor_department.'</div>';
                                }
                            } ?>
                        </div>
                    <?php  endif; ?>
                </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Doctor_Custom();
