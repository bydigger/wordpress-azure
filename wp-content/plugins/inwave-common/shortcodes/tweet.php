<?php

/*
 * Inwave_Heading for Visual Composer
 */
if (!class_exists('Inwave_Tweet')) {

    class Inwave_Tweet extends Inwave_Shortcode{

        protected $name = 'inwave_tweet';

        function init_params() {
            return array(
                'name' => __("Tweet", 'inwavethemes'),
                'description' => __('Schedule a countdown until a time in the future', 'inwavethemes'),
                'base' => $this->name,
                'icon' => 'iw-default',
                'category' => 'Custom',
                'params' => array(
                    array(
                        "type" => "textfield",
                        "heading" => __("User name", "inwavethemes"),
                        "param_name" => "user_name",
                        "value" => "",
                        "description" => __('Enter Twitter User name, if is blank we are will get from theme options.', "inwavethemes")
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Number", "inwavethemes"),
                        "param_name" => "num",
                        "value" => "3",
                        "description" => __('Choose among how many tweets.', "inwavethemes")
                    ),
                    array(
                        "type" => "checkbox",
                        "heading" => __("Options",'inwavethemes'),
                        "param_name" => "options",
                        "value" => array(
                            __('Show Hyperlinks', "inwavethemes") =>'hyperlinks',
                            __('Find @replies', "inwavethemes") =>'twitter_users',
                            __('UTF8 Encode', "inwavethemes") =>'encode_utf8',
                        ),
                        'std' => 'hyperlinks,twitter_users,encode_utf8'
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __( 'CSS box', 'js_composer' ),
                        'param_name' => 'css',
                        // 'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
                        'group' => __( 'Design Options', 'js_composer' )
                    )
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $end_date = $class = '';
            extract(shortcode_atts(array(
                'num' => '',
                'user_name' => '',
                'options' => '',
                'class' => '',
                'css' => ''
            ), $atts));

            $class .= ' '. vc_shortcode_custom_css_class( $css);

            global $inwave_theme_option;

            $user_name = $user_name ? $user_name : $inwave_theme_option['tw_username'];

            $data_plugin_options = '"navigation":false, "autoPlay":true, "autoPlayTimeout":1000, "pagination":false, "paginationNumbers":true, "items":1, "itemsDesktop":[1199, 1], "itemsDesktopSmall":[991, 1], "itemsTablet":[767, 1], "itemsTabletSmall":false, "itemsMobile":[479, 1]';

            if(!class_exists('StormTwitter')) {
                $StormTwitter_path = WP_PLUGIN_DIR . '/inwave-common/inc/StormTwitter.class.php';
                if (!file_exists($StormTwitter_path)) {
                    return __('StormTwitter.class.php is not found. please active inwave-common plugin.', 'inwavethemes');
                } else {
                    require_once $StormTwitter_path;
                }
            }

            $st = new StormTwitter(array(
                'key' => $inwave_theme_option['tw_consumer_key'],
                'secret' => $inwave_theme_option['tw_consumer_secret'],
                'token' => $inwave_theme_option['tw_access_token'],
                'token_secret' => $inwave_theme_option['tw_access_token_secret'],
                'cache_expire' => 3600
            ));
            $options = explode(",",$options);
            $tweets = $st->getTweets($user_name, $num);
            //$tweets = json_decode('[{"created_at":"Fri Jan 08 03:31:14 +0000 2016","id":6.8530271777482e+17,"id_str":"685302717774823424","text":"How 1 to Authenticate Users With Twitter OAuth https:\/\/t.co\/KDLwHe2Uz0","entities":{"hashtags":[],"symbols":[],"user_mentions":[],"urls":[{"url":"https:\/\/t.co\/KDLwHe2Uz0","expanded_url":"http:\/\/nukeviet.vn\/vi\/news\/the-gioi-cong-nghe\/Let-s-Encrypt-da-sang-giai-doan-thu-nghiem-cong-khai-385\/?utm_source=feedburner&utm_medium=twitter&utm_campaign=Feed%3A+nukeviet%2Fnews+%28Tin+t%E1%BB%A9c+NukeViet%29","display_url":"nukeviet.vn\/vi\/news\/the-gi\u2026","indices":[45,68]}]},"truncated":false,"source":"<a href=\"http:\/\/twitter.com\" rel=\"nofollow\">Twitter Web Client<\/a>","in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":3308503320,"id_str":"3308503320"},"geo":null,"coordinates":null,"place":null,"contributors":null,"is_quote_status":false,"retweet_count":0,"favorite_count":0,"favorited":false,"retweeted":false,"possibly_sensitive":false,"lang":"en"},{"created_at":"Fri Jan 08 03:31:14 +0000 2016","id":6.8530271777482e+17,"id_str":"685302717774823424","text":"How to Authenticate Users With Twitter OAuth https:\/\/t.co\/KDLwHe2Uz0","entities":{"hashtags":[],"symbols":[],"user_mentions":[],"urls":[{"url":"https:\/\/t.co\/KDLwHe2Uz0","expanded_url":"http:\/\/nukeviet.vn\/vi\/news\/the-gioi-cong-nghe\/Let-s-Encrypt-da-sang-giai-doan-thu-nghiem-cong-khai-385\/?utm_source=feedburner&utm_medium=twitter&utm_campaign=Feed%3A+nukeviet%2Fnews+%28Tin+t%E1%BB%A9c+NukeViet%29","display_url":"nukeviet.vn\/vi\/news\/the-gi\u2026","indices":[45,68]}]},"truncated":false,"source":"<a href=\"http:\/\/twitter.com\" rel=\"nofollow\">Twitter Web Client<\/a>","in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":3308503320,"id_str":"3308503320"},"geo":null,"coordinates":null,"place":null,"contributors":null,"is_quote_status":false,"retweet_count":0,"favorite_count":0,"favorited":false,"retweeted":false,"possibly_sensitive":false,"lang":"en"}]');
            //var_dump($tweets);exit;
            if($tweets){
                if (is_array($tweets) && isset($tweets['error'])) {
                    if (is_array($tweets) && isset($tweets['error'][0]) && isset($tweets['error'][0]['message'])) {
                        $last_error = $tweets['error'][0]['message'];
                    } else {
                        $last_error = $tweets['error'];
                    }

                    return $last_error;
                } else {
                    $output .= '<div class="iw-tweet clearfix '.$class.'">';
                    $output .= '<span class="tweet-icon"><i class="fa fa-twitter"></i></span>';
                    $output .= '<div class="tweet-content">';
                    $output .= "<div class='owl-carousel' data-plugin-options='{" .$data_plugin_options. "}'>";
                    foreach ($tweets as $key=>$tweet){
                        $tweet = (array)$tweet;
                        $output .= '<div class="'.($key == 0 ? 'active' : '').'">';
                        $msg = $tweet['text'];
                        if(in_array('encode_utf8', $options)) $msg = utf8_encode($msg);
                        if (in_array('hyperlinks', $options)) {
                            // match protocol://address/path/file.extension?some=variable&another=asf%
                            $msg = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $msg);
                            // match www.something.domain/path/file.extension?some=variable&another=asf%
                            $msg = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $msg);
                            // match name@address
                            $msg = preg_replace('/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i',"<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $msg);
                            //NEW mach #trendingtopics
                            //$msg = preg_replace('/#([\w\pL-.,:>]+)/iu', '<a href="http://twitter.com/#!/search/\1" class="twitter-link">#\1</a>', $msg);
                            //NEWER mach #trendingtopics
                            $msg = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<a href="http://twitter.com/#!/search/%23\2" class="twitter-link" target="_blank">#\2</a>', $msg);
                        }
                        if (in_array('twitter_users', $options))  {
                            $msg = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $msg);
                        }

                        $output .= '<div class="twitter-message">'.$msg.'</div>';
                        $output .= '</div>';
                    }
                    $output .= '</div>';
                    $output .= '</div>';
                    ob_start();
                    echo $output
                    ?>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                    <?php
                    $output = ob_get_contents();
                    ob_end_clean();
                    $output .= '</div>';
                }
            }

            return $output;
        }
    }
}

new Inwave_Tweet;
