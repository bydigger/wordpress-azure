<?php
/** Widget contact in footer  */
//Address email example: http://joomultra.us12.list-manage.com/subscribe/post?u=fbd801b2d75b67e540b5e0c53&id=2aad0371a2
class Inwave_Widget_Subscribe extends WP_Widget {

    /**
     * Construct
     */
    function __construct() {
        parent::__construct(
            'inwave-subscribe',
            esc_html__('Inwave Subscribe', 'inmedical'),
            array( 'description'  =>  esc_html__('Widget display subscribe.', 'inmedical') )
        );
    }

    /**
     * Tạo form option cho widget
     */
    function form( $instance ) {
        $default = array(
            'title'             => 'Title',
            'description'       => '',
            'action'            => '',
            'heading_social'    => '',
            'social_resource'   => '',
            'custom_social'     => ''
        );

        $instance = wp_parse_args( (array) $instance, $default );
        $title = esc_attr($instance['title']);
        $description = esc_attr($instance['description']);
        $action = esc_attr($instance['action']);
        $heading_social = esc_attr($instance['heading_social']);

        echo '<p>'.esc_html__('Title', 'inmedical').'<input type="text" class="widefat" id="'. esc_attr($this->get_field_id('title')) . '" name="'.esc_attr($this->get_field_name('title')).'" value="'.esc_attr($title).'"/></p>';
        echo '<p>'.esc_html__('Description', 'inmedical').'<textarea class="widefat" rows="5" cols="10" class="widefat" id="'. esc_attr($this->get_field_id('description')) . '" name="'.esc_attr($this->get_field_name('description')).'">' . $description .'</textarea></p>';
        echo '<p>'.esc_html__('Action Url', 'inmedical').'<input type="text" class="widefat" name="'.esc_attr($this->get_field_name('action')).'" value="'.esc_attr($action).'"/></p>';
        echo '<p>'.esc_html__('Heading Social', 'inmedical').'<input type="text" class="widefat" id="'. esc_attr($this->get_field_id('heading_social')) . '" name="'.esc_attr($this->get_field_name('heading_social')).'" value="'.esc_attr($heading_social).'"/></p>';

    ?>
        <p class="social_resource"><label for="<?php echo esc_attr($this->get_field_id('social_resource')); ?>"><?php echo esc_html__('Social Resource', 'inmedical'); ?></label><br/>
            <select name="<?php echo esc_attr($this->get_field_name('social_resource')); ?>" id="<?php echo esc_attr($this->get_field_id('social_resource')); ?>" class="widefat">
                <option value="1" <?php echo (esc_html($instance['social_resource']) == '1' ? 'selected' : ''); ?>><?php echo esc_html__('Theme config', 'inmedical'); ?></option>
                <option value="2" <?php echo (esc_html($instance['social_resource']) == '2' ? 'selected' : ''); ?>><?php echo esc_html__('Custom', 'inmedical'); ?></option>
            </select>
        </p>
        <p class="custom_social" style="<?php echo (esc_html($instance['social_resource']) == '2' ? '' : 'display: none;'); ?>" >
            <label for="<?php echo esc_attr($this->get_field_id('custom_social')); ?>"><?php echo esc_html__('Custom Social', 'inmedical'); ?></label><br/>
            <textarea rows="10" cols="70" name="<?php echo esc_attr($this->get_field_name('custom_social')); ?>" id="<?php echo esc_attr($this->get_field_id('custom_social')); ?>" class="widefat"><?php echo esc_html($instance['custom_social']); ?></textarea>
            <span>One Social per one line</span>
        </p>
    <?php
    }
    /**
     * save widget form
     */

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['action'] = strip_tags($new_instance['action']);
        $instance['heading_social'] = strip_tags($new_instance['heading_social']);
        $instance['social_resource'] = strip_tags($new_instance['social_resource']);
        $instance['custom_social'] = strip_tags($new_instance['custom_social']);

        return $instance;
    }

    /**
     * Show widget
     */
    function widget( $args, $instance ) {
        global $inwave_theme_option;
        wp_enqueue_script('mailchimp');
        $output = '';
        extract( $args );

        if(!isset($instance['social_resource'])){
            $instance['social_resource'] = '';
        }
        if(!isset($instance['custom_social'])){
            $instance['custom_social'] = '';
        }
        $title = apply_filters( 'widget_title', $instance['title'] );
        $description = empty($instance['description'] ) ? '' : $instance['description'];
        $action = empty( $instance['action'] ) ? '' : $instance['action'];
        $heading_social = empty( $instance['heading_social'] ) ? '' : $instance['heading_social'];
        $social_resource = empty( $instance['social_resource'] ) ? '' : $instance['social_resource'];
        $socials = empty( $instance['custom_social'] ) ? '' : $instance['custom_social'];

        echo wp_kses_post($before_widget);

        echo wp_kses_post($before_title. $title .$after_title);
        $response['submit'] = esc_html__('Submitting...','inmedical');
        $response[0] = esc_html__('We have sent you a confirmation email','inmedical');
        $response[1] = esc_html__('Please enter a value','inmedical');
        $response[2] = esc_html__('An email address must contain a single @','inmedical');
        $response[3] = esc_html__('The domain portion of the email address is invalid (the portion after the @: )','inmedical');
        $response[4] = esc_html__('The username portion of the email address is invalid (the portion before the @: )','inmedical');
        $response[5] = esc_html__('This email address looks fake or invalid. Please enter a real email address','inmedical');

        $response = json_encode($response);

        $output .= '<div class="iw-mailchimp-form "><form class="iw-email-notifications" action="' . $action . '" data-response="' . htmlentities($response) . '">';
        if($description){
            $output .= '<div class="malchimp-desc">'.$description.'</div>';
        }
        $output .= '<div class="ajax-overlay"><span class="ajax-loading"><i class="fa fa-spinner fa-spin fa-2x"></i></span></div>';
        $output .= '<input class="mc-email" type="email"  placeholder="' .esc_attr(esc_html__('Email address', 'inmedical')) .'">';
        $output .= '<button type="submit">' .esc_html__('Subscribe', 'inmedical') .'</button>';
        $output .= '<span class="response"><label></label></span>';
        $output .= '<h5>' . $heading_social . '</h5>';
        if($social_resource ==  '1'){
            $output .= '<ul class="iw-social-footer-all">';
            if (isset($inwave_theme_option['social_links']) && count($inwave_theme_option['social_links']) > 1) {
                $social_links = $inwave_theme_option['social_links'];
                unset($social_links[0]);
                foreach ($social_links as $social_link) {
                    $class = explode(".com", $social_link['link']);
                    $class = str_replace(array('https://', 'http://', 'www.'), '', $class[0]);
                    if ($class == 'plus.google') {
                        $class = 'google-plus';
                    }
                    $output .= '<li><a class="'.$class.'" href="' . esc_url($social_link['link']) . '" title="' . esc_attr($social_link['title']) . '"><i class="fa ' . esc_attr($social_link['icon']) . '"></i></a></li>';
                }
            }
            $output .= '</ul>';
        }else{
            $socials = explode("\n", $instance['custom_social']);
            $output .= '<ul class="iw-social-footer-all">';
            foreach($socials as $social){
                $social = explode("|", $social);
                $class = explode(".com", $social[1]);
                $class = str_replace(array('https://', 'http://'), '', $class[0]);
                if ($class == 'plus.google') {
                    $class = 'google-plus';
                }

                if(count($social) == 2){
                    $output .= '<li><a class="'.$class.'" href="' . esc_url($social[1]) . '"><i class="fa ' . esc_attr($social[0]) . '"></i></a></li>';
                }
            }
            $output .= '</ul>';
        }
        $output .= '</form></div>';
        echo (string)$output;

        // End show widget
        echo wp_kses_post($after_widget);
    }

}

function inwave_subscribe_widget() {
    register_widget('Inwave_Widget_Subscribe');
}
add_action('widgets_init', 'inwave_subscribe_widget');