<?php
/*
 * @package Inwave Medical
 * @version 1.0.0
 * @created Jul 30, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of imdookingExtra
 *
 * @developer duongca
 */
class inMediacalDoctor {

    /**
     * Adds the meta box container.
     */
    public function add_meta_box($post_type) {
        if ($post_type == 'indoctor') {
            add_meta_box(
                    'doctor_info', __('Doctor Information', 'inwavethemes'), array($this, 'render_meta_box_doctor_info'), $post_type, 'advanced', 'high'
            );
            add_meta_box(
                    'doctor_info_education_profile', __('Education profile', 'inwavethemes'), array($this, 'render_meta_box_education_profile'), $post_type, 'advanced', 'high'
            );
        }
    }

    function recursive_sanitize_text_field($array) {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
        }

        return $array;
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save($post_id) {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        /* @var $_POST type */
        if (!isset($_POST['imd_post_metabox_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['imd_post_metabox_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'imd_post_metabox')) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        $post_type = $_POST['post_type'];
        if ('page' == $post_type) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        if (!isset($_POST['iw_information'])) {
            return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        $iw_information = $_POST['iw_information'];
        $utility = new inMedicalUtility();

        $doctor_education_profile = $utility->margeArray($iw_information['doctor_education_profile']);
        $doctor_social_links = $utility->margeArray($iw_information['doctor_social_link']);
        $imd_doctor_info_department = $this->recursive_sanitize_text_field($iw_information['doctor_info_department']);
        $imd_doctor_info_email = sanitize_email($iw_information['doctor_info_email']);
        $imd_doctor_info_phone = sanitize_text_field($iw_information['doctor_info_phone']);

        update_post_meta($post_id, 'imd_doctor_info_department', implode(',', $imd_doctor_info_department));
        update_post_meta($post_id, 'imd_doctor_info_email', $imd_doctor_info_email);
        update_post_meta($post_id, 'imd_doctor_info_phone', $imd_doctor_info_phone);
        update_post_meta($post_id, 'imd_doctor_education_profile', serialize($doctor_education_profile));
        update_post_meta($post_id, 'imd_doctor_social_links', serialize($doctor_social_links));

        global $wpdb;
        $wpdb->delete($wpdb->prefix . "imd_extrafield_value", array('doctor_id' => $post_id));
        if (isset($iw_information['extrafield']) && is_array($iw_information['extrafield'])) {
            foreach ($iw_information['extrafield'] as $key => $val) {
                if (is_array($val)) {
                    $val = serialize($val);
                }
//                update_post_meta($post_id, $key, stripslashes(htmlspecialchars($val)));
                $ext_id = intval(substr($key, strrpos($key, '_') + 1));
                if ($ext_id) {
                    $wpdb->insert($wpdb->prefix . "imd_extrafield_value", array('doctor_id' => $post_id, 'extrafield_id' => $ext_id, 'value' => stripslashes(htmlspecialchars($val))));
                }
            }
        }
    }

    public function render_meta_box_doctor_info($post) {
        global $wpdb;
        $utility = new inMedicalUtility();
        $department_value = explode(',', get_post_meta($post->ID, 'imd_doctor_info_department', true));
        $departments = $wpdb->get_results('SELECT ID, post_title FROM ' . $wpdb->prefix . 'posts WHERE post_status="publish" AND post_type="indepartment"');
        $data = array();
        if ($departments) {
            foreach ($departments as $department) {
                $data[] = array('value' => $department->ID, 'text' => $department->post_title);
            }
        }
        $email = get_post_meta($post->ID, 'imd_doctor_info_email', true);
        $phone = get_post_meta($post->ID, 'imd_doctor_info_phone', true);
        wp_nonce_field('imd_post_metabox', 'imd_post_metabox_nonce');
        ?>
        <div class="iw-metabox-fields">
            <table class="list-table extrafield">
                <thead>
                    <tr>
                        <th class="left"><?php echo __('Text', 'inwavethemes'); ?></th>
                        <th><?php echo __('Value', 'inwavethemes'); ?></th>
                    </tr>
                </thead>
                <tbody class="the-list">
                    <tr class="alternate">
                        <td>
                            <label for="teacher_relative"><?php _e('Department', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php echo $utility->selectFieldRender('teacher_relative', 'iw_information[doctor_info_department]', $department_value, $data, 'Select Department', '', true); ?>
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td>
                            <label for=""><?php _e('Email', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <input type="text" required="required" name="iw_information[doctor_info_email]" value="<?php print($email); ?>"/>
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td>
                            <label for=""><?php _e('Phone Number', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <input type="text" name="iw_information[doctor_info_phone]" value="<?php print($phone); ?>"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
            /* Create a settings metabox ----------------------------------------------------- */
            $extrafield = new inMedicalExtra();
            $fields = $extrafield->getDoctorExtrafielđetail($post->ID);
            if (!empty($fields)) {
                $this->metaBoxHtmlRender($post, $fields);
            }
            ?>
            <div class="section-title"><?php _e('Social Links', 'inwavethemes'); ?></div>
            <?php
            $value = get_post_meta($post->ID, 'imd_doctor_social_links', true);
            $social_link = unserialize($value);
            $data_links = array(
                array('value' => 'facebook', 'text' => __('Facebook', 'inwavethemes')),
                array('value' => 'youtube', 'text' => __('Youtube', 'inwavethemes')),
                array('value' => 'vimeo', 'text' => __('Vimeo', 'inwavethemes')),
                array('value' => 'flickr', 'text' => __('Flickr', 'inwavethemes')),
                array('value' => 'google-plus', 'text' => __('Google+', 'inwavethemes')),
                array('value' => 'linkedin', 'text' => __('Linkedin', 'inwavethemes')),
                array('value' => 'tumblr', 'text' => __('Tumblr', 'inwavethemes')),
                array('value' => 'twitter', 'text' => __('Twitter', 'inwavethemes'))
            );
            ?>
            <table class="list-table">
                <thead>
                    <tr>
                        <th class="left"><?php echo __('Social Type', 'inwavethemes'); ?></th>
                        <th><?php echo __('Link', 'inwavethemes'); ?></th>
                        <th class="right"></th>
                    </tr>
                </thead>
                <tbody class="the-list">
                    <?php
                    if ($social_link):
                        foreach ($social_link as $exp):
                            ?>
                            <tr class="alternate social-link-types">
                                <td class="left">
                                    <?php
                                    echo $utility->selectFieldRender('', 'iw_information[doctor_social_link][key_title][]', $exp['key_title'], $data_links, '', '', false);
                                    ?>
                                </td>
                                <td>
                                    <input type="url" placeholder="<?php echo __('Social Link Value', 'inwavethemes'); ?>" name="iw_information[doctor_social_link][key_value][]" value="<?php echo $exp['key_value']; ?>"/>
                                </td>
                                <td class="right">
                                    <span class="button remove-button"><?php echo __('Remove', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <tr class="alternate social-link-types">
                            <td class="left">
                                <?php
                                echo $utility->selectFieldRender('', 'iw_information[doctor_social_link][key_title][]', NULL, $data_links, '', '', false);
                                ?>
                            </td>
                            <td>
                                <input type="url" placeholder="<?php echo __('Social Link Value', 'inwavethemes'); ?>" name="iw_information[doctor_social_link][key_value][]" value=""/>
                            </td>
                            <td class="right">
                                <span class="button remove-button"><?php echo __('Remove', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="3">
                            <div class="submit">
                                <span class="button add-row social"><?php echo __('Add Social', 'inwavethemes'); ?></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function render_meta_box_education_profile($post) {

        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta($post->ID, 'imd_doctor_education_profile', true);
        $education_profile = unserialize($value);
        ?>
        <div class="iw-metabox-fields">
            <table class="list-table">
                <thead>
                    <tr>
                        <th class="left"><?php echo __('Text', 'inwavethemes'); ?></th>
                        <th><?php echo __('Value', 'inwavethemes'); ?></th>
                        <th class="right"></th>
                    </tr>
                </thead>
                <tbody class="the-list">
                    <?php
                    if ($education_profile):
                        foreach ($education_profile as $exp):
                            ?>
                            <tr class="alternate">
                                <td class="left">
                                    <input value="<?php echo $exp['key_title']; ?>" placeholder="<?php echo __('Education Profile Title', 'inwavethemes'); ?>" type="text" size="20" name="iw_information[doctor_education_profile][key_title][]"/>
                                </td>
                                <td>
                                    <textarea placeholder="<?php echo __('Education Profile Value', 'inwavethemes'); ?>" name="iw_information[doctor_education_profile][key_value][]"><?php echo $exp['key_value']; ?></textarea>
                                </td>
                                <td class="right">
                                    <span class="button remove-button"><?php echo __('Remove', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <tr class="alternate">
                            <td class="left">
                                <input value="" placeholder="<?php echo __('Education Profile Title', 'inwavethemes'); ?>" type="text" size="20" name="iw_information[doctor_education_profile][key_title][]"/>
                            </td>
                            <td>
                                <textarea placeholder="<?php echo __('Education Profile Value', 'inwavethemes'); ?>" name="iw_information[doctor_education_profile][key_value][]"></textarea>
                            </td>
                            <td class="right">
                                <span class="button remove-button"><?php echo __('Remove', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="3">
                            <div class="submit">
                                <span class="button add-row education-profile"><?php echo __('Add Profile', 'inwavethemes'); ?></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }

    static function getDoctorInformation($post) {
        if (!$post) {
            return false;
        }

        if (is_numeric($post)) {
            $post = get_post($post);
        }
        $imd_cache = wp_cache_get('doctor_' . $post->ID);
        if ($imd_cache) {
            return $imd_cache;
        }
        $department = new inMediacalDepartment();
        $extrafield = new inMedicalExtra();
        $departments = array();
        $doctor = new stdClass();
        $doctor->id = $post->ID;
        $doctor->title = apply_filters('the_title',$post->post_title,$post->ID);
        $doctor->slug = $post->post_name;
        $doctor->image = get_the_post_thumbnail_url($post, 'full');
        $doctor->thumbnail = get_the_post_thumbnail_url($post, 'inmedical-thumb');
        $doctor->large = get_the_post_thumbnail_url($post, 'inmedical-large');
        $doctor->content = apply_filters('the_content',$post->post_content);
        $doctor->short_content = $post->post_excerpt ? apply_filters('the_content',$post->post_excerpt) : wp_trim_words(apply_filters('the_content',$post->post_content));
        $dep_ids = explode(',', get_post_meta($post->ID, 'imd_doctor_info_department', true));
        if (!empty($dep_ids)) {
            foreach ($dep_ids as $dep_id) {
                $departments[] = $department->getDepartmentInformation($dep_id);
            }
        }
        $doctor->departments = $departments;
        $doctor->email = get_post_meta($post->ID, 'imd_doctor_info_email', true);
        $doctor->education_profile = array('des' => get_post_meta($post->ID, 'imd_doctor_education_description', true), 'profiles' => unserialize(get_post_meta($post->ID, 'imd_doctor_education_profile', true)));
        $doctor->social_links = unserialize(get_post_meta($post->ID, 'imd_doctor_social_links', true));
        $doctor->extrafields = $extrafield->getDoctorExtrafielđetail($doctor->id);
        wp_cache_set('doctor_' . $doctor->id, $doctor);

        return $doctor;
    }

    public function getDoctors($ids, $departments, $order_by, $order_dir, $item_per_page, $page = 'page') {
        if ($page == 'page') {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        } else {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        }
        if ( $page == 'pagenum' ) {
            $paged = isset( $_GET['pagenum'] ) ? $_GET['pagenum'] : $paged;
        }
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
        $order_byn = isset($_REQUEST['order_by']) ? $_REQUEST['order_by'] : $order_by;
        $order_dirn = isset($_REQUEST['order_dir']) ? $_REQUEST['order_dir'] : $order_dir;

        $args = array();
        if ($ids) {
            $args['post__in'] = explode(',', $ids);
        }
        $departments_array = explode(',', $departments);
        if (count($departments_array)>1) {
            $args['meta_query'] = array('relation' => 'OR');
            foreach ($departments_array as $dep_id) {
                $args['meta_query'][] = array(
                    array(
                        'key' => 'imd_doctor_info_department',
                        'value' => $dep_id,
                        'compare' => 'LIKE',
                ));
            }
        }
        $args['post_type'] = 'indoctor';
        $args['s'] = $keyword;
        $args['order'] = ($order_dirn) ? $order_dirn : 'desc';
        $args['orderby'] = ($order_byn) ? $order_byn : 'ID';
        $args['post_status'] = 'publish';
        $args['posts_per_page'] = $item_per_page ? $item_per_page : -1;
        $args['paged'] = $paged;
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            return array(
                'pages' => $query->max_num_pages,
                'data' => $query->posts,
            );
        } else {
            return false;
        }
    }

    public function getDoctorsByDepartment($department) {
        $args = array();
        $args['post_type'] = 'indoctor';
        $args['order'] = 'desc';
        $args['orderby'] = 'ID';
        $args['meta_key'] = 'imd_doctor_info_department';
        $args['meta_value'] = $department;
        $args['meta_compare'] = 'LIKE';
        $args['post_status'] = 'publish';
        $args['posts_per_page'] = -1;
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            return $query->posts;
        } else {
            return false;
        }
    }

    /**
     * Print HTML content of a meta box.
     *
     * @since 1.0.0
     * @param object $post The post
     * @param array $meta_box Meta box data
     */
    function metaBoxHtmlRender($post, $fields) {
        $utility = new inMedicalUtility();
        if (!is_array($fields)) {
            return false;
        }
        if (!empty($fields)):
            ?>
            <div class="section-title extrafield"><?php _e('Extrafield Information', 'inwavethemes'); ?></div>
            <table class="list-table extrafield">
                <thead>
                    <tr>
                        <th class="left"><?php echo __('Field Name', 'inwavethemes'); ?></th>
                        <th><?php echo __('Value', 'inwavethemes'); ?></th>
                    </tr>
                </thead>
                <tbody class="the-list">
                    <?php
                    foreach ($fields as $field):
                        ?>
                        <tr class="alternate">
                            <td class="left">
                                <label for='<?php echo $field['id']; ?>'><strong><?php echo ($field['desc'] ? '<abbr title="' . $field['desc'] . '">' : ''), $field['name'], ($field['desc'] ? '</abbr>' : '') ?></strong></label>
                            </td>
                            <td>
                                <?php
                                switch ($field['type']):
                                    case 'text':
                                        echo "<input title='{$field['title']}' type='text' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' value='" . ($field['value'] ? $field['value'] : htmlentities(stripslashes($field['std']))) . "' />";
                                        break;
                                    case 'dropdown_list':
                                        $field_data = unserialize($field['std']);
                                        if ($field_data[1] == 1) {
                                            echo "<select name='iw_information[extrafield][{$field['id']}][]' id='{$field['id']}' multiple='multiple' >";
                                        } else {
                                            echo "<select name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' >";
                                        }
                                        $options = explode("\n", htmlentities(stripslashes($field_data[0])));
                                        foreach ($options as $option) {
                                            $option_data = explode("|", $option);
                                            if (count($option_data) == 3) {
                                                echo "<option value='{$option_data[0]}'";
                                                if ($field['value']) {
                                                    $val = $option_data[0];
                                                    if ($field_data[1] == 1) {
                                                        $values = unserialize(html_entity_decode($field['value']));
                                                    } else {
                                                        $values = $field['value'];
                                                    }
                                                    if ((is_array($values) && in_array($val, $values)) || $values == $val) {
                                                        echo ' selected="selected"';
                                                    }
                                                } else {
                                                    if ($option_data[2] == 1) {
                                                        echo ' selected="selected"';
                                                    }
                                                }
                                                echo ">{$option_data[1]}</option>";
                                            }
                                        }
                                        echo '</select>';
                                        break;
                                    case 'textarea':
                                        echo "<textarea title='{$field['title']}' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}'>" . ($field['value'] ? $field['value'] : htmlentities(stripslashes($field['std']))) . "</textarea>";
                                        break;
                                    case 'date':
                                        echo "<input type='text' title='{$field['title']}' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' value='" . ($field['value'] ? $field['value'] : $field['std']) . "'/>";
                                        echo '<script tpe="text/javascript">
                            jQuery(document).ready(function () {
                                jQuery("#' . $field['id'] . '").datepicker({
                                    dateFormat: "dd-mm-yy"
                                });
                            });
                        </script>';
                                        break;
                                    case 'measurement':
                                        $measurement_data = unserialize(html_entity_decode($field['value']));
                                        if (!$measurement_data) {
                                            $measurement_data = unserialize($field['std']);
                                        }
                                        $measurement_value = htmlentities(stripslashes($measurement_data['measurement_value']));
                                        $measurement_unit = $measurement_data['measurement_unit'];
                                        echo "<input type='text' title='{$field['title']}' name='iw_information[extrafield][{$field['id']}][measurement_value]' id='{$field['id']}' value='" . $measurement_value . "'/>";
                                        echo "<input type='text' name='iw_information[extrafield][{$field['id']}][measurement_unit]' id='{$field['id']}' value='" . $measurement_unit . "'/>";
                                        break;
                                    case 'link':
                                        $link_data = unserialize(html_entity_decode($field['value']));
                                        if (!$link_data) {
                                            $link_data = unserialize($field['std']);
                                        }
                                        $link_url = htmlentities(stripslashes($link_data['link_value_link']));
                                        $link_text = htmlentities(stripslashes($link_data['link_value_text']));
                                        $link_target = $link_data['link_value_target'];
                                        echo '<input placeholder = "' . __('URL', 'inwavethemes') . '" name = "iw_information[extrafield][' . $field['id'] . '][link_value_link]" value = "' . $link_url . '" type = "url"/>';
                                        echo '<input placeholder = "' . __('Text', 'inwavethemes') . '" name = "iw_information[extrafield][' . $field['id'] . '][link_value_text]" value = "' . $link_text . '" type = "text"/>';
                                        $link_datas = array(
                                            array('value' => '_blank', 'text' => 'Blank'),
                                            array('value' => '_self', 'text' => 'Self')
                                        );
                                        echo $utility->selectFieldRender('iw_information_' . $field['id'], 'iw_information[extrafield][' . $field['id'] . '][link_value_target]', $link_target, $link_datas, 'Target', '', false);
                                        break;
                                    case 'image':
                                        echo $utility->imageFieldRender('', 'iw_information[extrafield][' . $field['id'] . ']', $field['value'] ? $field['value'] : $field['std']);
                                        ?>
                                        <?php
                                        break;
                                    case 'images':
                                        ?>
                                        <script>
                                            jQuery(function ($) {
                                                // Load images
                                                function loadImages(images) {
                                                    if (images) {
                                                        var shortcode = new wp.shortcode({
                                                            tag: 'gallery',
                                                            attrs: {ids: images},
                                                            type: 'single'
                                                        });

                                                        var attachments = wp.media.gallery.attachments(shortcode);

                                                        var selection = new wp.media.model.Selection(attachments.models, {
                                                            props: attachments.props.toJSON(),
                                                            multiple: true
                                                        });

                                                        selection.gallery = attachments.gallery;

                                                        // Fetch the query's attachments, and then break ties from the
                                                        // query to allow for sorting.
                                                        selection.more().done(function () {
                                                            // Break ties with the query.
                                                            selection.props.set({query: false});
                                                            selection.unmirror();
                                                            selection.props.unset('orderby');
                                                        });

                                                        return selection;
                                                    }

                                                    return false;
                                                }

                                                var frame,
                                                        images = '<?php echo get_post_meta($post->ID, '_iw_courses_image_ids', true); ?>'
                                                selection = loadImages(images);

                                                $('#iw_courses_image_upload').on('click', function (e) {
                                                    e.preventDefault();

                                                    // Set options for 1st frame render
                                                    var options = {
                                                        title: '<?php _e('Create Gallery', 'inwavethemes'); ?>',
                                                        state: 'gallery-edit',
                                                        frame: 'post',
                                                        selection: selection
                                                    }

                                                    // Check if frame or gallery already exist
                                                    if (frame || selection) {
                                                        options['title'] = '<?php _e('Edit Gallery', 'inwavethemes'); ?>';
                                                    }

                                                    frame = wp.media(options).open();

                                                    // Tweak views
                                                    frame.menu.get('view').unset('cancel');
                                                    frame.menu.get('view').unset('separateCancel');
                                                    frame.menu.get('view').get('gallery-edit').el.innerHTML = '<?php _e('Edit Gallery', 'inwavethemes'); ?>';
                                                    frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

                                                    // When we are editing a gallery
                                                    overrideGalleryInsert();
                                                    frame.on('toolbar:render:gallery-edit', function () {
                                                        overrideGalleryInsert();
                                                    });

                                                    frame.on('content:render:browse', function (browser) {
                                                        if (!browser)
                                                            return;
                                                        // Hide Gallery Settings in sidebar
                                                        browser.sidebar.on('ready', function () {
                                                            browser.sidebar.unset('gallery');
                                                        });
                                                        // Hide filter/search as they don't work
                                                        browser.toolbar.on('ready', function () {
                                                            if (browser.toolbar.controller._state == 'gallery-library') {
                                                                browser.toolbar.$el.hide();
                                                            }
                                                        });
                                                    });

                                                    // All images removed
                                                    frame.state().get('library').on('remove', function () {
                                                        var models = frame.state().get('library');
                                                        if (models.length == 0) {
                                                            selection = false;
                                                            $.post(ajaxurl, {ids: '', action: 'iw_courses_save_images', post_id: iw_courses_ajax.post_id, nonce: iw_courses_ajax.nonce});
                                                        }
                                                    });

                                                    // Override insert button
                                                    function overrideGalleryInsert() {
                                                        frame.toolbar.get('view').set({
                                                            insert: {
                                                                style: 'primary',
                                                                text: '<?php echo _e('Save Gallery', 'inwavethemes'); ?>',
                                                                click: function () {
                                                                    var models = frame.state().get('library'),
                                                                            ids = '';

                                                                    models.each(function (attachment) {
                                                                        ids += attachment.id + ','
                                                                    });

                                                                    this.el.innerHTML = '<?php _e('Saving ...', 'inwavethemes'); ?>';

                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: ajaxurl,
                                                                        data: {
                                                                            ids: ids,
                                                                            action: 'iw_courses_save_images',
                                                                            post_id: iw_courses_ajax.post_id,
                                                                            nonce: iw_courses_ajax.nonce
                                                                        },
                                                                        success: function () {
                                                                            selection = loadImages(ids);
                                                                            $('#_iw_courses_image_ids').val(ids);
                                                                            frame.close();
                                                                        },
                                                                        dataType: 'html'
                                                                    }).done(function (data) {
                                                                        $('.iwc-gallery-thumbnail').html(data);
                                                                    });

                                                                } // end click function
                                                            } // end insert
                                                        });
                                                    }
                                                });
                                            });
                                        </script>
                                        <?php
// SPECIAL CASE:
                                        $thumbs_output = '';
                                        $button_text = $meta ? __('Edit Gallery', 'inwavethemes') : $field['std'];
                                        if ($field['value']) {
                                            $field['std'] = __('Edit Gallery', 'inwavethemes');
                                            $thumbs = explode(',', $field['value']);
                                            foreach ($thumbs as $thumb) {
                                                $thumbs_output .= '<li>' . wp_get_attachment_image($thumb, array(50, 50)) . '</li>';
                                            }
                                        }
                                        echo
                                        "
						<input type='button' class='button' name='{$field['id']}' id='iw_courses_image_upload' value='$button_text' />
						<input type='hidden' name='iw_information[extrafield][_iw_courses_image_ids]' id='_iw_courses_image_ids' value='" . ($field['value'] ? $field['value'] : 'false') . "' />
						<ul class='iwc-gallery-thumbnail'>{$thumbs_output}</ul>
					";
                                        break;
                                    case 'file':
                                        ?>
                                        <script>
                                            jQuery(function ($) {
                                                var frame;

                                                $('#<?php echo $field['id']; ?>_button').on('click', function (e) {
                                                    e.preventDefault();

                                                    // Set options
                                                    var options = {
                                                        state: 'insert',
                                                        frame: 'post'
                                                    };

                                                    frame = wp.media(options).open();

                                                    // Tweak views
                                                    frame.menu.get('view').unset('gallery');
                                                    frame.menu.get('view').unset('featured-image');

                                                    frame.toolbar.get('view').set({
                                                        insert: {
                                                            style: 'primary',
                                                            text: '<?php _e('Insert', 'inwavethemes'); ?>',
                                                            click: function () {
                                                                var models = frame.state().get('selection'),
                                                                        url = models.first().attributes.url;

                                                                $('#<?php echo $field['id']; ?>').val(url);

                                                                frame.close();
                                                            }
                                                        } // end insert
                                                    });
                                                });
                                            });
                                        </script>
                                        <?php
                                        echo
                                        "
						<input type='text' title='{$field['title']}' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' value='" . ($field['value'] ? $field['value'] : $field['std']) . "' class='file' />
						<input type='button' class='button' name='{$field['id']}_button' id='{$field['id']}_button' value='Browse' />
					";
                                        break;
                                    default:
                                        break;
                                endswitch;
                                ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
        <?php
    }

    //Ajax sendMessageToDoctor
    function sendMessageToDoctor() {
        $result = array();
        $result['success'] = false;
        $mailto = isset($_POST['doctor-email']) ? $_POST['doctor-email'] : '';
        if (!$mailto) {
            $mailto = get_option('admin_email');
        }
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $message = isset($_POST['message']) ? $_POST['message'] : '';
        $title = __('Email from "Question Form"', 'inwavethemes') . ' [' . $email . ']';

        $html = '<html><head><title>' . $title . '</title>
                    </head><body><p>' . __('Hi Admin,', 'inwavethemes') . '</p><p>' . __('This email was sent from contact form', 'inwavethemes') . '</p><table>';

        if ($name) {
            $html .= '<tr><td>' . __('Name', 'inwavethemes') . '</td><td>' . $name . '</td></tr>';
        }
        if ($email) {
            $html .= '<tr><td>' . __('Email', 'inwavethemes') . '</td><td>' . $email . '</td></tr>';
        }
        if ($message) {
            $html .= '<tr><td>' . __('Message', 'inwavethemes') . '</td><td>' . $message . '</td></tr>';
        }
        $html .= '</tr></table></body></html>';

        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

        if (wp_mail($mailto, $title, $html, $headers)) {
            $result['success'] = true;
            $result['message'] = __('Your message was sent, we will contact you soon', 'inwavethemes');
        } else {
            $result['message'] = __('Can\'t send message, please try again', 'inwavethemes');
        }
        echo json_encode($result);
        exit();
    }

    public function getDoctorEvents($doctor) {
        global $wpdb;
        $start_time = strtotime(date('m/d/Y', time()));
        $end_time = strtotime(date('m/d/Y', time() + 2592000));
        $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND (b.event_date BETWEEN %d AND %d) AND b.doctor_post=%d', 'inmedical', 'publish', $start_time, $end_time, $doctor));
        return $events;
    }

    public function countDoctorEventsListing($get_filter, $days_setting) {
        global $wpdb;
        $start_time = strtotime(date('m/d/Y', time()));
        $end_time = strtotime(date('m/d/Y', time() + 2592000));
        $tomorrow = strtotime(date('m/d/Y', time() + 60 * 60 * 24));
        $nextweek = strtotime(date('m/d/Y', time() + 60 * 60 * 24 * $days_setting));
        if ($get_filter == 'expired') {
            $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND b.event_date<%d  ORDER BY b.event_date ASC', 'inmedical', 'publish', $start_time));
        } elseif ($get_filter == 'upcomming') {
            $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND (b.event_date BETWEEN %d AND %d) ORDER BY b.event_date ASC', 'inmedical', 'publish', $tomorrow, $nextweek));
        } else {
            $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND (b.event_date BETWEEN %d AND %d) ORDER BY b.event_date ASC', 'inmedical', 'publish', $start_time, $end_time));
        }
        $count = count($events);
        return $count;
    }

    public function getDoctorEventsListing($start, $posts_per_page, $get_filter, $days_setting) {
        global $wpdb;
        $start_time = strtotime(date('m/d/Y', time()));
        $end_time = strtotime(date('m/d/Y', time() + 2592000));
        $tomorrow = strtotime(date('m/d/Y', time() + 60 * 60 * 24));
        $nextweek = strtotime(date('m/d/Y', time() + 60 * 60 * 24 * $days_setting));
        if ($get_filter == 'expired') {
            $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND b.event_date<%d ORDER BY b.event_date ASC LIMIT %d, %d', 'inmedical', 'publish', $start_time, $start, $posts_per_page));
        } elseif ($get_filter == 'upcoming') {
            $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND (b.event_date BETWEEN %d AND %d) ORDER BY b.event_date ASC LIMIT %d, %d', 'inmedical', 'publish', $tomorrow, $nextweek, $start, $posts_per_page));
        } else {
            $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND (b.event_date BETWEEN %d AND %d) ORDER BY b.event_date ASC LIMIT %d, %d', 'inmedical', 'publish', $start_time, $end_time, $start, $posts_per_page));
        }
        return $events;
    }

}
