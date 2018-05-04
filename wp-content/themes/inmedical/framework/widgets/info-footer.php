<?php
class Inwave_Contact_Info_Footer extends WP_Widget
{

    function __construct() {
        parent::__construct(
            'inwave-contact-info',
            esc_html__('Inwave Contact Information', 'inmedical'),
            array('description' => esc_html__('Widget display about us information in footer.', 'inmedical'))
        );
    }

    function widget($args, $instance) {
        extract($args);
        // widget content
        $logo_img = Inwave_Helper::getPostOption('image-information');
        $title = apply_filters( 'widget_title', $instance['title'] );
        $description = empty( $instance['description'] ) ? '' : $instance['description'];
        $images = empty( $instance['image_uri'] ) ? '' : $instance['image_uri'];
        $link = empty( $instance['link'] ) ? '' : $instance['link'];
        $text_link = empty( $instance['text_link'] ) ? '' : $instance['text_link'];
        $no_title = '';
        if (!$title) {
            $no_title = 'no-title';
        }

        echo wp_kses_post($before_widget);
        echo '<div class="widget-info-footer ' . $no_title .'">';
        echo wp_kses_post($before_title. $title .$after_title);
        ?>
            <?php if ($logo_img) {
                echo '<a class="iw-widget-about-us" href="' .esc_url(home_url('/')). '">
                    <img src="' .esc_url($logo_img). '" alt=""/>
                </a>';
            }
            elseif ($images) {
                echo '<a class="iw-widget-about-us" href="' .esc_url(home_url('/')). '">
                    <img src="' .esc_url($images). '" alt=""/>
                </a>';
            }
            ?>
            <p><?php echo esc_html($description); ?></p>
            <a class="link_page_about_us" href="<?php echo esc_url($link); ?>"><?php echo esc_html($text_link); ?> </a>

        <?php
        echo '</div>';
        echo wp_kses_post($after_widget);
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['image_uri'] = strip_tags($new_instance['image_uri']);
        $instance['link'] = strip_tags($new_instance['link']);
        $instance['text_link'] = strip_tags($new_instance['text_link']);

        return $instance;
    }
    function form($instance) {
        $default = array(
            'title'         => 'Title',
            'description'   => '',
            'image_uri'     => '',
            'link'          => '',
            'text_link'          => ''
        );
        $instance = wp_parse_args((array)$instance, $default);
        $title = esc_attr($instance['title']);
        $description = esc_attr($instance['description']);
        $image_uri = esc_attr($instance['image_uri']);
        $link = esc_attr($instance['link']);
        $text_link = esc_attr($instance['text_link']);
        ?>

        <p> <?php esc_html_e('Title', 'inmedical'); ?><input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>"/></p>
        <p> <?php esc_html_e('Description', 'inmedical'); ?><textarea class="widefat" rows="5" cols="10" class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?> "name="<?php echo esc_attr($this->get_field_name('description'));?>"> <?php echo esc_html($description) ?> </textarea></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('image_uri')); ?>"><?php esc_html_e('Image', 'inmedical'); ?></label><br/>

            <?php
            if ($instance['image_uri'] != '') :
                echo '<img class="custom_media_image" src="' . esc_attr($image_uri) . '" /><br />';
            endif;
            ?>

            <input type="text" class="widefat custom_media_url"
                   name="<?php echo esc_attr($this->get_field_name('image_uri')); ?>"
                   id="<?php echo esc_attr($this->get_field_id('image_uri')); ?>"
                   value="<?php echo esc_attr($instance['image_uri']); ?>">

            <input type="button" class="button button-primary custom_media_button" id="custom_media_button"
                   name="<?php echo esc_attr($this->get_field_name('image_uri')); ?>" value="<?php esc_html_e('Upload Image', 'inmedical'); ?>"/>
        </p>
        <p> <?php esc_html_e('Action URL', 'inmedical'); ?><input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('link')); ?>" name="<?php echo esc_attr($this->get_field_name('link')); ?>" value="<?php echo esc_attr($link);?>"/></p>
        <p> <?php esc_html_e('Link Text', 'inmedical'); ?><input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('text_link')); ?> " name="<?php echo esc_attr($this->get_field_name('text_link'));?>" value="<?php echo esc_attr($text_link);?>"/></p>
        <?php
    }
}
function inwave_contact_info_widget() {
    register_widget('Inwave_Contact_Info_Footer');
}
add_action('widgets_init', 'inwave_contact_info_widget');