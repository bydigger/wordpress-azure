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
class inMediacalDepartment {

    /**
     * Adds the meta box container.
     */
    public function add_meta_box($post_type) {
        if ($post_type == 'indepartment') {
            add_meta_box(
                    'department_detail', __('Department Detail', 'inwavethemes'), array($this, 'render_meta_box_department_detail'), $post_type, 'advanced', 'high'
            );
        }
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

        $icon = sanitize_title($iw_information['icon']);

        update_post_meta($post_id, 'imd_department_detail_icon', $icon);
    }

    public function render_meta_box_department_detail($post) {
        $icon = get_post_meta($post->ID, 'imd_department_detail_icon', true);
        $utility = new inMedicalUtility();
        wp_nonce_field('imd_post_metabox', 'imd_post_metabox_nonce');
        ?>
        <div class="iw-metabox-fields">
            <table class="list-table">
                <thead>
                    <tr>
                        <th class="left"><?php echo __('Label', 'inwavethemes'); ?></th>
                        <th><?php echo __('Value', 'inwavethemes'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="the-list">
                    <tr class="alternate">
                        <td>
                            <?php _e('Icon', 'inwavethemes'); ?>
                        </td>
                        <td>
                            <?php echo $utility->imageFieldRender('', 'iw_information[icon]', $icon); ?>
                        </td>
                        <td>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function getDepartments($item_department_per_page = -1, $order_by="ID", $order_dir="DESC") {
        $args = array();
        $result = array();
        $args['post_type'] = 'indepartment';
        $args['post_status'] = 'publish';
        $args['posts_per_page'] = $item_department_per_page ? $item_department_per_page : -1;
		$args['order'] = $order_dir;
        $args['orderby'] = $order_by;

        $query = new WP_Query($args);
        if ($query->have_posts()) {
            foreach ($query->posts as $dep) {
                $result[] = $this->getDepartmentInformation($dep);
            }
        }
        return $result;
    }

    public function getDepartmentInformation($post) {
        $utility = new inMedicalUtility();
        if (is_numeric($post)) {
            $post = get_post($post);
        }
        if(!$post){
            return false;
        }
        $imd_cache = wp_cache_get('department_' . $post->ID);
        if ($imd_cache) {
            return $imd_cache;
        }
        $department = new stdClass();
        $department->id = $post->ID;
        $department->title = apply_filters('the_title',$post->post_title,$post->ID);
        $department->slug = $post->post_name;
        $department->image = get_the_post_thumbnail_url($post, 'full');
        $department->thumbnail = get_the_post_thumbnail_url($post, 'inmedical-thumb');
        $department->large = get_the_post_thumbnail_url($post, 'inmedical-large');
        $department->content = apply_filters('the_content',$post->post_content);
        $department->short_description = $utility->truncateString(apply_filters('the_content',$post->post_content), 15);
        $department->icon = wp_get_attachment_image_url(get_post_meta($department->id, 'imd_department_detail_icon', true), 'full');
        wp_cache_set('department_' . $department->id, $department);

        return $department;
    }

    public function getDepartmentDoctors($department) {
        $doctor = new inMediacalDoctor();
        return $doctor->getDoctorsByDepartment($department);
    }

    public function getDepartmentEvents($department) {
        global $wpdb;
        $start_time = strtotime(date('m/d/Y', time()));
        $end_time = strtotime(date('m/d/Y', time() + 2592000));
        $events = $wpdb->get_results($wpdb->prepare('Select * from ' . $wpdb->prefix . 'posts AS a INNER JOIN ' . $wpdb->prefix . 'imd_events AS b ON a.ID=b.event_post WHERE a.post_type=%s AND a.post_status=%s AND (b.event_date BETWEEN %d AND %d) AND b.department_post=%d', 'inmedical', 'publish', $start_time, $end_time, $department));
        return $events;
    }

}
