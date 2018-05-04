<?php
/*Register VC Appoinments Calendar*/
if (!class_exists('Inwave_Register_VC_Appoinments')) {
    function Inwave_Register_VC_Appoinments() {
        if (class_exists('Vc_Manager')) {
            global $wpdb;
            $sql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_status = %s AND post_type = %s";
            $departments = $wpdb->get_results($wpdb->prepare($sql, 'publish', 'indepartment'));
            $department_options = array(__('All Departments', 'inwavethemes') => '');
            if ($departments) {
                foreach ($departments as $department) {
                    $department_options[esc_attr($department->post_title)] = $department->ID;
                }
            }

            vc_map(array(
                'name' => __('Appoinments Calendar', 'inwavethemes'),
                'description' => __('Create a Appoinments Calendar', 'inwavethemes'),
                'base' => 'inmedical_appointments',
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "heading" => __("Select Department", "inwavethemes"),
                        "param_name" => "department_ids",
                        "type" => "dropdown",
                        "value" => $department_options,
                    ),
                )
            ));
        }
    }

    add_action('init', 'Inwave_Register_VC_Appoinments');
}

/*Register VC Appoinments Scroll Vertical*/
if (!class_exists('Inwave_Register_VC_Appoinments_Scroll_Vertical')) {
    function Inwave_Register_VC_Appoinments_Scroll_Vertical() {
        if (class_exists('Vc_Manager')) {
            global $wpdb;
            $sql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_status = %s AND post_type = %s";
            $departments = $wpdb->get_results($wpdb->prepare($sql, 'publish', 'indepartment'));
            $department_options = array(__('All Departments', 'inwavethemes') => '');
            if ($departments) {
                foreach ($departments as $department) {
                    $department_options[esc_attr($department->post_title)] = $department->ID;
                }
            }

            vc_map(array(
                'name' => __('Appoinments Scroll Vertical', 'inwavethemes'),
                'description' => __('Create a Appoinments Scroll Vertical', 'inwavethemes'),
                'base' => 'inmedical_appointments_scroll_vertical',
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "heading" => __("Select Department", "inwavethemes"),
                        "param_name" => "department_ids",
                        "type" => "dropdown",
                        "value" => $department_options,
                    ),
                    array(
                        "heading" => __("Number days show", "inwavethemes"),
                        "param_name" => "number_days_show",
                        "type" => "textfield",
                        "value" => 7,
                    ),
                    array(
                        "heading" => __("Number items show", "inwavethemes"),
                        "param_name" => "number_items_show",
                        "type" => "textfield",
                        "value" => 3,
                    ),
                )
            ));
        }
    }

    add_action('init', 'Inwave_Register_VC_Appoinments_Scroll_Vertical');
}
