<?php
/** Widget Our Departments */
class InMedical_Our_Departments extends WP_Widget
{
    /**
     * Construct
     */
    function __construct() {
        $widget_ops = array('classname' => 'our_departments', 'description' => esc_html__('Display InMedical our departments.', 'inwavethemes'));
        parent::__construct('our_departments', esc_html__('InMedical Our Departments', 'inwavethemes'), $widget_ops);
    }

    /**
     * Show widget
     */
    function widget($args, $instance)
    {
        $department = new inMediacalDepartment();
        $departments = $department->getDepartments();
        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        echo '<div class="iw-department-sidebar">';
        echo '<div class="sidebar-our-departments theme-bg">';

        echo $args['before_widget'];
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Our Departments', 'inwavethemes' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        if ( !empty( $title ) ) { echo $args['before_title'] . $title . $args['after_title']; }

        if ($departments) {
            echo '<ul>';
                foreach ($departments as $dep) {
                    echo '<li><a href="' . get_permalink($dep->id) . '">' . $dep->title . '</a></li>';
                }
            echo '</ul>';
        }

        echo $args['after_widget'];

        echo '</div>';
        echo '</div>';
    }

    /**
     * save widget form
     */

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /**
     * create form option cho widget
     */
    function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array( 'title' => __( 'Our Departments', 'inwavethemes' )) );
        $title = strip_tags($instance['title']);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'inwavethemes'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }
}

function inmedical_our_departments_widget() {
    register_widget('InMedical_Our_Departments');
}
add_action('widgets_init', 'inmedical_our_departments_widget');