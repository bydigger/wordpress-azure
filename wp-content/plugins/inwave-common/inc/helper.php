<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of functions
 *
 * @developer duongca
 */
if (!function_exists('inwave_get_shortcodes')) {

    function inwave_get_shortcodes() {
        return array(
            'base',//required
            'heading',
//			'contact',
			'map',
			'multi_map',
            'testimonials',
            'posts',
            'info_list',
            'item_info',
            'button',
            'button_popup_form',
            'iw_sponsors',
            'tweet',
            'simple_list',
			'video',
			'pricing_table',
			'pricings',
			'tabs',
			'tab_item',
			'doctors_schedule',
			'iw_accordions',
            'opening_hours',
			'iw_department',
			'iw_departments',
			'departments_slider_custom',
			'iw_doctor',
			'iw_doctors',
			'iw_doctor_custom',
			//'iw_appoinment_form',
			'iw_time_table',
			'iw_event_listing',
			'iw_upcomming_event',
			'comparison-slider',
        );
    }
}

if (!function_exists('inwave_add_shortcode_script')) {

    function inwave_add_shortcode_script($scripts) {
        if ($scripts) {
            $theme_info = wp_get_theme();
            foreach ($scripts as $key => $scripts2) {
                foreach ($scripts2 as $key2 => $script) {
                    if ($key == 'scripts') {
                        wp_enqueue_script($key2, $script, array('jquery'), $theme_info->get('Version'));
                    } else {
                        wp_enqueue_style($key2, $script, array(), $theme_info->get('Version'));
                    }
                }
            }
        }
    }

}

if (!function_exists('inwave_get_element_by_tags')) {
    /**
     * Function to get element by tag
     * @param string $tag tag name. Eg: embed, iframe...
     * @param string $content content to find
     * @param int $type type of tag. <br/> 1. [tag_name settings]content[/tag_name]. <br/>2. [tag_name settings]. <br/>3. HTML tags.
     * @return type
     */
    function inwave_get_element_by_tags($tag, $content, $type = 1) {
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

        // Caipt'n, ready to hook.
        add_filter( 'image_resize_dimensions', 'aq_upscale', 10, 6 );

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
        remove_filter( 'image_resize_dimensions', 'aq_upscale' );
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
if(!function_exists('aq_upscale')){
    function aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
        if ( $crop ) {
            // crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
            $aspect_ratio = $orig_w / $orig_h;
            $new_w = min($dest_w, $orig_w);
            $new_h = min($dest_h, $orig_h);

            if ( ! $new_w ) {
                $new_w = (int) round( $new_h * $aspect_ratio );
            }

            if ( ! $new_h ) {
                $new_h = (int) round( $new_w / $aspect_ratio );
            }

            $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

            $crop_w = round($new_w / $size_ratio);
            $crop_h = round($new_h / $size_ratio);

            if ( ! is_array( $crop ) || count( $crop ) !== 2 ) {
                $crop = array( 'center', 'center' );
            }

            list( $x, $y ) = $crop;

            if ( 'left' === $x ) {
                $s_x = 0;
            } elseif ( 'right' === $x ) {
                $s_x = $orig_w - $crop_w;
            } else {
                $s_x = floor( ( $orig_w - $crop_w ) / 2 );
            }

            if ( 'top' === $y ) {
                $s_y = 0;
            } elseif ( 'bottom' === $y ) {
                $s_y = $orig_h - $crop_h;
            } else {
                $s_y = floor( ( $orig_h - $crop_h ) / 2 );
            }
        } else {
            // don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
            $crop_w = $orig_w;
            $crop_h = $orig_h;

            $s_x = 0;
            $s_y = 0;

            list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
        }

        // if the resulting image would be the same size or larger we don't want to resize it
        if ( $new_w >= $orig_w && $new_h >= $orig_h && $dest_w != $orig_w && $dest_h != $orig_h ) {
            return false;
        }

        // the return array matches the parameters to imagecopyresampled()
        // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
        return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
    }
}

if(!function_exists('inwave_get_placeholder_image')){
    function inwave_get_placeholder_image(){
        return get_template_directory_uri().'/assets/images/default-placeholder.png';
    }
}

