<?php
/*
 * Inwave_Doctor_Listing for Visual Composer
 */
if (!class_exists('Inwave_Doctor')) {

    class Inwave_Doctor extends Inwave_Shortcode {

        protected $name = 'iw_doctor';

        function getIwDoctors() {
            global $wpdb;
            $doctors = $wpdb->get_results('SELECT post_name, post_title FROM ' . $wpdb->prefix . 'posts WHERE post_status="publish" AND post_type="indoctor"');
            $newDoctors = array();
            $newDoctors[__("Select doctor", "inwavethemes")] = '0';
            foreach ($doctors as $doctor) {
                $newDoctors[$doctor->post_title] = $doctor->post_name;
            }
            return $newDoctors;
        }

        function init_params() {

            return array(
                'name' => __('Doctor', 'inwavethemes'),
                'description' => __('Show a single of doctor', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "heading" => "Select doctor",
                        "admin_label" => true,
                        "param_name" => "select_doctor",
                        "value" => $this->getIwDoctors(),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Style",
                        "admin_label" => true,
                        "param_name" => "style",
                        "value" => array(
                            "style1" => "style 1",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/departments_grid-v1.png',
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Social link",
                        "param_name" => "social_link",
                        "value" => array(
                            "Yes" => "Yes",
                            "No" => "No"
                        ),
                    ),
                    array(
                        "type" => "attach_image",
                        "heading" => __("Doctor Image", "inwavethemes"),
                        "param_name" => "image",
                        "value" => "",
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => "Number of description text",
                        "param_name" => "desc_text_limit",
                        "value" => '35'
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('CSS box', 'inwavethemes'),
                        'param_name' => 'css',
                        'group' => __('Design Options', 'inwavethemes')
                    )
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            global $wpdb;
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes($this->name, $atts) : $atts;
            extract(shortcode_atts(array(
                'select_doctor' => '',
                'style' => 'style1',
                'social_link' => 'Yes',
                'image' => '',
                'desc_text_limit' => '35',
                'class' => '',
                'css' => ''
                            ), $atts));
            if (!class_exists('inMedicalUtility')) {
                return __('Please active Inwave inMedical plugin', 'inwavethemes');
            }
            $id = $wpdb->get_var($wpdb->prepare('SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_name=%s', $select_doctor));
            $utility = new inMedicalUtility();
            $class .= ' ' . $class . ' ' . vc_shortcode_custom_css_class($css);
            ob_start();
            ?>
            <div class="single-doctor <?php echo $class; ?> ">
                <?php
                $doctor = new inMediacalDoctor();
                $doctor_info = $doctor->getDoctorInformation($id);

                $images_post = wp_get_attachment_image_src(get_post_thumbnail_id($doctor_info->id), 'full');
                $url_imgs_post = inwave_resize($images_post[0], 970, 448, array('center', 'top'));
                $image_post = esc_url($url_imgs_post);
                $img_tag = '';
                if ($image) {
                    $image = wp_get_attachment_image_src($image, 'large');
                    $image_post = $image[0];
                }
                $img_tag .= '<img src="' . $image_post . '" alt="">';
                ?>
                <div class="department-detail iw-doctor-detail">
                    <div class="iw-container">
                        <div class="doctor-detail-content">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="doctor-info">
                                        <h3 class="doctor-name"><?php print($doctor_info->title); ?></h3>
                                        <div class="doctor-desc"><?php print($utility->truncateString($doctor_info->content, $desc_text_limit ? $desc_text_limit : 35)); ?></div>
                                        <div class="doctor-fields">
                                            <ul>
                                                <?php if (!empty($doctor_info->extrafields)): ?>
                                                    <?php foreach ($doctor_info->extrafields as $field): ?>
                                                        <li>
                                                            <label><?php print($field['name']); ?></label>
                                                            <?php
                                                            switch ($field['type']) {
                                                                case 'textarea':
                                                                    $value = apply_filters('the_content', $field['value']);
                                                                    break;
                                                                case 'image':
                                                                    $image = wp_get_attachment_image_src($field['value'], 'large', true);
                                                                    $value = '<img src="' . esc_url($image[0]) . '" alt="">';
                                                                    break;
                                                                case 'date':
                                                                    $value = date(get_option('date_format', $field['value']));
                                                                    break;
                                                                case 'link':
                                                                    $link_value = maybe_unserialize(html_entity_decode($field['value']));
                                                                    $value = '<a href="' . esc_url($link_value['link_value_link']) . '" ' . ($link_value['link_value_target'] ? 'target="' . esc_attr($link_value['link_value_target']) . '"' : '') . '>' . $link_value['link_value_text'] . '</a>';
                                                                    break;
                                                                default:
                                                                    $value = $field['value'];
                                                                    break;
                                                            }
                                                            ?>
                                                            <span><?php print($value); ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php
                                                $dep_links = array();
                                                foreach ($doctor_info->departments as $dep):
                                                    $dep_links[] = '<span>' . $dep->title . '</span>';
                                                endforeach;
                                                ?>
                                                <li><label><?php print (__('Department: ', 'inwavethemes')); ?></label><?php echo implode(' / ', $dep_links);?></li>
                                                <li><label><?php print (__('Email: ', 'inwavethemes')); ?></label><span><a href="mailto:<?php print($doctor_info->email); ?>"><?php print($doctor_info->email); ?></a></span></li>
                                            </ul>
                                        </div>
                                        <?php
                                        if ($social_link == 'Yes'):
                                            if ($doctor_info->social_links && !empty($doctor_info->social_links)):
                                                ?>
                                                <div class="social-link">
                                                    <label><?php _e('Social Profile', 'inwavethemes'); ?>:</label>
                                                    <div>
                                                        <ul>
                                                            <?php
                                                            foreach ($doctor_info->social_links as $social_link) {
                                                                echo '<li class="' . $social_link['key_title'] . '"><a target="_blank" href="' . $social_link['key_value'] . '"><i class="fa fa-' . $social_link['key_title'] . '"></i></a></li>';
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php endif;
                                        endif;
                                        ?>
                                        <div class="view-schedule iw-button effect-5"><a class="theme-color" href="#"><?php _e('view doctor schedules', 'inwavethemes'); ?></a></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <?php
                                        if ($doctor_info->large):
                                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($doctor_info->id), 'full');
                                            $url_img = inwave_resize($image[0], 477, 620, array('center', 'top'));
                                            ?>
                                            <img alt="" src="<?php echo esc_url($url_img); ?>"/>
                                            <!--                    <div class="doctor-image" style="background: url('--><?php //print($doctor_info->large);          ?><!--') no-repeat scroll center center / cover "></div>-->
            <?php endif; ?>
                                    </div>
                                </div>
                            </div>
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

new Inwave_Doctor();
