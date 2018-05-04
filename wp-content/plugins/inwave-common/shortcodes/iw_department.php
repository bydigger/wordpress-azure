<?php

/*
 * Inwave_Department_Listing for Visual Composer
 */
if (!class_exists('Inwave_Department')) {

    class Inwave_Department extends Inwave_Shortcode{

        protected $name = 'iw_department';

        function getIwDepartments() {
            global $wpdb;
            $departments = $wpdb->get_results('SELECT post_name, post_title FROM ' . $wpdb->prefix . 'posts WHERE post_status="publish" AND post_type="indepartment"');
            $newDepartments = array();
            $newDepartments[__("Select department", "inwavethemes")] = '0';
            foreach ($departments as $department) {
                $newDepartments[$department->post_title] = $department->post_name;
            }
            return $newDepartments;
        }

        function init_params() {

            return array(
                'name' => __('Department', 'inwavethemes'),
                'description' => __('Show a single of department', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "heading" => "Select department",
                        "admin_label" => true,
                        "param_name" => "select_department",
                        "value" => $this->getIwDepartments(),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Style",
                        "admin_label" => true,
                        "param_name" => "style",
                        "value" => array(
                            "square" => "square",
                            "vertical" => "vertical",
                            "horizontal" => "horizontal",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/department-square.png',
                        "dependency" => array('element' => 'style', 'value' => 'square')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style2",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/department-vertical.png',
                        "dependency" => array('element' => 'style', 'value' => 'vertical')
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style3",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/department-horizontal.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'horizontal')
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Readmore link",
                        "param_name" => "readmore_link",
                        "value" => array(
                            "Yes" => "Yes",
                            "No" => "No"
                        ),
                    ),
                    array(
                        "type" => "attach_image",
                        "heading" => __("Department Image", "inwavethemes"),
                        "param_name" => "image",
                        "value" => "",
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
                'select_department' => '',
                'style' => 'square',
                'readmore_link' => 'Yes',
                'image' => '',
                'class' => '',
                'css' => ''
            ), $atts));
            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }
            $id = $wpdb->get_var($wpdb->prepare('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_name=%s', $select_department));
            $class .= ' '.$class.' '. vc_shortcode_custom_css_class($css);
            ob_start();
            ?>
            <div class="single-department <?php echo $class; ?> ">
                <?php
                $indepartment = new inMediacalDepartment();
                $department = $indepartment->getDepartmentInformation($id);
                $img_tag = '';
                if ($image) {
                    $image = wp_get_attachment_image_src($image, 'large');
                }else{
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($department->id), 'full');
                }
                if($style == 'vertical'){
                    $url_img = inwave_resize($image[0], 270, 570, true);
                }elseif($style == 'horizontal'){
                    $url_img = inwave_resize($image[0], 570, 270, true);
                }else{
                    $url_img = inwave_resize($image[0], 570, 570, true);
                }
                $img_tag .= '<img src="' . esc_url($url_img) . '" alt="">';
                ?>
                <div class="single-department-solid">
                <div class="single-department-img effect-1"><?php echo $img_tag; ?></div>
                <div class="single-department-info">
                    <div class="single-department-background">
                        <h3 class="single-department-title<?php if($readmore_link == 'Yes'){echo '-readmore';}?>"><a href="<?php echo esc_url(get_the_permalink($department->id)); ?>"><?php echo $department->title; ?></a></h3>
                        <?php if($readmore_link == 'Yes'):?>
                        <div class="single-department-detail"><a href="<?php echo esc_url(get_the_permalink($department->id)); ?>"><?php echo __("Read more", "inwavethemes");?> <i class="fa fa-angle-right" aria-hidden="true"></i></a></div>
                        <?php endif;?>
                    </div>
                </div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Department();
