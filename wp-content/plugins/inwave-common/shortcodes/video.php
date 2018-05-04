<?php

/*
 * Inwave_Item_Info for Visual Composer
 */
if (!class_exists('Inwave_Video')) {

    class Inwave_Video extends Inwave_Shortcode{

        protected $name = 'inwave_video';

        function init_params() {
            return array(
                'name' => __("Video HTML", 'inwavethemes'),
                'description' => __('Add a video HTML', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        "type" => "dropdown",
                        "admin_label" => true,
                        "heading" => "Style",
                        "param_name" => "style",
                        "value" => array(
                            "Style 1" => "style1",
                        )
                    ),
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_default",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/video.jpg',
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
					array(
                        'type' => 'textfield',
                        "heading" => __("Video URL", "inwavethemes"),
                        "value" => "",
                        "param_name" => "video_url",
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
					array(
                        'type' => 'attach_image',
                        "heading" => __("Poster image", "inwavethemes"),
                        "param_name" => "video_poster",
                        "dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
					array(
                        'type' => 'checkbox',
                        'heading' => __( 'Open video on popup?', 'js_composer' ),
                        'param_name' => 'open_popup',
                    ),
					array(
                        'type' => 'checkbox',
                        'heading' => __( 'Crop poster image?', 'js_composer' ),
                        'param_name' => 'crop_poster',
						"dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
					array(
                        'type' => 'textfield',
                        "heading" => __("Poster Image width", "inwavethemes"),
                        "value" => "800",
						"description" => __("Example: 800", "inwavethemes"),
                        "param_name" => "poster_width",
						"dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
					array(
                        'type' => 'textfield',
                        "heading" => __('Poster Image height ', 'inwavethemes'),
                        "value" => "600",
						"description" => __('Example: 600.<br /> If "Crop poster image" is not checked, poster height is element height.', 'inwavethemes'),
                        "param_name" => "poster_height",
						"dependency" => array('element' => 'style', 'value' => 'style1')
                    ),
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $class = $style = $video_url = $video_poster = $open_popup = $crop_poster = $poster_width = $poster_height = '';
            extract(shortcode_atts(array(
                'style' => '',
				'video_url' => '',
				'video_poster' => '',
				'open_popup' => '',
				'crop_poster' => '',
				'poster_height' => '',
				'poster_width' => '',
            ), $atts));
			
			$class .= ' '.$style;

            switch ($style) {
                // Normal style
				case 'style1':
					$class_modal = '';
					if ($open_popup){
						$class_modal = 'modal fade';
					} else {
						$class .= ' no-popup';
					}
					if ($video_url) {
						if($video_poster){
							$video_poster = wp_get_attachment_image_src($video_poster, 'full');
                            $video_poster = $video_poster[0];
							if ($crop_poster){
								if ($poster_width && $poster_height){
									$video_poster = inwave_resize($video_poster, $poster_width, $poster_height, true);
								}
							}
							$element_css = 'style="';
							if ($poster_height){
								$element_css .= 'height:'.$poster_height.'px;';
							} else {
								$element_css .= 'height:800px;';
							}
							if ($poster_width){
								$element_css .= 'width:'.$poster_width.'px;';
							} else {
								$element_css .= 'width:100%;';
								$class .= ' no-width';
							}
							$element_css .= '"';
						} else {
							$class .= ' no-poster';
						}
							
						$output .= '<div class="iw-video-html '.$class.'">';
						$output .= '<div class="iw-video">';
						$id_modal_target = 'myModal'.rand(1,100);
                        if($video_poster){
                            $output .= '<div class="video-poster" '.$element_css.'>';
							$output .= '<div class="video-poster-inner" style="background-image: url('.esc_url($video_poster).');"></div>';
							if ($open_popup){			
								$output .= '<button type="button" class="open-popup-btn open-popup" data-toggle="modal" data-target="#'.$id_modal_target.'"></button>';
							} else {
								$output .= '<div class="play-button"></div>';
							}
							$output .=	'</div>';
                        }
                    //    $output .= '';
                        $output .= '<div class="iw-video-player '.$class_modal.'" id="'.$id_modal_target.'" role="dialog">';
                        $output .= '<div class="iw-modal-dialog">';
                        $output .= '<div class="play-button"></div>';
                        $output .= '<div class="video"><video src="'.$video_url.'"></video></div>';
						if ($open_popup){
							$output .= '<button type="button" class="btn btn-default close-popup" data-dismiss="modal"><i class="ion-close"></i></button>';
						}
                        $output .= '</div>';
                        $output .= '</div>';
                    
                    $output .= '</div>';
					
                    $output .= '</div>';
					}
				break;
            }
            return $output;
        }
    }
}

new Inwave_Video;
