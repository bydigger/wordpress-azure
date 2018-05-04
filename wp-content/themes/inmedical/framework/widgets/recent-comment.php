<?php
/** Widget recent comment */
class Inwave_Recent_Comments_Widget extends WP_Widget_Recent_Comments
{
    public function widget($args, $instance)
    {
        global $comments, $comment;

        $cache = array();
        if (!$this->is_preview()) {
            $cache = wp_cache_get('widget_recent_comments', 'widget');
        }
        if (!is_array($cache)) {
            $cache = array();
        }

        if (!isset($args['widget_id']))
            $args['widget_id'] = $this->id;

        if (isset($cache[$args['widget_id']])) {
            echo wp_kses_post($cache[$args['widget_id']]);
            return;
        }

        $output = '';

        $title = (!empty($instance['title'])) ? $instance['title'] : esc_html__('Recent Comments','inmedical');

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number)
            $number = 5;

        /**
         * Filter the arguments for the Recent Comments widget.
         *
         * @since 3.4.0
         *
         * @see WP_Comment_Query::query() for information on accepted arguments.
         *
         * @param array $comment_args An array of arguments used to retrieve the recent comments.
         */
        $comments = get_comments(apply_filters('widget_comments_args', array(
            'number' => $number,
            'status' => 'approve',
            'post_status' => 'publish'
        )));

        $output .= $args['before_widget'];
        if ($title) {
            $output .= $args['before_title'] . $title . $args['after_title'];
        }

        $output .= '<ul id="recentcomments">';
        if ($comments) {
            // Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
            $post_ids = array_unique(wp_list_pluck($comments, 'comment_post_ID'));
            _prime_post_caches($post_ids, strpos(get_option('permalink_structure'), '%category%'), false);

            foreach ((array)$comments as $comment) {
                $output .= '<li class="recentcomments">';
                /* translators: comments widget: 1: comment author, 2: post link */
                $output .= sprintf(esc_html_x('%1$s %2$s', 'widgets','inmedical'),
                    '<a class="recentcomment-title" href="' . esc_url(get_comment_link($comment->comment_ID)) . '">' . get_the_title($comment->comment_post_ID) . '</a>',
                    '<div class="comment-author-link"><i class="fa fa-comments"></i>' . get_comment_author_link() . '</div>'
                );
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        $output .= $args['after_widget'];

        echo wp_kses_post($output);

        if (!$this->is_preview()) {
            $cache[$args['widget_id']] = $output;
            wp_cache_set('widget_recent_comments', $cache, 'widget');
        }
    }
}

function inwave_recent_comment_widget() {
    register_widget('Inwave_Recent_Comments_Widget');
}
add_action('widgets_init', 'inwave_recent_comment_widget');