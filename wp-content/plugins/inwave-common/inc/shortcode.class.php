<?php

class Inwave_Shortcode_Class {

    // declare custom shortcodes
    private $shortCodes;

    function __construct() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        include_once('font-data.php');
        
        if (is_plugin_active('js_composer/js_composer.php')) {
            require_once(WP_PLUGIN_DIR . '/js_composer/include/classes/shortcodes/shortcodes.php');
        }

        require_once(INWAVE_COMMON . 'shortcodes/only_register_vc.php');

        $this->shortCodes = inwave_get_shortcodes();
        add_action('init', array($this, 'add_row_params'));
        add_action('admin_init', array($this, 'generate_shortcode_params'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_admin'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // include shortcodes
        foreach ($this->shortCodes as $shortCode) {
            include_once INWAVE_COMMON .'shortcodes/' . $shortCode . '.php';
        }

        add_action('media_buttons', array($this, 'shortcode_add_button'), 15);
        add_action('wp_ajax_nopriv_load_shortcode_settings', array($this, 'load_shortcode_settings'));
        add_action('wp_ajax_load_shortcode_settings', array($this, 'load_shortcode_settings'));
        add_action('wp_ajax_nopriv_load_shortcode_preview', array($this, 'load_shortcode_preview'));
        add_action('wp_ajax_load_shortcode_preview', array($this, 'load_shortcode_preview'));
        add_action('wp_ajax_nopriv_search_icons', array($this, 'search_icons'));
        add_action('wp_ajax_search_icons', array($this, 'search_icons'));
    }

    function load_shortcode_settings() {
        $shortcode = $_POST['shortcode'];
        if (isset(Inwave_Shortcode::$shortcodes[$shortcode])) {
            echo $this->get_field_html(Inwave_Shortcode::$shortcodes[$shortcode]);
        } else {
            echo __('Not exists', 'inwavethemes');
        }
        exit;
    }

    function search_icons() {
        $key = $_POST['key'];
        $type = $_POST['type'];
        echo $this->get_search_icons_html($type, $key);
        exit;
    }

    function generate_shortcode_params() {
        /* Generate param type "iw_icon" */
        if (function_exists('vc_add_shortcode_param')) {
            vc_add_shortcode_param('iw_icon', array($this, 'icon_param_settings_field'));
        }

        /* Generate param type preview image*/
        if (function_exists('vc_add_shortcode_param')) {
            vc_add_shortcode_param('iwevent_preview_image', array($this, 'preview_image_settings_field'));
        }
    }

    function load_shortcode_preview() {
        echo '<html><head>';
        wp_head();
        echo '<style>body{background:none!important}</style>';
        echo '</head><body>';
        $shortcode = stripslashes($_GET['shortcode']);
        echo do_shortcode($shortcode);
        wp_footer();
        echo '</body></html>';
        exit();
    }

    function enqueue_scripts() {
        $theme_info = wp_get_theme();
        wp_enqueue_style('iw-shortcodes', plugins_url() . '/inwave-common/assets/css/iw-shortcodes.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('iw-shortcodes-rtl', plugins_url() . '/inwave-common/assets/css/iw-shortcodes-rtl.css', array(), $theme_info->get('Version'));
        wp_enqueue_script('iw-shortcodes', plugins_url() . '/inwave-common/assets/js/iw-shortcodes.js', array(), $theme_info->get('Version'), true);
//        wp_enqueue_script('plg-intravel', plugins_url() . '/inwave-common/assets/js/iw-shortcodes.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), $theme_info->get('Version'), true);
    }

    function enqueue_scripts_admin() {
        $theme_info = wp_get_theme();
//        wp_enqueue_script('plg-intravel-admin', plugins_url() . '/intravel/assets/js/admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), $theme_info->get('Version'), true);
        //wp_enqueue_style('select2', get_template_directory_uri() . '/assets/css/select2.css', array(), $theme_info->get('Version'));
        wp_register_script('infobox', 'https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js', array('google-maps'), null, true);
        wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/font-awesome.min.css', array(), $theme_info->get('Version'));
        //wp_enqueue_style('glyphicons', get_template_directory_uri() . '/assets/fonts/glyphicons/glyphicons.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('font-ionicons', get_template_directory_uri() . '/assets/fonts/ionicons/ionicons.min.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('fancybox', get_template_directory_uri() . '/assets/fancybox/jquery.fancybox.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('iw-common', plugins_url() . '/inwave-common/assets/css/iw-admin.css', array(), $theme_info->get('Version'));

        //wp_enqueue_script('select2', get_template_directory_uri() . '/assets/js/select2.full.js', array(), $theme_info->get('Version'), true);
        wp_enqueue_script('fancybox', get_template_directory_uri() . '/assets/fancybox/jquery.fancybox.js', array(), $theme_info->get('Version'), false);
        wp_enqueue_script('iw-common', plugins_url() . '/inwave-common/assets/js/iw-admin.js', array('jquery'), $theme_info->get('Version'), true);
        wp_localize_script('iw-common', 'iwConfig', array('ajaxUrl' => admin_url('admin-ajax.php'), 'siteUrl' => site_url(), 'homeUrl' => get_home_url()));
        wp_enqueue_script('jquery-ui-draggable');
    }

    /* add parameter for rows */
    function add_row_params() {

        if (!defined('WPB_VC_VERSION')) {
            return;
        }

        // init new params
        $newParams = array(
            array(
                "type" => "dropdown",
                "group" => "Layout options",
                "class" => "",
                "heading" => "Width",
                "param_name" => "iw_layout",
                "value" => array(
                    "Auto" => "",
                    "Boxed" => "boxed",
                    "Wide Background" => "wide-bg",
                    "Wide Content" => "wide-content"
                )
            ),
            array(
                "type" => "dropdown",
                "group" => "Layout options",
                "class" => "",
                "heading" => "Parallax background",
                "description" => 'Require background image in Design Options Tab',
                "param_name" => "iw_parallax",
                "value" => array(
                    "No" => "0",
                    "Yes" => "1"
                )
            ),
            array(
                "type" => "textfield",
                "group" => "Layout options",
                "class" => "",
                "heading" => "Parallax Overlay opacity",
                "param_name" => "iw_parallax_overlay",
                "value" => '0.9'
            ),
            array(
                "type" => "textfield",
                "group" => "Layout options",
                "class" => "",
                "heading" => "Parallax background speed",
                "description" => "Enter speed factor from 0 to 1",
                "param_name" => "iw_parallax_speed",
                "value" => "0.1"
            )
        );
        // add to row
        vc_add_params("vc_row", $newParams);
        vc_add_params("vc_row_inner", array(
            array(
                'type' => 'animation_style',
                 'heading' => __( 'Animation', 'js_composer' ),
                 'param_name' => 'css_animation',
            )
        ));
        vc_add_params("vc_column_inner", array(
            array(
                'type' => 'animation_style',
                 'heading' => __( 'Animation', 'js_composer' ),
                 'param_name' => 'css_animation',
            )
        ));
        vc_set_shortcodes_templates_dir(dirname(dirname(__FILE__)) . '/vc_templates');
    }

    function icon_param_settings_field($settings, $value) {
        $name = $settings['param_name'] ? $settings['param_name'] : '';
        $type = isset($settings['type']) ? $settings['type'] : '';
        $class = isset($settings['class']) ? $settings['class'] : '';
        $class .= ' wpb_vc_param_value ' . $name . " " . $type;
        return $this->get_icon_field($name, $value, $class);
    }

    function preview_image_settings_field($settings, $value){
        $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
        $type = isset($settings['type']) ? $settings['type'] : '';
        $class = isset($settings['class']) ? $settings['class'] : '';
        $class = "wpb_vc_param_value " . $param_name . " " . $type . " " . $class;
        return $this->get_preview_image_settings_field($param_name, $settings['value'], $class);
    }

    //Functions
    function shortcode_add_button() {
        echo '<a href="javascript:void(0);" id="insert-iw-shortcode" class="button">' . __('Insert IW Shortcode', 'inwavethemes') . '</a>';
        ?>
        <div id='iw-list-shortcode' class="iw-shortcode list-shortcode iw-hidden">
            <div class="shortcode-contain">
                <div class="shortcode-control">
                    <div class="title"><?php _e('List Shortcode', 'inwavethemes'); ?></div>
                    <div class="close-btn"><i class="fa fa-times"></i></div>
                    <div class="filter-box"><input placeholder="<?php echo __('Shortcode filter', 'inwavethemes'); ?>" class="shortcode-filter" name="shortcode-filter"/></div>
                    <div style="clear: both;"></div>
                </div>
                <div class="shortcode-list-content">
                    <div class="shortcode-items">
                        <?php
                        foreach (Inwave_Shortcode::$shortcodes as $shortcode) {
                            if (isset($shortcode['base'])) {
                                echo '<div class="shortcode-item" data-shortcodetag="' . $shortcode['base'] . '">';
                                echo '<div class="icon">';
                                if ($shortcode['icon']) {
                                    if ($shortcode['icon'] == 'iw-default') {
                                        echo '<span class="' . $shortcode['icon'] . '"></span>';
                                    } else {
                                        echo '<i class="fa fa-2x fa-' . $shortcode['icon'] . '"></i>';
                                    }
                                } else {
                                    echo '<i class="iw-shortcode-dficon"></i>';
                                }
                                echo '</div>';
                                echo '<div class="short-info">';
                                echo '<div class="s_name">' . $shortcode['name'] . '</div>';
                                echo '<div class="s_des">' . (isset($shortcode['description']) ?  $shortcode['description'] : '') . '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
        <div id="iw-shortcode" class="iw-hidden iw-shortcode shortcode-item-view">
            <div class="shortcode-contain">
                <div class="shortcode-control">
                    <div class="title"><?php _e('Shortcode settings', 'inwavethemes'); ?></div>
                    <div class="close-btn"><i class="fa fa-times"></i></div>
                    <div style="clear: both;"></div>
                </div>
                <div class="shortcode-content">
                </div>
                <div class="shortcode-preview">
                </div>
                <div class="shortcode-save-setting">
                    <div class="save-settings"><?php _e('Insert Shortcode', 'inwavethemes'); ?></div>
                    <div class="cancel-settings"><?php _e('Cancel', 'inwavethemes'); ?></div>
                    <div class="preview-settings"><?php _e('Preview', 'inwavethemes'); ?></div>
                    <div style="clear: both; padding: 0;"></div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
                    var $shortcodeItems = $('.shortcode-items .shortcode-item');
                    $('.shortcode-control .shortcode-filter').on('input', function () {
                        var filterVal = $(this).val();
                        $shortcodeItems.each(function () {
                            var text = $(this).text();
                            if (text.toLowerCase().indexOf(filterVal.toLowerCase()) >= 0) {
                                $(this).fadeIn();
                            } else {
                                $(this).fadeOut();
                            }
                        });
                    });
                });
            })(jQuery);
        </script>
        <?php
    }

    function get_field_html($params) {
        $shortcode = $params['base'];
        $content_e = isset($params['content_element']) ? 1 : 0;
        $fields = $params['params'];

        ob_start();
        foreach ($fields as $field):
            $value = isset($field['value']) ? $field['value'] : '';
            ?>
            <div class="field-group <?php echo isset($field['class']) ? $field['class'] : ''; ?>">
                <div class="field-label"><?php echo $field['heading']; ?></div>
                <div class="field-input">
                    <?php
                    switch ($field['type']) {
                        case 'textfield':
                            echo $this->get_text_field($field['param_name'], $value);
                            break;
                        case 'iw_select':
                            echo $this->get_select_field($field['param_name'], $value, $field['data'], $field['multiple'] ? $field['multiple'] : 0, $field['class'] ? $field['class'] : '');
                            break;
                        case 'textarea':
                            echo $this->get_textarea_field($field['param_name'], $value);
                            break;
                        case 'dropdown':
                            if (!$value)
                                $value = array();
                            echo $this->get_dropdown_field($field['param_name'], $value);
                            break;
                        case 'textarea_html':
                            echo $this->get_textareahtml_field($field['param_name'], $value);
                            break;
                        case 'attach_image':
                            echo $this->get_attach_image_field($field['param_name'], $value);
                            break;
                        case 'iconpicker':
                        case 'iw_icon':
                            echo $this->get_icon_field($field['param_name'], $value, $field['class'] ? $field['class'] : '');
                            break;
                        case 'colorpicker':
                            echo $this->get_colorpicker_field($field['param_name'], $value);
                            break;
                        case 'iw_range_skillbar':
                            echo $this->get_range_skillbar_field($field['param_name'], $value, $field['class'] ? $field['class'] : '');
                            break;
						case 'iw_profile_info':
                            echo $this->get_profile_info_field($field['param_name'], $value, $field['class'] ? $field['class'] : '');
                            break;
                        default:
                            break;
                    }
                    ?>
                </div>
                <?php if (isset($field['description'])): ?>
                    <div class="field-description"><?php echo $field['description']; ?></div>
                <?php endif; ?>
            </div>
            <?php
        endforeach;
        echo '<input type="hidden" name="shortcode_tag" value="' . $shortcode . '"/>';
        echo '<input type="hidden" name="shortcode_close_tag" value="' . $content_e . '"/>';
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    function get_attach_image_field($fname, $fvalue) {
        if (!is_numeric($fvalue)) {
            $fvalue = null;
        }
        ob_start();
        ?>
        <div class="iw-image-field">
            <div class="image-preview iw-hidden"></div>
            <div class="image-add-image"><span><i class="fa fa-plus"></i></span></div>
        </div>
        <input type="hidden" value="<?php echo $fvalue; ?>" name="<?php echo $fname; ?>" class="iw-field iw-image-field-data"/>
        <div style="clear: both;"></div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    function get_textareahtml_field($fname, $fvalue) {
        ob_start();
        ?>
        <div class="textarea_html">
            <textarea class="iw-field iw-textarea-html" name="<?php echo $fname; ?>" id="iw-tinymce-<?php echo $fname; ?>"><?php echo $fvalue; ?></textarea>
        </div>
        <script type="text/javascript">
            (function ($) {
                $('.shortcode-content .field-group').ready(function () {
                    var ed = new tinymce.Editor('iw-tinymce-<?php echo $fname; ?>', {
                    }, tinymce.EditorManager);

                    ed.on('change', function (e) {
                        var content = ed.getContent();
                        $('#iw-tinymce-<?php echo $fname; ?>').text(content);
                    });
                    ed.render();
                });
            })(jQuery);
        </script>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    function get_icon_field($fname, $fvalue, $class) {
        ob_start();
        global $inwave_fonts;
        ?>
        <div class="control-icon">
            <div class="icon-preview" onclick="jQuery('.iw-list-icon-wrap').slideToggle();">
                <span><i class="<?php echo $fvalue; ?>"></i></span>
            </div>
            <div class="icon-filter">
                <input class="filter-input" style="width:49%" onclick="jQuery('.iw-list-icon-wrap').slideDown();" placeholder="<?php echo __('Click to select new or search...', 'inwavethemes'); ?>" type="text"/>
                <select class="filter-input-type" style="width:49%">
                    <option value="">All</option>
                    <?php
                    foreach ($inwave_fonts as $type=>$font){
                        echo '<option value="'.$type.'">'.$font['name'].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="clear:both;"></div>
        <div class="iw-list-icon-wrap" style="display:none;">
            <input name="<?php echo $fname; ?>" type="hidden" value="<?php echo $fvalue; ?>" class="iw-icon-input-cs iw-field iw-iw_icon-field <?php echo $class; ?>">
            <div class="list-icon-wrap">
                <ul id="iw-list-icon" class="list-icon">
                    <?php
                    echo $this->get_search_icons_html('', '', $fvalue);
                    ?>
                </ul>
                <div class="ajax-overlay">
                    <span class="ajax-loading"><i class="fa fa-spinner fa-spin fa-2x"></i></span>
                </div>
            </div>

        </div>
        <script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
                    var xhr = '';
                    var timeout = '';
                    $('.icon-filter .filter-input').on('input', function () {
                        var filterVal = $(this).val();
                        var type = $(this).parent().find('.filter-input-type').val();
                        $('.list-icon-wrap .ajax-overlay').fadeIn();
                        jQuery('.iw-list-icon-wrap').slideDown();
                        if (xhr) {
                            xhr.abort();
                        }
                        clearTimeout(timeout);
                        timeout = setTimeout(function () {
                            xhr = $.ajax({
                                url: iwConfig.ajaxUrl,
                                data: {action: 'search_icons', 'key': filterVal, 'type': type},
                                type: "post",
                                success: function (data) {
                                    $('#iw-list-icon').html(data);
                                    $('.list-icon-wrap .ajax-overlay').fadeOut();
                                }
                            });
                        }, 200);

                    });
                    $('.icon-filter .filter-input-type').on('change', function () {
                        $('.icon-filter .filter-input').trigger('input');
                    })
                    $('.icon-item').live('click', function () {
                        var value = $(this).data('icon');
                        var html = '<span><i class="' + value + '"></i></span>';
                        $('.control-icon .icon-preview').html(html);
                        $('input[name="<?php echo $fname; ?>"]').val(value);
                        $('.icon-item').removeClass('selected');
                        $(this).addClass('selected');
                        $('.iw-list-icon-wrap').slideUp();
                    });
                });
                $('.iw-icon-input-cs').live('change', function () {
                    var value = jQuery(this).val();
                    var html = '<i class="' + value + '"></i>';
                    $('.control-icon .icon-preview').html(html);
                })
            })(jQuery);
        </script>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    function get_search_icons_html($type = 'fa', $key = '', $fvalue = '', $limit = 640) {

        global $inwave_fonts;
        $result = array();
        $i = 0;

        foreach ($inwave_fonts as $_type => $font) {
            if(!$type || $type == $_type){
                foreach ($font['icons'] as $icon){
                    if ($key == '' || strpos($icon, $key) !== false) {
                        $icon = $font['prefix'] . $icon;
                        $result[] = $icon;
                        $i++;
                        if ($i == $limit) break;
                    }
                }
            }
        }

        $html = '';
        if (count($result)) {
            $html .= '<li title="No icon" class="icon-item" data-icon=""><span class="icon"><i class=""></i></span></li>';
            foreach ($result as $icon) {

                $html .= '<li class="icon-item ' . ($icon == $fvalue ? 'selected' : '') . '" data-icon="' . $icon . '"><span class="icon"><i class="' . $icon . '"></i></span></li>';
            }
        } else {
            $html .= '<li>Not found</li>';
        }
        return $html;
    }

    function get_colorpicker_field($fname, $fvalue) {
        $html = '<div class="iwcolor-picker color-group"><input type="text" value="' . htmlspecialchars($fvalue) . '" name="' . $fname . '" class="vc_color-control iw-field input-field"></div>';
        $html .= "<script>if(typeof('vc') != undefined) vc.atts.colorpicker.init('','.iwcolor-picker')</script>";

        wp_enqueue_script('wpb_js_composer_js_atts');
        return $html;
    }

    function get_text_field($fname, $fvalue) {
        return '<input type="text" value="' . htmlspecialchars($fvalue) . '" name="' . $fname . '" class="iw-field input-field"/>';
    }

    function get_textarea_field($fname, $fvalue) {
        return '<textarea name="' . $fname . '" class="iw-field textarea-field">' . $fvalue . '</textarea>';
    }

    function get_dropdown_field($fname, $fvalue) {
        $html = '';
        $html .= '<select name="' . $fname . '" class="iw-field dropdown-field">';
        foreach ($fvalue as $text => $value) {
            $html .= '<option value="' . $value . '">' . $text . '</option>';
        }
        $html.='</select>';
        return $html;
    }

    function get_range_skillbar_field($fname, $fvalue, $class) {
        $html = '<input class="skillbar_input iw-field ' . $class . '" value="' . $fvalue . '" type="range" min="0" max="100" step="1" name="' . $fname . '"/>
				<span class="value-view">' . ($fvalue ? $fvalue : 50) . '%</span>
					<script>
						jQuery("input.skillbar_input").on("input", function(){
							jQuery(this).parent().find(".value-view").text(jQuery(this).val() + "%");
						});
					</script>
				';
        return $html;
    }
	
	function get_profile_info_field($fname, $fvalue, $class) {
        ob_start();
        ?>
        <div class="profile_info">
			<div class="profile_lists">
			<?php if($fvalue){
			$arr = explode('||', $fvalue);
			foreach($arr as $k=>$v){
				$profile_info = explode(',',$v);
				?>
				<div class="profile_list_item">
					<label>User's skill name</label>
					<input type="text" class="profile_info_label" value="<?php echo $profile_info[0];?>" name="profile_info[label][]" />
					
					<label>User's skill value (from 1 to 100)</label>
					<input type="number" min="1" max="100" class="profile_info_value" value="<?php echo $profile_info[1];?>" name="profile_info[value][]" />
				</div>
				<?php
			}
			}else{?>
				<div class="profile_list_item">
					<label>User's skill name</label>
					<input type="text" class="profile_info_label" name="profile_info[label][]" />
					
					<label>User's skill value (from 1 to 100)</label>
					<input type="text" class="profile_info_value" name="profile_info[value][]" />
				</div>
				<?php }?>
			</div>
			<input type="hidden" class="profile_input iw-field <?php echo $class;?>" value="<?php echo $fvalue;?>" name = "<?php echo $fname;?>" />
			<button class="addmore"><?php _e("Add more user's skill", 'inwavethemes'); ?></button>
			
        </div>
		<script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
					$('.profile_info .addmore').click(function(){
						var html='<div class="profile_list_item">';
							html+='<label>Profile skill title</label>';
							html+='<input class="profile_info_label" type="text" name="profile_info_label" />';
							html+='<label>Profile skill value</label>';
							html+='<input type="text" class="profile_info_value" name="profile_info_value" />';
							html+='</div>';
							
							$('.profile_info .profile_lists').append(html);
				
					});
					
					$('.profile_info input[type="text"]').live('change', function(){
						var labels = $('.profile_info .profile_info_label'),
							values = $('.profile_info .profile_info_value'),
							new_arr = '';
						for(var i = 0; i < labels.length; i++){
							if(i>0){
								new_arr += '||';
							}
							new_arr += $(labels[i]).val()+','+$(values[i]).val();
						}
						$('.profile_info .profile_input').val(new_arr);
					});
                 });
            })(jQuery);
        </script>
       
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
	}

	function get_iwe_event_field($fname, $fvalue, $class) {
        ob_start();
        $args = array(
            'post_type' => 'iwevent',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );

        $iwevents = get_posts($args);
        ?>
            <select name="<?php echo $fname;?>" class="iw-field <?php echo $class;?>">
                <option value=""><?php echo __('Select Event', 'inwavethemes');?></option>
                <?php
                    foreach($iwevents as $iwevent){
                        ?>
                        <option value="<?php echo $iwevent->ID; ?>" <?php echo $iwevent->ID == $fvalue ? 'selected' : '' ?>><?php echo $iwevent->post_title; ?></option>
                        <?php
                    }
                ?>
            </select>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
	}

    function get_iwe_cateogry_spearker_field($fname, $fvalue, $class) {
        ob_start();
        global $wpdb;
        $categories = $wpdb->get_results('SELECT id, name FROM ' . $wpdb->prefix . 'iwe_speaker_category WHERE status = 1');
        ?>
            <select name="<?php echo $fname;?>" class="iw-field <?php echo $class;?>">
                <option value=""><?php echo __('All Categories'); ?></option>
                <?php
                    foreach($categories as $category){
                        ?>
                        <option value="<?php echo $category->id; ?>" <?php echo $category->id == $fvalue ? 'selected' : '' ?>><?php echo $category->name; ?></option>
                        <?php
                    }
                ?>
            </select>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
	}

    function get_preview_image_settings_field($fname, $fvalue, $class) {
        ob_start();
        ?>
            <a name="<?php echo $fname; ?>" class="preview-image <?php echo $class;?>" href="#<?php echo $fname; ?>" style="display: inline-block"><img style="max-width: 80px; max-height: 50px" src="<?php echo $fvalue; ?>"></a>
            <img id="<?php echo $fname;?>" style="display: none" src="<?php echo $fvalue; ?>">
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
	}
	
	//if(!function_exists('margeArray')){
		public function margeArray($array) {
			if (!is_array($array)) {
				return;
			}
			$key_title = $array['key_title'];
			$key_value = $array['key_value'];
			$new_array = array();
			$i = 0;
			foreach ($key_title as $value) {
				$new_array[] = array('key_title' => $value, 'key_value' => $key_value[$i]);
				$i++;
			}
			return $new_array;
		}
	//}
}

new Inwave_Shortcode_Class();
