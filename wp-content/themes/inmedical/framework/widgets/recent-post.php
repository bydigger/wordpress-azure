<?php
/** Widget recent post */
class Inwave_Recent_Posts_Widget extends WP_Widget_Recent_Posts {

    public function widget($args, $instance) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'widget_recent_posts', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo wp_kses_post($cache[ $args['widget_id'] ]);
            return;
        }

        ob_start();

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts', 'inmedical');

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        $r = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        ) ) );

        if ($r->have_posts()) :
            ?>
            <?php echo wp_kses_post($args['before_widget']); ?>
            <?php if ( $title ) {
            echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
        } ?>
            <ul class="recent-blog-posts recent-blog-posts-default">
                <?php while ( $r->have_posts() ) : $r->the_post();
				
					$img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
					$img_src = count($img) ? $img[0] : '';
					if ($img_src){
						$img_src = inwave_resize($img_src, 75, 75, true);
					}					
                    $thumb = get_the_post_thumbnail();
                ?>
                    <li class="recent-blog-post">
						<?php if($thumb):?>
                            <a class="recent-blog-post-thumnail" href="<?php echo esc_url(get_the_permalink()); ?>">
								<img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
							</a>
                        <?php endif?>
                        <div class="recent-blog-post-detail">
                            <h3 class="recent-blog-post-title"><a class="theme-color-hover" href="<?php echo esc_url(get_the_permalink()); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a></h3>
                            <?php if ( $show_date ) : ?>
                                <div class="post-date"><?php the_date('d.m.Y'); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="clearfix"></div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php echo wp_kses_post($args['after_widget']); ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
        } else {
            ob_end_flush();
        }
    }

}

function inwave_recent_post_widget() {
    unregister_widget('Widget_Recent_Posts');
    register_widget('Inwave_Recent_Posts_Widget');
}
add_action('widgets_init', 'inwave_recent_post_widget');