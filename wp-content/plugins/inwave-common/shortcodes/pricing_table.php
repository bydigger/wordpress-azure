<?php

/*
 * Inwave_Pricing_Table for Visual Composer
 */
if (!class_exists('Inwave_Pricing_Table')) {

    class Inwave_Pricing_Table extends Inwave_Shortcode{

        protected $name = 'inwave_pricing_table';

        function init_params() {
            return array(
                'name' => __("Pricing Table", 'inwavethemes'),
                'description' => __('Add a pricing table', 'inwavethemes'),
                'base' => $this->name,
                'category' => 'Custom',
                'icon' => 'iw-default',
                'params' => array(
                    array(
                        'type' => 'textarea_html',
                        "holder" => "div",
                        "heading" => __("Content", "inwavethemes"),
                        "value" => '<table class="table">
                                        <thead>
                                          <tr>
                                            <th>name of service</th>
                                            <th>Delivery time</th>
                                            <th>pricing</th>
                                            <th>professionals doctors</th>
                                            <th>Online tools supported</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Maternal Special Care Unit</td>
                                            <td>02 working hours</td>
                                            <td>Starting in $40</td>
                                            <td>01 professionals doctors</td>
                                            <td>no</td>
                                          </tr>
                                          <tr>
                                            <td>Maternal-Fetal Medicine for High-Risk Pregnancies</td>
                                            <td>02 working hours</td>
                                            <td>Starting in $40</td>
                                            <td> 01 professionals doctors</td>
                                            <td>Yes</td>
                                          </tr>
                                          <tr>
                                            <td>Fetal Care Center</td>
                                            <td>05 working hours</td>
                                            <td>Starting in $40</td>
                                            <td>01 professionals doctors</td>
                                            <td>Yes</td>
                                          </tr>
                                          <tr>
                                            <td>Labor and Birth</td>
                                            <td>06 working hours</td>
                                            <td>Starting in $40</td>
                                            <td>01 professionals doctors</td>
                                            <td>Yes</td>
                                          </tr>
                                          <tr>
                                            <td> After Your Baby Arrives</td>
                                            <td>01 working day</td>
                                            <td>Starting in $60</td>
                                            <td>01 professionals doctors</td>
                                            <td>Yes</td>
                                          </tr>
                                        </tbody>
                                      </table>',
                        "param_name" => "content",
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __( 'CSS box', 'js_composer' ),
                        'param_name' => 'css',
                        'group' => __( 'Design Options', 'js_composer' )
                    )
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $class = $title = $sub_title = $price = $icon_currency_unit = $price_desc = $purchase_text = $purchase_link = $img = $css = '';
            extract(shortcode_atts(array(
                'title' => '',
                'sub_title' => '',
                'price' => '',
                'icon_currency_unit' => '',
                'price_desc' => '',
                'purchase_text' => '',
                'purchase_link' => '',
                'img' => '',
                'css' => '',
                'class' => '',
            ), $atts));

            $class .= vc_shortcode_custom_css_class( $css);
            $content = rtrim($content, '</p>');
            $content = ltrim($content, '</p>');
            $output .= '<div class="iw-pricing-table-v4">';
                    $output .= $content;
            $output .= '</div>';
            return $output;
        }
    }
}

new Inwave_Pricing_Table();
