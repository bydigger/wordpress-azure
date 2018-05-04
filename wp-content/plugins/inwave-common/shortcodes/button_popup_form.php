<?php
/*
 * Inwave_Button for Visual Composer
 */
if (!class_exists('Inwave_Button_Popup_Form')) {

    class Inwave_Button_Popup_Form extends Inwave_Shortcode{

        protected $name = 'inwave_button_popup_form';

        function init_params() {
            return array(
                'name' => __("Button Popup Form", 'inwavethemes'),
                'description' => __('Insert a button with some styles', 'inwavethemes'),
                'base' => $this->name,
                'icon' => 'iw-default',
                'category' => 'Custom',
                'params' => array(
                    array(
                        "type" => "textfield",
                        "heading" => __("Button Text", "inwavethemes"),
                        "param_name" => "button_text",
                        "holder" => "div",
                        "value"=>"Click here"
                    ),
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Title popup form", "inwavethemes"),
                        "description" => "",
                        "value" => "",
                        "param_name" => "title_form",
                    ),
                    array(
                        'type' => 'textarea',
                        "heading" => __("Description popup form", "inwavethemes"),
                        "value" => "",
                        "param_name" => "description_form",
                    ),
                    array(
                        'type' => 'textarea_html',
                        "holder" => "div",
                        "heading" => __("Add Shortcode", "inwavethemes"),
                        "value" => "",
                        "param_name" => "content"
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

        function init_shortcode($atts, $content = null){
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $class = $button_text = $title_form = $description_form = $css = '';
            extract(shortcode_atts(array(
                'class' => '',
                'button_text' => '',
                'title_form' => '',
                'css' => '',
                'description_form' => '',
            ), $atts));

            $id_modal_target = 'myModal'.rand(1,100);

            ob_start();

            ?>

            <div class="iw-button-popup <?php echo esc_attr($class); ?>">
                <button type="button" class="theme-bg-hover open-popup" data-toggle="modal" data-target="#<?php echo esc_attr($id_modal_target); ?>"><?php echo esc_attr($button_text); ?></button>
                <?php if($content) { ?>
                    <div class="modal fade" id="<?php echo esc_attr($id_modal_target); ?>" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header theme-bg">
                                    <button type="button" class="close" data-dismiss="modal"><i class="ion-close"></i></button>
                                    <?php if ($title_form) { ?>
                                        <h3 class="title-form"><?php echo esc_attr($title_form); ?></h3>
                                    <?php } ?>
                                    <?php if ($description_form) { ?>
                                        <p class="desc-form"><?php echo esc_attr($description_form); ?></p>
                                    <?php } ?>
                                </div>
                                <div class="modal-body">
                                    <?php if($content) { ?>
                                        <div><?php echo do_shortcode(wpb_js_remove_wpautop( $content, true )); ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <?php
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
    }
}

new Inwave_Button_Popup_Form;
