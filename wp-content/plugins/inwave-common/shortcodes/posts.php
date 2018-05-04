<?php
/*
 * @package Inwave Athlete
 * @version 1.0.0
 * @created Mar 27, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of wp_posts
 *
 * @Developer duongca
 */
if (!class_exists('Inwave_Posts')) {

    class Inwave_Posts extends Inwave_Shortcode{

        protected $name = 'inwave_posts';

        function init_params() {
            $_categories = get_categories();
            $cats = array(__("All", "inwavethemes") => '');
            foreach ($_categories as $cat) {
                $cats[$cat->name] = $cat->term_id;
            }

            return array(
                'name' => __('Posts', 'inwavethemes'),
                'description' => __('Display a list of posts ', 'inwavethemes'),
                'base' => $this->name,
                'icon' => 'iw-default',
                'category' => 'Custom',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "admin_label" => true,
                        "heading" => __("Style", "inwavethemes"),
                        "param_name" => "style",
                        "value" => array(
                            'Style 1' => 'style1',
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_style1",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/posts-style1.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Post Ids", "inwavethemes"),
                        "value" => "",
                        "param_name" => "post_ids",
                        "description" => __('Id of posts you want to get. Separated by commas.', "inwavethemes")
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Post Category", "inwavethemes"),
                        "param_name" => "category",
                        "value" => $cats,
                        "description" => __('Category to get posts.', "inwavethemes")
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Post per page", "inwavethemes"),
                        "param_name" => "post_number",
                        "value" => "3",
						"admin_label" => true,
                        "description" => __('Number of posts to display on box.', "inwavethemes")
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => "Number column",
                        "param_name" => "number_column",
                        "value" => array(
                            "1" => "1",
                            "2" => "2",
                            "3" => "3",
                            "4" => "4"
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Order By", "inwavethemes"),
                        "param_name" => "order_by",
                        "value" => array(
                            'ID' => 'ID',
                            'Title' => 'title',
                            'Date' => 'date',
                            'Modified' => 'modified',
                            'Ordering' => 'menu_order',
                            'Random' => 'rand'
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Order Type", "inwavethemes"),
                        "param_name" => "order_type",
                        "value" => array(
                            'ASC' => 'ASC',
                            'DESC' => 'DESC'
                        ),
                    ),
					array(
                        "type" => "textfield",
                        "heading" => __("Number word of description", "inwavethemes"),
						'description' => __('Trim description. Exp: 15', 'inwavethemes'),
                        "param_name" => "number_desc",
                        "value" => '15',
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;
            $output = $post_ids = $category = $post_number = $number_column = $order_by = $order_type = $style = $number_desc = $class = '';
            extract(shortcode_atts(array(
                'title' => '',
                'post_ids' => '',
                'category' => '',
                'post_number' => 3,
                'number_column' => 3,
                'order_by' => 'ID',
                'order_type' => 'DESC',
                'style' => 'style1',
				'number_desc' => '15',
                'class' => ''
                            ), $atts));

            $args = array();
            if ($post_ids) {
                $args['post__in'] = explode(',', $post_ids);
            } else {
                if ($category) {
                    $args['category__in'] = $category;
                }
            }
            $args['posts_per_page'] = $post_number;
            $args['order'] = $order_type;
            $args['orderby'] = $order_by;
            $query = new WP_Query($args);
            $class .= ' '. $style;

            ob_start();
            switch ($style) {
                case 'style1':
					?>
					<div class="iw-posts <?php echo $class ?>">
                        <div class="iw-posts-list row">
							<?php
								while ($query->have_posts()) :
								$query->the_post();
									$post = get_post();
									$contents = $post->post_content;
									$img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
									$img_src = count($img) ? $img[0] : '';
									if(!$img_src){
										$img_src = inwave_get_placeholder_image();
									}
									$img_src = inwave_resize($img_src, 370, 255, true);
									?>
									<div class="col-md-<?php echo 12 / $number_column ?> col-sm-6 col-xs-12">
                                        <div class="post-item">
                                            <div class="post-image">
                                                <img src="<?php echo $img_src; ?>" alt="">
                                            </div>
                                            <div class="post-content">
                                                <div class="post-date">
                                                    <?php echo get_the_date("d F Y"); ?>
                                                </div>
                                                <h3 class="post-title iw-title-border">
                                                    <a class="theme-color-hover" href="<?php echo get_permalink(); ?>"><?php the_title() ?></a>
                                                </h3>
                                                <div class="post-description">
                                                    <?php
                                                    if($post->post_excerpt){
                                                        echo wp_trim_words(get_the_excerpt(), $number_desc);
                                                    } else {
                                                        echo strip_shortcodes(wp_trim_words( get_the_content(), $number_desc ));
                                                    }
                                                    ?>
                                                </div>
                                                <a class="read-more theme-color" href="<?php echo get_permalink(); ?>"><?php echo esc_html__('Read more', 'inwavethemes') ;?></a>
                                            </div>
                                        </div>
									</div>
									<?php
								endwhile;
								wp_reset_postdata();
								?>
                        </div>
					</div>
				<?php
                break;
			}
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Posts();