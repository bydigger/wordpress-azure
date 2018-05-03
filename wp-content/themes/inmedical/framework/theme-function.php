<?php
/**
 * Theme function file.
 */

/**
 * Add class to nav menu
 */

if (!is_admin()) {
    function inwave_nav_class($classes, $item)
    {
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'selected active ';
        }
        return $classes;
    }

    add_filter('nav_menu_css_class', 'inwave_nav_class', 10, 2);
}

/* add body class: support white color and boxed layout */
if(!function_exists('inwave_add_body_class')){
    function inwave_add_body_class($classes){

        $page_class = Inwave_Helper::getPostOption('page_class');
        if($page_class){
            $classes[] = $page_class;
        }

        $use_scroll_footer = Inwave_Helper::getPostOption('use_scroll_footer', 'use_scroll_footer');
        if($use_scroll_footer){
            $classes[] = 'page-template-home-page-scroll';
        }

        $layout = Inwave_Helper::getPanelSetting('layout');
        if($layout=='boxed'){
            $classes[] = 'body-boxed';
        }

        $classes[] = 'st-effect-3';

        return $classes;
    }

    add_filter( 'body_class', 'inwave_add_body_class');
}



if (!function_exists('inwave_comment')) {
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own inwave_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.

     */
    function inwave_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                <p><?php esc_html_e('Pingback:', 'inmedical'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(esc_html__('(Edit)', 'inmedical'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                // Proceed with normal comments.
                global $post;
                ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <div id="comment-<?php comment_ID(); ?>" class="comment answer">
                    <div class="commentAvt commentLeft">
                        <?php echo get_avatar(get_comment_author_email() ? get_comment_author_email() : $comment, 91); ?>
                    </div>
                    <!-- .comment-meta -->
                    <div class="commentRight">
                        <div class="content-cmt">
                            <div class="comment-head-info">
								<span class="name-cmt"><?php echo get_comment_author_link() ?></span>
								<span class="date-cmt"><?php echo esc_html__('Posted ', 'inmedical'); ?> <?php printf(esc_html__('%s in %s', 'inmedical'), get_comment_date('d.m.Y'),get_comment_time('h:i')) ?>. </span>
								<div class="comment_reply"><?php comment_reply_link(array_merge($args, array('reply_text' => esc_html__('Reply', 'inmedical'), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth']))); edit_comment_link(esc_html__('Edit', 'inmedical'), '<span class="edit-link">', '</span>'); ?></div>
								
                            </div>
                            <div class="content-reply">
                                <?php comment_text(); ?>
								<?php if ('0' == $comment->comment_approved) : ?>
									<p class="comment-awaiting-moderation theme-color"><?php esc_html_e('Your comment is awaiting moderation.', 'inmedical'); ?></p>
								<?php endif; ?>
								
                            </div>
                        </div>
                    </div>
					
                    <!-- .comment-content -->
                   
                    <div class="clearfix"></div>
                </div>
                <!-- #comment-## -->
                <?php
                break;
        endswitch; // end comment_type check
    }

}

if (!function_exists('inwave_getElementsByTag')) {

    /**
     * Function to get element by tag
     * @param string $tag tag name. Eg: embed, iframe...
     * @param string $content content to find
     * @param int $type type of tag. <br/> 1. [tag_name settings]content[/tag_name]. <br/>2. [tag_name settings]. <br/>3. HTML tags.
     * @return type
     */
    function inwave_getElementsByTag($tag, $content, $type = 1)
    {
        if ($type == 1) {
            $pattern = "/\[$tag(.*)\](.*)\[\/$tag\]/Uis";
        } elseif ($type == 2) {
            $pattern = "/\[$tag(.*)\]/Uis";
        } elseif ($type == 3) {
            $pattern = "/\<$tag(.*)\>(.*)\<\/$tag\>/Uis";
        } else {
            $pattern = null;
        }
        $find = null;
        if ($pattern) {
            preg_match($pattern, $content, $matches);
            if ($matches) {
                $find = $matches;
            }
        }
        return $find;
    }

}


if (!function_exists('inwave_social_sharing')) {

    /**
     *
     * @global type $inwave_theme_option
     * @param String $link Link to share
     * @param String $text the text content to share
     * @param String $title the title to share
     * @param String $tag the wrap html tag
     */
    function inwave_social_sharing($link, $text, $title, $tag = '')
    {
        global $inwave_theme_option;
        $newWindow = 'onclick="return iwOpenWindow(this.href);"';
        $title = urlencode($title);
        $text = '';
        if(is_single() && has_excerpt()){
            $text = Inwave_Helper::substrword(get_the_excerpt(), 15);
        }
        $text = urlencode($text);
        $link = urlencode($link);
        $html = '';
        if ($inwave_theme_option['sharing_facebook']) {
            $shareLink = 'https://www.facebook.com/sharer.php?s=100&amp;t=' . $title . '&amp;u='. $link;
            if(is_single() && has_post_thumbnail()){
                $thumb_id = get_post_thumbnail_id();
                $thumb_url = wp_get_attachment_image_src($thumb_id,'large', true);
                $shareLink .= '&amp;picture='.$thumb_url[0];
            }
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-fb" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Facebook','title','inmedical') . '" ' . $newWindow . '><i class="fa fa-facebook"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if ($inwave_theme_option['sharing_twitter']) {
            $shareLink = 'https://twitter.com/share?url=' . $link . '&amp;text=' . $text;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-tt" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Twitter','title','inmedical') . '" ' . $newWindow . '><i class="fa fa-twitter"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if ($inwave_theme_option['sharing_linkedin']) {
            $shareLink = 'https://www.linkedin.com/shareArticle?mini=true&amp;url=' . $link . '&amp;title=' . $title . '&amp;summary=' . $text;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-linkedin" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Linkedin','title','inmedical') . '" ' . $newWindow . '><i class="fa fa-linkedin"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if ($inwave_theme_option['sharing_google']) {
            $shareLink = 'https://plus.google.com/share?url=' . $link . '&amp;title=' . $title;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-gg" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Google Plus','title','inmedical') . '" ' . $newWindow . '><i class="fa fa-google-plus"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if ($inwave_theme_option['sharing_tumblr']) {
            $shareLink = 'http://www.tumblr.com/share/link?url=' . $link . '&amp;description=' . $text . '&amp;name=' . $title;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-tumblr" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Tumblr','title','inmedical') . '" ' . $newWindow . '><i class="fa fa-tumblr-square"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if ($inwave_theme_option['sharing_pinterest']) {
            $shareLink = 'http://pinterest.com/pin/create/button/?url=' . $link . '&amp;description=' . $text . '&amp;media=' . $link;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-pinterest" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Pinterest','title', 'inmedical') . '" ' . $newWindow . '><i class="fa fa-pinterest"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if ($inwave_theme_option['sharing_email']) {
            $shareLink = 'mailto:?subject=' . esc_attr_x('I wanted you to see this site','title', 'inmedical') . '&amp;body=' . $link . '&amp;title=' . $title;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-email" href="' . urlencode(esc_attr($shareLink)) . '" title="' . esc_attr_x('Email','title', 'inmedical') . '"><i class="fa fa-envelope"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }

        echo (string)$html;
    }
}

if (!function_exists('inwave_social_sharing_category_listing')) {

    /**
     *
     * @global type $inwave_theme_option
     * @param String $link Link to share
     * @param String $text the text content to share
     * @param String $title the title to share
     * @param String $tag the wrap html tag
     */
    function inwave_social_sharing_category_listing($link, $text, $title, $tag = '')
    {
        global $inwave_theme_option;
        $newWindow = 'onclick="return iwOpenWindow(this.href);"';
        $title = urlencode($title);
        $text = urlencode($text);
        $link = urlencode($link);
        if ($inwave_theme_option['sharing_facebook']) {
            $shareLink = 'https://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $title . '&amp;p[url]=' . $link . '&amp;p[summary]=' . $text;
            echo ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-fb" target="_blank" href="#" title="' . esc_attr_x('Share on Facebook','title','inmedical') . '" onclick="return iwOpenWindow(\'' . esc_js($shareLink) . '\')"><i class="fa fa-facebook"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if ($inwave_theme_option['sharing_google']) {
            $shareLink = 'https://plus.google.com/share?url=' . $link . '&amp;title=' . $title;
            echo ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-gg" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Google Plus','title','inmedical') . '" ' . $newWindow . '><i class="fa fa-google-plus"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
		if ($inwave_theme_option['sharing_twitter']) {
            $shareLink = 'https://twitter.com/share?url=' . $link . '&amp;text=' . $text;
            echo ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-tt" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Twitter','title','inmedical') . '" ' . $newWindow . '><i class="fa fa-twitter"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
    }
}

if (!function_exists('inwave_get_social_link')) {
    function inwave_get_social_link()
    {
        global $inwave_theme_option;

        $html = '<ul class="iw-social-all">';
        if(isset($inwave_theme_option['social_links']) && count($inwave_theme_option['social_links']) > 1){
            $social_links = $inwave_theme_option['social_links'];
            unset($social_links[0]);
            foreach($social_links as $social_link){
                $html .= '<li><a href="'.esc_url($social_link['link']).'" title="'.esc_attr($social_link['title']).'"><i class="fa '.esc_attr($social_link['icon']).'"></i></a></li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }
}

if (!function_exists('inwave_get_class')) {
    function inwave_get_classes($type,$sidebar)
    {
        $classes = '';
        switch ($type) {
            case 'container':
                $classes = 'col-sm-12 col-xs-12';
                if ($sidebar == 'left' || $sidebar == 'right') {
                    $classes .= ' col-lg-9 col-md-8';
                    if ($sidebar == 'left') {
                        $classes .= ' pull-right';
                    }
                }
                break;
            case 'sidebar':
                $classes = 'col-sm-12 col-xs-12';
                if ($sidebar == 'left' || $sidebar == 'right') {
                    $classes .= ' col-lg-3 col-md-4';
                }
                if ($sidebar == 'bottom') {
                    $classes .= ' pull-' . $sidebar;
                }
                break;
        }
        return $classes;
    }
}

if (!function_exists('inwave_allow_tags')) {

    function inwave_allow_tags($tag = null)
    {
        $inwave_tag_allowed = wp_kses_allowed_html('post');

        $inwave_tag_allowed['input'] = array(
            'class' => array(),
            'id' => array(),
            'name' => array(),
            'value' => array(),
            'checked' => array(),
            'type' => array()
        );
        $inwave_tag_allowed['select'] = array(
            'class' => array(),
            'id' => array(),
            'name' => array(),
            'value' => array(),
            'multiple' => array(),
            'type' => array()
        );
        $inwave_tag_allowed['option'] = array(
            'value' => array(),
            'selected' => array()
        );

        if($tag == null){
            return $inwave_tag_allowed;
        }
        elseif(is_array($tag)){
            $new_tag_allow = array();
            foreach ($tag as $_tag){
                $new_tag_allow[$_tag] = $inwave_tag_allowed[$_tag];
            }

            return $new_tag_allow;
        }
        else{
            return isset($inwave_tag_allowed[$tag]) ? array($tag=>$inwave_tag_allowed[$tag]) : array();
        }
    }
}

if (!function_exists('inwave_get_post_views')) {

    function inwave_get_post_views($postID){
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
        }
        return $count;
    }
}

if (!function_exists('inwave_social_sharing_fb')) {
    /**
     *
     * @global type $inwave_theme_option
     * @param String $link Link to share
     * @param String $text the text content to share
     * @param String $title the title to share
     * @param String $tag the wrap html tag
     */
    function inwave_social_sharing_fb($link, $text, $title)
    {
        $newWindow = 'onclick="return iwOpenWindow(this.href);"';
        $title = urlencode($title);
        $text = urlencode($text);
        $link = urlencode($link);
        $shareLink = 'https://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $title . '&amp;p[url]=' . $link . '&amp;p[summary]=' . $text;
        echo '<a class="share-buttons-fb" target="_blank" href="#" title="' . esc_attr_x('Share on Facebook','title', 'inmedical') . '" onclick="return iwOpenWindow(\'' . esc_js($shareLink) . '\')"><i class="fa fa-share"></i><span>share</span></a>';
    }
}

if(!function_exists('inwave_check_cart_url')){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    function inwave_check_cart_url(){
        if(!isset($cartUrl)){
            $cartUrl = '';
        }
        if(function_exists('WC')) {
            $cartUrl = wc_get_cart_url();
        }
        echo esc_url($cartUrl);
    }
}

if(!function_exists('inwave_count_product')){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    function inwave_count_product(){
        $count = 0;
        if(function_exists('WC')) {
            $count = WC()->cart->cart_contents_count;
        }
        return $count;
    }
}

/**
 * Count widgets function
 * https://gist.github.com/slobodan/6156076
 */
if(!function_exists('inwave_count_widgets')){
    function inwave_count_widgets($sidebar_id)
    {
        // If loading from front page, consult $_wp_sidebars_widgets rather than options
        // to see if wp_convert_widget_settings() has made manipulations in memory.
        global $_wp_sidebars_widgets;
        if (empty($_wp_sidebars_widgets)) :
            $_wp_sidebars_widgets = get_option('sidebars_widgets', array());
        endif;
        $sidebars_widgets_count = $_wp_sidebars_widgets;
        if (isset($sidebars_widgets_count[$sidebar_id])) :
            $widget_count = count($sidebars_widgets_count[$sidebar_id]);
            $widget_classes = 'widget-count-' . count($sidebars_widgets_count[$sidebar_id]);
            if ($widget_count >= 3) :
                // Four widgets er row if there are exactly four or more than 3
                $widget_classes .= ' col-md-4 col-sm-4';
            elseif ($widget_count == 2) :
                // Three widgets per row if there's three or more widgets
                $widget_classes .= ' col-md-6 col-sm-6';
            elseif ($widget_count == 1) :
                // Otherwise show two widgets per row
                $widget_classes .= ' col-md-12 col-sm-12';
            endif;
            $widget_classes .= ' col-xs-12';
            return $widget_classes;
        endif;
    }

    function inwave_breadcrumbs(){
        /* === OPTIONS === */
        $text['home']     = esc_html__('Home', 'inmedical'); // text for the 'Home' link
        $text['category'] = esc_html__('Archive by Category "%s"', 'inmedical'); // text for a category page
        $text['tax'] 	  = esc_html__('Archive for "%s"', 'inmedical'); // text for a taxonomy page
        $text['search']   = esc_html__('Search Results for "%s" Query', 'inmedical'); // text for a search results page
        $text['tag']      = esc_html__('Posts Tagged "%s"', 'inmedical'); // text for a tag page
        $text['author']   = esc_html__('Articles Posted by %s', 'inmedical'); // text for an author page
        $text['404']      = esc_html__('Error 404', 'inmedical'); // text for the 404 page

        $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
        $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
        $delimiter   = ''; // delimiter between crumbs
        $before      = '<li class="current">'; // tag before the current crumb
        $after       = '</li>'; // tag after the current crumb
        /* === END OF OPTIONS === */

        global $post;
        $homeLink = home_url('/');
        $linkBefore = '<li>';
        $linkAfter = '</li>';
        $linkAttr = '';
        $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

        if ( is_front_page()) {

            if ($showOnHome == 1) echo '<ul><li><a href="' . esc_url($homeLink) . '">' . $text['home'] . '</a></li></ul>';

        } else {

            echo '<ul><li><i class="fa fa-home"></i><a href="' . esc_url($homeLink) . '">' . $text['home'] . '</a></li>' . $delimiter;

            if(is_home()){
                echo wp_kses_post($before . get_the_title( get_option('page_for_posts', true) ) . $after);
            }elseif ( is_category() ) {
                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) {
                    $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo wp_kses_post($cats);
                }
                echo wp_kses_post($before . sprintf($text['category'], single_cat_title('', false)) . $after);

            } elseif( is_tax() ){
                if(is_tax('cat')){
                    $thisCat = get_category(get_query_var('cat'), false);
                    if ($thisCat->parent != 0) {
                        $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                        $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                        $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                        echo wp_kses_post($cats);
                    }
                    echo wp_kses_post($before . sprintf($text['tax'], single_cat_title('', false)) . $after);
                }
                else
                {
                    global $wp_query;
                    $taxonomy = $wp_query->query_vars['taxonomy'];
                    $thisCat = get_term_by('slug', get_query_var($taxonomy) , $taxonomy);
                    if ($thisCat->parent != 0) {
                        $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                        $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                        $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                        echo wp_kses_post($cats);
                    }
                    echo wp_kses_post($before . single_cat_title('', false) . $after);
                }
            }elseif ( is_search() ) {
                echo wp_kses_post($before . sprintf($text['search'], get_search_query()) . $after);

            } elseif ( is_day() ) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
                echo wp_kses_post($before . get_the_time('d') . $after);

            } elseif ( is_month() ) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                echo wp_kses_post($before . get_the_time('F') . $after);

            } elseif ( is_year() ) {
                echo wp_kses_post($before . get_the_time('Y') . $after);

            } elseif ( is_single() && !is_attachment() ) {
                if ( get_post_type() != 'post' ) {
                    if ($showCurrent == 1) echo wp_kses_post($delimiter . $before . get_the_title() . $after);
                } else {
                    $cat = get_the_category(); $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo wp_kses_post($cats);
                    if ($showCurrent == 1) echo wp_kses_post($before . get_the_title() . $after);
                }

            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                echo wp_kses_post($before . $post_type->labels->singular_name . $after);

            } elseif ( is_attachment() ) {
                $parent = get_post($post->post_parent);
                $cat = get_the_category($parent->ID); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo wp_kses_post($cats);
                printf($link, get_permalink($parent), $parent->post_title);
                if ($showCurrent == 1) echo wp_kses_post($delimiter . $before . get_the_title() . $after);

            } elseif ( is_page() && !$post->post_parent ) {
                if ($showCurrent == 1) echo wp_kses_post($before . get_the_title() . $after);

            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    $parent_id  = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo wp_kses_post($breadcrumbs[$i]);
                    if ($i != count($breadcrumbs)-1) echo wp_kses_post($delimiter);
                }
                if ($showCurrent == 1) echo wp_kses_post($delimiter . $before . get_the_title() . $after);

            } elseif ( is_tag() ) {
                echo wp_kses_post($before . sprintf($text['tag'], single_tag_title('', false)) . $after);

            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);
                echo wp_kses_post($before . sprintf($text['author'], $userdata->display_name) . $after);

            } elseif ( is_404() ) {
                echo wp_kses_post($before . $text['404'] . $after);
            }

            if ( get_query_var('paged') ) {
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
                echo esc_html(__('Page', 'inmedical')) . ' ' . get_query_var('paged');
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
            }

            echo '</ul>';

        }
    }
}

if(!function_exists('inwave_get_googlefonts')){
    function inwave_get_googlefonts($flip = true){
        $fonts = array(
            "" => "Select Font",
            "ABeeZee" => "ABeeZee",
            "Abel" => "Abel",
            "Abril Fatface" => "Abril Fatface",
            "Aclonica" => "Aclonica",
            "Acme" => "Acme",
            "Actor" => "Actor",
            "Adamina" => "Adamina",
            "Advent Pro" => "Advent Pro",
            "Aguafina Script" => "Aguafina Script",
            "Akronim" => "Akronim",
            "Aladin" => "Aladin",
            "Aldrich" => "Aldrich",
            "Alef" => "Alef",
            "Alegreya" => "Alegreya",
            "Alegreya SC" => "Alegreya SC",
            "Alegreya Sans" => "Alegreya Sans",
            "Alegreya Sans SC" => "Alegreya Sans SC",
            "Alex Brush" => "Alex Brush",
            "Alfa Slab One" => "Alfa Slab One",
            "Alice" => "Alice",
            "Alike" => "Alike",
            "Alike Angular" => "Alike Angular",
            "Allan" => "Allan",
            "Allerta" => "Allerta",
            "Allerta Stencil" => "Allerta Stencil",
            "Allura" => "Allura",
            "Almendra" => "Almendra",
            "Almendra Display" => "Almendra Display",
            "Almendra SC" => "Almendra SC",
            "Amarante" => "Amarante",
            "Amaranth" => "Amaranth",
            "Amatic SC" => "Amatic SC",
            "Amethysta" => "Amethysta",
            "Anaheim" => "Anaheim",
            "Andada" => "Andada",
            "Andika" => "Andika",
            "Angkor" => "Angkor",
            "Annie Use Your Telescope" => "Annie Use Your Telescope",
            "Anonymous Pro" => "Anonymous Pro",
            "Antic" => "Antic",
            "Antic Didone" => "Antic Didone",
            "Antic Slab" => "Antic Slab",
            "Anton" => "Anton",
            "Arapey" => "Arapey",
            "Arbutus" => "Arbutus",
            "Arbutus Slab" => "Arbutus Slab",
            "Architects Daughter" => "Architects Daughter",
            "Archivo Black" => "Archivo Black",
            "Archivo Narrow" => "Archivo Narrow",
            "Arimo" => "Arimo",
            "Arizonia" => "Arizonia",
            "Armata" => "Armata",
            "Artifika" => "Artifika",
            "Arvo" => "Arvo",
            "Asap" => "Asap",
            "Asset" => "Asset",
            "Astloch" => "Astloch",
            "Asul" => "Asul",
            "Atomic Age" => "Atomic Age",
            "Aubrey" => "Aubrey",
            "Audiowide" => "Audiowide",
            "Autour One" => "Autour One",
            "Average" => "Average",
            "Average Sans" => "Average Sans",
            "Averia Gruesa Libre" => "Averia Gruesa Libre",
            "Averia Libre" => "Averia Libre",
            "Averia Sans Libre" => "Averia Sans Libre",
            "Averia Serif Libre" => "Averia Serif Libre",
            "Bad Script" => "Bad Script",
            "Balthazar" => "Balthazar",
            "Bangers" => "Bangers",
            "Basic" => "Basic",
            "Battambang" => "Battambang",
            "Baumans" => "Baumans",
            "Bayon" => "Bayon",
            "Belgrano" => "Belgrano",
            "Belleza" => "Belleza",
            "BenchNine" => "BenchNine",
            "Bentham" => "Bentham",
            "Berkshire Swash" => "Berkshire Swash",
            "Bevan" => "Bevan",
            "Bigelow Rules" => "Bigelow Rules",
            "Bigshot One" => "Bigshot One",
            "Bilbo" => "Bilbo",
            "Bilbo Swash Caps" => "Bilbo Swash Caps",
            "Bitter" => "Bitter",
            "Black Ops One" => "Black Ops One",
            "Bokor" => "Bokor",
            "Bonbon" => "Bonbon",
            "Boogaloo" => "Boogaloo",
            "Bowlby One" => "Bowlby One",
            "Bowlby One SC" => "Bowlby One SC",
            "Brawler" => "Brawler",
            "Bree Serif" => "Bree Serif",
            "Bubblegum Sans" => "Bubblegum Sans",
            "Bubbler One" => "Bubbler One",
            "Buda" => "Buda",
            "Buenard" => "Buenard",
            "Butcherman" => "Butcherman",
            "Butterfly Kids" => "Butterfly Kids",
            "Cabin" => "Cabin",
            "Cabin Condensed" => "Cabin Condensed",
            "Cabin Sketch" => "Cabin Sketch",
            "Caesar Dressing" => "Caesar Dressing",
            "Cagliostro" => "Cagliostro",
            "Calligraffitti" => "Calligraffitti",
            "Cambo" => "Cambo",
            "Candal" => "Candal",
            "Cantarell" => "Cantarell",
            "Cantata One" => "Cantata One",
            "Cantora One" => "Cantora One",
            "Capriola" => "Capriola",
            "Cardo" => "Cardo",
            "Carme" => "Carme",
            "Carrois Gothic" => "Carrois Gothic",
            "Carrois Gothic SC" => "Carrois Gothic SC",
            "Carter One" => "Carter One",
            "Caudex" => "Caudex",
            "Cedarville Cursive" => "Cedarville Cursive",
            "Ceviche One" => "Ceviche One",
            "Changa One" => "Changa One",
            "Chango" => "Chango",
            "Chau Philomene One" => "Chau Philomene One",
            "Chela One" => "Chela One",
            "Chelsea Market" => "Chelsea Market",
            "Chenla" => "Chenla",
            "Cherry Cream Soda" => "Cherry Cream Soda",
            "Cherry Swash" => "Cherry Swash",
            "Chewy" => "Chewy",
            "Chicle" => "Chicle",
            "Chivo" => "Chivo",
            "Cinzel" => "Cinzel",
            "Cinzel Decorative" => "Cinzel Decorative",
            "Clicker Script" => "Clicker Script",
            "Coda" => "Coda",
            "Coda Caption" => "Coda Caption",
            "Codystar" => "Codystar",
            "Combo" => "Combo",
            "Comfortaa" => "Comfortaa",
            "Coming Soon" => "Coming Soon",
            "Concert One" => "Concert One",
            "Condiment" => "Condiment",
            "Content" => "Content",
            "Contrail One" => "Contrail One",
            "Convergence" => "Convergence",
            "Cookie" => "Cookie",
            "Copse" => "Copse",
            "Corben" => "Corben",
            "Courgette" => "Courgette",
            "Cousine" => "Cousine",
            "Coustard" => "Coustard",
            "Covered By Your Grace" => "Covered By Your Grace",
            "Crafty Girls" => "Crafty Girls",
            "Creepster" => "Creepster",
            "Crete Round" => "Crete Round",
            "Crimson Text" => "Crimson Text",
            "Croissant One" => "Croissant One",
            "Crushed" => "Crushed",
            "Cuprum" => "Cuprum",
            "Cutive" => "Cutive",
            "Cutive Mono" => "Cutive Mono",
            "Damion" => "Damion",
            "Dancing Script" => "Dancing Script",
            "Dangrek" => "Dangrek",
            "Dawning of a New Day" => "Dawning of a New Day",
            "Days One" => "Days One",
            "Delius" => "Delius",
            "Delius Swash Caps" => "Delius Swash Caps",
            "Delius Unicase" => "Delius Unicase",
            "Della Respira" => "Della Respira",
            "Denk One" => "Denk One",
            "Devonshire" => "Devonshire",
            "Didact Gothic" => "Didact Gothic",
            "Diplomata" => "Diplomata",
            "Diplomata SC" => "Diplomata SC",
            "Domine" => "Domine",
            "Donegal One" => "Donegal One",
            "Doppio One" => "Doppio One",
            "Dorsa" => "Dorsa",
            "Dosis" => "Dosis",
            "Dr Sugiyama" => "Dr Sugiyama",
            "Droid Sans" => "Droid Sans",
            "Droid Sans Mono" => "Droid Sans Mono",
            "Droid Serif" => "Droid Serif",
            "Duru Sans" => "Duru Sans",
            "Dynalight" => "Dynalight",
            "EB Garamond" => "EB Garamond",
            "Eagle Lake" => "Eagle Lake",
            "Eater" => "Eater",
            "Economica" => "Economica",
            "Electrolize" => "Electrolize",
            "Elsie" => "Elsie",
            "Elsie Swash Caps" => "Elsie Swash Caps",
            "Emblema One" => "Emblema One",
            "Emilys Candy" => "Emilys Candy",
            "Engagement" => "Engagement",
            "Englebert" => "Englebert",
            "Enriqueta" => "Enriqueta",
            "Erica One" => "Erica One",
            "Esteban" => "Esteban",
            "Euphoria Script" => "Euphoria Script",
            "Ewert" => "Ewert",
            "Exo" => "Exo",
            "Exo 2" => "Exo 2",
            "Expletus Sans" => "Expletus Sans",
            "Fanwood Text" => "Fanwood Text",
            "Fascinate" => "Fascinate",
            "Fascinate Inline" => "Fascinate Inline",
            "Faster One" => "Faster One",
            "Fasthand" => "Fasthand",
            "Fauna One" => "Fauna One",
            "Federant" => "Federant",
            "Federo" => "Federo",
            "Felipa" => "Felipa",
            "Fenix" => "Fenix",
            "Finger Paint" => "Finger Paint",
            "Fjalla One" => "Fjalla One",
            "Fjord One" => "Fjord One",
            "Flamenco" => "Flamenco",
            "Flavors" => "Flavors",
            "Fondamento" => "Fondamento",
            "Fontdiner Swanky" => "Fontdiner Swanky",
            "Forum" => "Forum",
            "Francois One" => "Francois One",
            "Freckle Face" => "Freckle Face",
            "Fredericka the Great" => "Fredericka the Great",
            "Fredoka One" => "Fredoka One",
            "Freehand" => "Freehand",
            "Fresca" => "Fresca",
            "Frijole" => "Frijole",
            "Fruktur" => "Fruktur",
            "Fugaz One" => "Fugaz One",
            "GFS Didot" => "GFS Didot",
            "GFS Neohellenic" => "GFS Neohellenic",
            "Gabriela" => "Gabriela",
            "Gafata" => "Gafata",
            "Galdeano" => "Galdeano",
            "Galindo" => "Galindo",
            "Gentium Basic" => "Gentium Basic",
            "Gentium Book Basic" => "Gentium Book Basic",
            "Geo" => "Geo",
            "Geostar" => "Geostar",
            "Geostar Fill" => "Geostar Fill",
            "Germania One" => "Germania One",
            "Gilda Display" => "Gilda Display",
            "Give You Glory" => "Give You Glory",
            "Glass Antiqua" => "Glass Antiqua",
            "Glegoo" => "Glegoo",
            "Gloria Hallelujah" => "Gloria Hallelujah",
            "Goblin One" => "Goblin One",
            "Gochi Hand" => "Gochi Hand",
            "Gorditas" => "Gorditas",
            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
            "Graduate" => "Graduate",
            "Grand Hotel" => "Grand Hotel",
            "Gravitas One" => "Gravitas One",
            "Great Vibes" => "Great Vibes",
            "Griffy" => "Griffy",
            "Gruppo" => "Gruppo",
            "Gudea" => "Gudea",
            "Habibi" => "Habibi",
			"Hind"=>"Hind",
            "Hammersmith One" => "Hammersmith One",
            "Hanalei" => "Hanalei",
            "Hanalei Fill" => "Hanalei Fill",
            "Handlee" => "Handlee",
            "Hanuman" => "Hanuman",
            "Happy Monkey" => "Happy Monkey",
            "Headland One" => "Headland One",
            "Henny Penny" => "Henny Penny",
            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
            "Holtwood One SC" => "Holtwood One SC",
            "Homemade Apple" => "Homemade Apple",
            "Homenaje" => "Homenaje",
            "IM Fell DW Pica" => "IM Fell DW Pica",
            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
            "IM Fell Double Pica" => "IM Fell Double Pica",
            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
            "IM Fell English" => "IM Fell English",
            "IM Fell English SC" => "IM Fell English SC",
            "IM Fell French Canon" => "IM Fell French Canon",
            "IM Fell French Canon SC" => "IM Fell French Canon SC",
            "IM Fell Great Primer" => "IM Fell Great Primer",
            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
            "Iceberg" => "Iceberg",
            "Iceland" => "Iceland",
            "Imprima" => "Imprima",
            "Inconsolata" => "Inconsolata",
            "Inder" => "Inder",
            "Indie Flower" => "Indie Flower",
            "Inika" => "Inika",
            "Irish Grover" => "Irish Grover",
            "Istok Web" => "Istok Web",
            "Italiana" => "Italiana",
            "Italianno" => "Italianno",
            "Jacques Francois" => "Jacques Francois",
            "Jacques Francois Shadow" => "Jacques Francois Shadow",
            "Jim Nightshade" => "Jim Nightshade",
            "Jockey One" => "Jockey One",
            "Jolly Lodger" => "Jolly Lodger",
            "Josefin Sans" => "Josefin Sans",
            "Josefin Slab" => "Josefin Slab",
            "Joti One" => "Joti One",
            "Judson" => "Judson",
            "Julee" => "Julee",
            "Julius Sans One" => "Julius Sans One",
            "Junge" => "Junge",
            "Jura" => "Jura",
            "Just Another Hand" => "Just Another Hand",
            "Just Me Again Down Here" => "Just Me Again Down Here",
            "Kameron" => "Kameron",
            "Kantumruy" => "Kantumruy",
            "Karla" => "Karla",
            "Kaushan Script" => "Kaushan Script",
            "Kavoon" => "Kavoon",
            "Kdam Thmor" => "Kdam Thmor",
            "Keania One" => "Keania One",
            "Kelly Slab" => "Kelly Slab",
            "Kenia" => "Kenia",
            "Khmer" => "Khmer",
            "Kite One" => "Kite One",
            "Knewave" => "Knewave",
            "Kotta One" => "Kotta One",
            "Koulen" => "Koulen",
            "Kranky" => "Kranky",
            "Kreon" => "Kreon",
            "Kristi" => "Kristi",
            "Krona One" => "Krona One",
            "La Belle Aurore" => "La Belle Aurore",
            "Lancelot" => "Lancelot",
            "Lato" => "Lato",
            "League Script" => "League Script",
            "Leckerli One" => "Leckerli One",
            "Ledger" => "Ledger",
            "Lekton" => "Lekton",
            "Lemon" => "Lemon",
            "Libre Baskerville" => "Libre Baskerville",
            "Life Savers" => "Life Savers",
            "Lilita One" => "Lilita One",
            "Lily Script One" => "Lily Script One",
            "Limelight" => "Limelight",
            "Linden Hill" => "Linden Hill",
            "Lobster" => "Lobster",
            "Lobster Two" => "Lobster Two",
            "Londrina Outline" => "Londrina Outline",
            "Londrina Shadow" => "Londrina Shadow",
            "Londrina Sketch" => "Londrina Sketch",
            "Londrina Solid" => "Londrina Solid",
            "Lora" => "Lora",
            "Love Ya Like A Sister" => "Love Ya Like A Sister",
            "Loved by the King" => "Loved by the King",
            "Lovers Quarrel" => "Lovers Quarrel",
            "Luckiest Guy" => "Luckiest Guy",
            "Lusitana" => "Lusitana",
            "Lustria" => "Lustria",
            "Macondo" => "Macondo",
            "Macondo Swash Caps" => "Macondo Swash Caps",
            "Magra" => "Magra",
            "Maiden Orange" => "Maiden Orange",
            "Mako" => "Mako",
            "Marcellus" => "Marcellus",
            "Marcellus SC" => "Marcellus SC",
            "Marck Script" => "Marck Script",
            "Margarine" => "Margarine",
            "Marko One" => "Marko One",
            "Marmelad" => "Marmelad",
            "Marvel" => "Marvel",
            "Mate" => "Mate",
            "Mate SC" => "Mate SC",
            "Maven Pro" => "Maven Pro",
            "McLaren" => "McLaren",
            "Meddon" => "Meddon",
            "MedievalSharp" => "MedievalSharp",
            "Medula One" => "Medula One",
            "Megrim" => "Megrim",
            "Meie Script" => "Meie Script",
            "Merienda" => "Merienda",
            "Merienda One" => "Merienda One",
            "Merriweather" => "Merriweather",
            "Merriweather Sans" => "Merriweather Sans",
            "Metal" => "Metal",
            "Metal Mania" => "Metal Mania",
            "Metamorphous" => "Metamorphous",
            "Metrophobic" => "Metrophobic",
            "Michroma" => "Michroma",
            "Milonga" => "Milonga",
            "Miltonian" => "Miltonian",
            "Miltonian Tattoo" => "Miltonian Tattoo",
            "Miniver" => "Miniver",
            "Miss Fajardose" => "Miss Fajardose",
            "Modern Antiqua" => "Modern Antiqua",
            "Molengo" => "Molengo",
            "Molle" => "Molle",
            "Monda" => "Monda",
            "Monofett" => "Monofett",
            "Monoton" => "Monoton",
            "Monsieur La Doulaise" => "Monsieur La Doulaise",
            "Montaga" => "Montaga",
            "Montez" => "Montez",
            "Montserrat" => "Montserrat",
            "Montserrat Alternates" => "Montserrat Alternates",
            "Montserrat Subrayada" => "Montserrat Subrayada",
            "Moul" => "Moul",
            "Moulpali" => "Moulpali",
            "Mountains of Christmas" => "Mountains of Christmas",
            "Mouse Memoirs" => "Mouse Memoirs",
            "Mr Bedfort" => "Mr Bedfort",
            "Mr Dafoe" => "Mr Dafoe",
            "Mr De Haviland" => "Mr De Haviland",
            "Mrs Saint Delafield" => "Mrs Saint Delafield",
            "Mrs Sheppards" => "Mrs Sheppards",
            "Muli" => "Muli",
            "Mystery Quest" => "Mystery Quest",
            "Neucha" => "Neucha",
            "Neuton" => "Neuton",
            "New Rocker" => "New Rocker",
            "News Cycle" => "News Cycle",
            "Niconne" => "Niconne",
            "Nixie One" => "Nixie One",
            "Nobile" => "Nobile",
            "Nokora" => "Nokora",
            "Norican" => "Norican",
            "Nosifer" => "Nosifer",
            "Nothing You Could Do" => "Nothing You Could Do",
            "Noticia Text" => "Noticia Text",
            "Noto Sans" => "Noto Sans",
            "Noto Serif" => "Noto Serif",
            "Nova Cut" => "Nova Cut",
            "Nova Flat" => "Nova Flat",
            "Nova Mono" => "Nova Mono",
            "Nova Oval" => "Nova Oval",
            "Nova Round" => "Nova Round",
            "Nova Script" => "Nova Script",
            "Nova Slim" => "Nova Slim",
            "Nova Square" => "Nova Square",
            "Numans" => "Numans",
            "Nunito" => "Nunito",
            "Odor Mean Chey" => "Odor Mean Chey",
            "Offside" => "Offside",
            "Old Standard TT" => "Old Standard TT",
            "Oldenburg" => "Oldenburg",
            "Oleo Script" => "Oleo Script",
            "Oleo Script Swash Caps" => "Oleo Script Swash Caps",
            "Open Sans" => "Open Sans",
            "Open Sans Condensed" => "Open Sans Condensed",
            "Oranienbaum" => "Oranienbaum",
            "Orbitron" => "Orbitron",
            "Oregano" => "Oregano",
            "Orienta" => "Orienta",
            "Original Surfer" => "Original Surfer",
            "Oswald" => "Oswald",
            "Over the Rainbow" => "Over the Rainbow",
            "Overlock" => "Overlock",
            "Overlock SC" => "Overlock SC",
            "Ovo" => "Ovo",
            "Oxygen" => "Oxygen",
            "Oxygen Mono" => "Oxygen Mono",
            "PT Mono" => "PT Mono",
            "PT Sans" => "PT Sans",
            "PT Sans Caption" => "PT Sans Caption",
            "PT Sans Narrow" => "PT Sans Narrow",
            "PT Serif" => "PT Serif",
            "PT Serif Caption" => "PT Serif Caption",
            "Pacifico" => "Pacifico",
            "Paprika" => "Paprika",
            "Parisienne" => "Parisienne",
            "Passero One" => "Passero One",
            "Passion One" => "Passion One",
            "Pathway Gothic One" => "Pathway Gothic One",
            "Patrick Hand" => "Patrick Hand",
            "Patrick Hand SC" => "Patrick Hand SC",
            "Patua One" => "Patua One",
            "Paytone One" => "Paytone One",
            "Peralta" => "Peralta",
            "Permanent Marker" => "Permanent Marker",
            "Petit Formal Script" => "Petit Formal Script",
            "Petrona" => "Petrona",
            "Philosopher" => "Philosopher",
            "Piedra" => "Piedra",
            "Pinyon Script" => "Pinyon Script",
            "Pirata One" => "Pirata One",
            "Plaster" => "Plaster",
            "Play" => "Play",
            "Playball" => "Playball",
            "Playfair Display" => "Playfair Display",
            "Playfair Display SC" => "Playfair Display SC",
            "Podkova" => "Podkova",
            "Poiret One" => "Poiret One",
            "Poller One" => "Poller One",
            "Poly" => "Poly",
            "Pompiere" => "Pompiere",
			"Poppins" => "Poppins",
            "Pontano Sans" => "Pontano Sans",
            "Port Lligat Sans" => "Port Lligat Sans",
            "Port Lligat Slab" => "Port Lligat Slab",
            "Prata" => "Prata",
            "Preahvihear" => "Preahvihear",
            "Press Start 2P" => "Press Start 2P",
            "Princess Sofia" => "Princess Sofia",
            "Prociono" => "Prociono",
            "Prosto One" => "Prosto One",
            "Puritan" => "Puritan",
            "Purple Purse" => "Purple Purse",
            "Quando" => "Quando",
            "Quantico" => "Quantico",
            "Quattrocento" => "Quattrocento",
            "Quattrocento Sans" => "Quattrocento Sans",
            "Questrial" => "Questrial",
            "Quicksand" => "Quicksand",
            "Quintessential" => "Quintessential",
            "Qwigley" => "Qwigley",
            "Racing Sans One" => "Racing Sans One",
            "Radley" => "Radley",
            "Raleway" => "Raleway",
            "Raleway Dots" => "Raleway Dots",
            "Rambla" => "Rambla",
            "Rammetto One" => "Rammetto One",
            "Ranchers" => "Ranchers",
            "Rancho" => "Rancho",
            "Rationale" => "Rationale",
            "Redressed" => "Redressed",
            "Reenie Beanie" => "Reenie Beanie",
            "Revalia" => "Revalia",
            "Ribeye" => "Ribeye",
            "Ribeye Marrow" => "Ribeye Marrow",
            "Righteous" => "Righteous",
            "Risque" => "Risque",
            "Roboto" => "Roboto",
            "Roboto Condensed" => "Roboto Condensed",
            "Roboto Slab" => "Roboto Slab",
            "Rochester" => "Rochester",
            "Rock Salt" => "Rock Salt",
            "Rokkitt" => "Rokkitt",
            "Romanesco" => "Romanesco",
            "Ropa Sans" => "Ropa Sans",
            "Rosario" => "Rosario",
            "Rosarivo" => "Rosarivo",
            "Rouge Script" => "Rouge Script",
            "Rubik Mono One" => "Rubik Mono One",
            "Rubik One" => "Rubik One",
            "Ruda" => "Ruda",
            "Rufina" => "Rufina",
            "Ruge Boogie" => "Ruge Boogie",
            "Ruluko" => "Ruluko",
            "Rum Raisin" => "Rum Raisin",
            "Ruslan Display" => "Ruslan Display",
            "Russo One" => "Russo One",
            "Ruthie" => "Ruthie",
            "Rye" => "Rye",
            "Sacramento" => "Sacramento",
            "Sail" => "Sail",
            "Salsa" => "Salsa",
            "Sanchez" => "Sanchez",
            "Sancreek" => "Sancreek",
            "Sansita One" => "Sansita One",
            "Sarina" => "Sarina",
            "Satisfy" => "Satisfy",
            "Scada" => "Scada",
            "Schoolbell" => "Schoolbell",
            "Seaweed Script" => "Seaweed Script",
            "Sevillana" => "Sevillana",
            "Seymour One" => "Seymour One",
            "Shadows Into Light" => "Shadows Into Light",
            "Shadows Into Light Two" => "Shadows Into Light Two",
            "Shanti" => "Shanti",
            "Share" => "Share",
            "Share Tech" => "Share Tech",
            "Share Tech Mono" => "Share Tech Mono",
            "Shojumaru" => "Shojumaru",
            "Short Stack" => "Short Stack",
            "Siemreap" => "Siemreap",
            "Sigmar One" => "Sigmar One",
            "Signika" => "Signika",
            "Signika Negative" => "Signika Negative",
            "Simonetta" => "Simonetta",
            "Sintony" => "Sintony",
            "Sirin Stencil" => "Sirin Stencil",
            "Six Caps" => "Six Caps",
            "Skranji" => "Skranji",
            "Slackey" => "Slackey",
            "Smokum" => "Smokum",
            "Smythe" => "Smythe",
            "Sniglet" => "Sniglet",
            "Snippet" => "Snippet",
            "Snowburst One" => "Snowburst One",
            "Sofadi One" => "Sofadi One",
            "Sofia" => "Sofia",
            "Sonsie One" => "Sonsie One",
            "Sorts Mill Goudy" => "Sorts Mill Goudy",
            "Source Code Pro" => "Source Code Pro",
            "Source Sans Pro" => "Source Sans Pro",
            "Special Elite" => "Special Elite",
            "Spicy Rice" => "Spicy Rice",
            "Spinnaker" => "Spinnaker",
            "Spirax" => "Spirax",
            "Squada One" => "Squada One",
            "Stalemate" => "Stalemate",
            "Stalinist One" => "Stalinist One",
            "Stardos Stencil" => "Stardos Stencil",
            "Stint Ultra Condensed" => "Stint Ultra Condensed",
            "Stint Ultra Expanded" => "Stint Ultra Expanded",
            "Stoke" => "Stoke",
            "Strait" => "Strait",
            "Sue Ellen Francisco" => "Sue Ellen Francisco",
            "Sunshiney" => "Sunshiney",
            "Supermercado One" => "Supermercado One",
            "Suwannaphum" => "Suwannaphum",
            "Swanky and Moo Moo" => "Swanky and Moo Moo",
            "Syncopate" => "Syncopate",
            "Tangerine" => "Tangerine",
            "Taprom" => "Taprom",
            "Tauri" => "Tauri",
            "Telex" => "Telex",
            "Tenor Sans" => "Tenor Sans",
            "Text Me One" => "Text Me One",
            "The Girl Next Door" => "The Girl Next Door",
            "Tienne" => "Tienne",
            "Tinos" => "Tinos",
            "Titan One" => "Titan One",
            "Titillium Web" => "Titillium Web",
            "Trade Winds" => "Trade Winds",
            "Trocchi" => "Trocchi",
            "Trochut" => "Trochut",
            "Trykker" => "Trykker",
            "Tulpen One" => "Tulpen One",
            "Ubuntu" => "Ubuntu",
            "Ubuntu Condensed" => "Ubuntu Condensed",
            "Ubuntu Mono" => "Ubuntu Mono",
            "Ultra" => "Ultra",
            "Uncial Antiqua" => "Uncial Antiqua",
            "Underdog" => "Underdog",
            "Unica One" => "Unica One",
            "UnifrakturCook" => "UnifrakturCook",
            "UnifrakturMaguntia" => "UnifrakturMaguntia",
            "Unkempt" => "Unkempt",
            "Unlock" => "Unlock",
            "Unna" => "Unna",
            "VT323" => "VT323",
            "Vampiro One" => "Vampiro One",
            "Varela" => "Varela",
            "Varela Round" => "Varela Round",
            "Vast Shadow" => "Vast Shadow",
            "Vibur" => "Vibur",
            "Vidaloka" => "Vidaloka",
            "Viga" => "Viga",
            "Voces" => "Voces",
            "Volkhov" => "Volkhov",
            "Vollkorn" => "Vollkorn",
            "Voltaire" => "Voltaire",
            "Waiting for the Sunrise" => "Waiting for the Sunrise",
            "Wallpoet" => "Wallpoet",
            "Walter Turncoat" => "Walter Turncoat",
            "Warnes" => "Warnes",
            "Wellfleet" => "Wellfleet",
            "Wendy One" => "Wendy One",
            "Wire One" => "Wire One",
            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
            "Yellowtail" => "Yellowtail",
            "Yeseva One" => "Yeseva One",
            "Yesteryear" => "Yesteryear",
            "Zeyada" => "Zeyada",
        );

        if($flip){
            return array_flip($fonts);
        }
        else{
            return $fonts;
        }
    }
}

if(!function_exists('inwave_get_fonts_weight')){
    function inwave_get_fonts_weight(){
        return array(
            "Default" => "",
            "Extra Bold" => "900",
            "Bold" => "700",
            "Semi-Bold" => "600",
            "Medium" => "500",
            "Normal" => "400",
            "Light" => "300",
            "Thin" => "100"
        );
    }
}

if(!function_exists('inwave_get_text_transform')){
    function inwave_get_text_transform(){
        return array(
            "Default" => "",
            "Capitalize" => "capitalize",
            "Uppercase" => "uppercase",
            "None" => "none"
        );
    }
}


if(!function_exists('inwave_start_session')){
    add_action('init', 'inwave_start_session', 1);
    /**  Init session for theme  */
    function inwave_start_session()
    {
		global $smof_data;
        if (!session_id()  && !headers_sent()) {
            session_start();
        }
		if (!isset($_SESSION['product-category-layout']) || !$_SESSION['product-category-layout']) {
            $_SESSION['product-category-layout'] =  $smof_data['product_listing_layout'];
            if($_SESSION['product-category-layout']==''){
                $_SESSION['product-category-layout'] == 'col';
            }
        }
        if (isset($_REQUEST['category-layout']) && $_REQUEST['category-layout']) {
            $_SESSION['product-category-layout'] = $_REQUEST['category-layout'];
        }
    }
}

if(!function_exists('inwave_add_editor_styles')){
    function inwave_add_editor_styles() {
        add_editor_style();
    }
    add_action( 'admin_init', 'inwave_add_editor_styles' );
}

if(!function_exists('inwave_resize')) {
    function inwave_resize($url, $width, $height = null, $crop = null, $single = true)
    {
        //validate inputs
        if (!$url OR !$width) return false;

        //define upload path & dir
        $upload_info = wp_upload_dir();
        $upload_dir = $upload_info['basedir'];
        $upload_url = $upload_info['baseurl'];
        //check if $img_url is local
        if (strpos($url, $upload_url) === false){
            //define path of image
            $rel_path = str_replace(content_url(), '', $url);
            $img_path = WP_CONTENT_DIR  . $rel_path;
        }
        else
        {
            $rel_path = str_replace($upload_url, '', $url);
            $img_path = $upload_dir . $rel_path;
        }

        //check if img path exists, and is an image indeed
        if (!file_exists($img_path) OR !getimagesize($img_path)) return $url;

        //get image info
        $info = pathinfo($img_path);
        $ext = $info['extension'];
        list($orig_w, $orig_h) = getimagesize($img_path);

        //get image size after cropping
        $dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
        $dst_w = $dims[4];
        $dst_h = $dims[5];

        //use this to check if cropped image already exists, so we can return that instead
        $suffix = "{$dst_w}x{$dst_h}";
        $dst_rel_url = str_replace('.' . $ext, '', $url);
        $destfilename = "{$img_path}-{$suffix}.{$ext}";
        if (!$dst_h) {
            //can't resize, so return original url
            $img_url = $url;
            $dst_w = $orig_w;
            $dst_h = $orig_h;
        } //else check if cache exists
        elseif (file_exists($destfilename) && getimagesize($destfilename)) {
            $img_url = "{$dst_rel_url}-{$suffix}.{$ext}";
        } //else, we resize the image and return the new resized image url
        else {
            // Note: This pre-3.5 fallback check will edited out in subsequent version
            if (function_exists('wp_get_image_editor')) {

                $editor = wp_get_image_editor($img_path);

                if (is_wp_error($editor) || is_wp_error($editor->resize($width, $height, $crop)))
                    return false;

                $resized_file = $editor->save();

                if (!is_wp_error($resized_file)) {
                    $resized_rel_path = str_replace($upload_dir, '', $resized_file['path']);
                    $img_url = "{$dst_rel_url}-{$suffix}.{$ext}";
                } else {
                    return false;
                }

            }
        }

        //return the output
        if ($single) {
            //str return
            $image = $img_url;
        } else {
            //array return
            $image = array(
                0 => $img_url,
                1 => $dst_w,
                2 => $dst_h
            );
        }

        return $image;
    }
}

if(!function_exists('inwave_get_title_string')){
    function  inwave_get_title_string($title){
        $output = '';
        $title = explode('.', $title);
        $new_title = '';
        if(count($title) > 1){
            $new_title .= '<span>'.$title[0].'.</span>';
            unset($title[0]);
        }
        $new_title .= implode('.', $title);
        $output .= '<h3>'.$new_title.'</h3>';

        return $output;
    }
}

if(!function_exists('inwave_display_pagination_none')){
    function inwave_display_pagination_none($query = '') {
        $rs = array('success'=>false, 'data'=>'');
        if (!$query) {
            global $wp_query;
            $query = $wp_query;
        }

        $paginate_links = paginate_links(array(
            'format' => '?page=%#%',
            'prev_next' => false,
            'current' => max(1, get_query_var('paged')),
            'show_all'=>true,
            'total' => $query->max_num_pages
        ));
        // Display the pagination if more than one page is found
        if ($paginate_links) :
            $html = array();
            $html[] = '<div class="post-pagination clearfix hide">';
            $html[] = $paginate_links;
            $html[] = '</div>';
            $rs['success'] = true;
            $rs['data'] = implode($html);
        endif;

        return $rs;
    }
}

if(!function_exists('inwave_show_post_info')) {
    function inwave_show_post_info()
    {
        global $inwave_theme_option;
        $show_post_infor = (isset($inwave_theme_option['show_post_date']) && $inwave_theme_option['show_post_date'])
            || (isset($inwave_theme_option['show_post_author']) && $inwave_theme_option['show_post_author'])
            || (isset($inwave_theme_option['blog_category_title_listing']) && $inwave_theme_option['blog_category_title_listing'])
            || (isset($inwave_theme_option['show_post_comment']) && $inwave_theme_option['show_post_comment']);

        return $show_post_infor;
    }
}

if(!function_exists('inwave_get_placeholder_image')){
    function inwave_get_placeholder_image(){
        return get_template_directory_uri().'/assets/images/default-placeholder.png';
    }
}

if(!function_exists('inwave_mmmenu_is_active')){
    function inwave_mmmenu_is_active(){
        return class_exists('MmenuAdminPage');
    }
}
