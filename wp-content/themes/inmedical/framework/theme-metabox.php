<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category ARIVA
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'inwave_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function inwave_metaboxes( array $meta_boxes ) {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'inwave_';

    $sideBars = array();
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
        $sideBars[$sidebar['id']] = ucwords( $sidebar['name'] );
    }

    $menuArr = array();
    $menuArr[''] = 'Default';
    $menus = get_terms('nav_menu');
    foreach ( $menus as $menu ) {
        $menuArr[$menu->slug] = $menu->name;
    }

    /**
     * Metabox to be displayed on a single page ID
     */

    $meta_boxes['page_metas'] = array(
        'id'         => 'page_metas',
        'title'      => esc_html__( 'Page Options', 'inmedical' ),
        'pages'      => array( 'page', 'post' ), // Post type
        'context'    => 'side',
        'priority'   => 'low',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name'    => esc_html__('Main Color', 'inmedical'),
                'id'      => $prefix . 'main_color',
                'type'    => 'colorpicker',
                'default' => '',
            ),
            array(
                'name'    => esc_html__('Background Color Page', 'inmedical'),
                'id'      => $prefix . 'background_color_page',
                'type'    => 'colorpicker',
                'default' => '',
            ),
            array(
                'name'    => esc_html__('Select Revolution  Slider', 'inmedical'),
                'id'      => $prefix . 'slider',
                'type'    => 'select',
                'options' => Inwave_Helper::getRevoSlider(),
                'default' => '',
            ),
            array(
                'name'    => esc_html__( 'Show preload', 'inmedical' ),
                'id'      => $prefix . 'show_preload',
                'type'    => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'inmedical' ),
                    'yes'   => esc_html__( 'Yes', 'inmedical' ),
                    'no'     => esc_html__( 'No', 'inmedical' ),
                ),
            ),
            array(
                'name' => esc_html__( 'Extra class', 'inmedical' ),
                'desc' => esc_html__( 'Add extra class for page content', 'inmedical' ),
                'default' => '',
                'id' => $prefix . 'page_class',
                'type' => 'text',
            ),
            array(
                'name' => esc_html__( 'Header Options', 'inmedical' ),
                'id'   => $prefix . 'header_options_title',
                'type' => 'title',
            ),
            array(
                'name'    => esc_html__( 'Header style', 'inmedical' ),
                'id'      => $prefix . 'header_option',
                'type'    => 'select',
                'options' => array(
					'' => esc_html__( 'Default', 'inmedical' ),
					'none'   => esc_html__( 'None', 'inmedical' ),
					'default'   => esc_html__( 'Header Style 1', 'inmedical' ),
					'v2'     => esc_html__( 'Header Style 2', 'inmedical' ),
					'v3'     => esc_html__( 'Header Style 3', 'inmedical' ),
					'v4'     => esc_html__( 'Header Style 4', 'inmedical' ),
					'v5'     => esc_html__( 'Header Style 5', 'inmedical' ),
					'v6'     => esc_html__( 'Header Style Children', 'inmedical' ),
                    'v7'     => esc_html__( 'Header Style Pet', 'inmedical' ),
                ),
            ),
            array(
                'name'    => esc_html__( 'Sticky Header', 'inmedical' ),
                'id'      => $prefix . 'header_sticky',
                'type'    => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'inmedical' ),
                    'yes'   => esc_html__( 'Yes', 'inmedical' ),
                    'no'     => esc_html__( 'No', 'inmedical' ),
                ),
            ),
            array(
                'name'    => esc_html__( 'Show Make Appointment in Header Style 1', 'inmedical' ),
                'id'      => $prefix . 'show_make_appointment',
                'type'    => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'inmedical' ),
                    'yes'   => esc_html__( 'Yes', 'inmedical' ),
                    'no'     => esc_html__( 'No', 'inmedical' ),
                ),
            ),
            array(
                'name' => esc_html__( 'Change logo', 'inmedical' ),
                'id'   => $prefix . 'logo',
                'type' => 'file',
            ),
            array(
                'name' => esc_html__( 'Change logo sticky', 'inmedical' ),
                'id'   => $prefix . 'logo_sticky',
                'type' => 'file',
            ),
            array(
                'name' => esc_html__( 'Page Heading Options', 'inmedical' ),
                'id'   => $prefix . 'page_heading_options_title',
                'type' => 'title',
            ),
            array(
                'name'    => esc_html__( 'Show page heading', 'inmedical' ),
                'id'      => $prefix . 'show_pageheading',
                'type'    => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'inmedical' ),
                    'yes'   => esc_html__( 'Yes', 'inmedical' ),
                    'no'     => esc_html__( 'No', 'inmedical' ),
                ),
            ),
            array(
                "name" => esc_html__("Tagline", 'inmedical'),
                "desc" => esc_html__("Tagline for page heading", 'inmedical'),
                "id" => $prefix . "tagline",
                "type" => "text"),
            array(
                'name' => esc_html__( 'Page heading background', 'inmedical' ),
                'id'   => $prefix . 'pageheading_bg',
                'type' => 'file',
            ),
            array(
                'name'    => esc_html__( 'Show page breadcrumb', 'inmedical' ),
                'id'      => $prefix . 'breadcrumbs',
                'type'    => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'inmedical' ),
                    'yes'   => esc_html__( 'Yes', 'inmedical' ),
                    'no'     => esc_html__( 'No', 'inmedical' ),
                ),
            ),
            array(
                'name' => esc_html__( 'Sidebar Options', 'inmedical' ),
                'id'   => $prefix . 'sidebar_options_title',
                'type' => 'title',
            ),
            array(
                'name'    => esc_html__( 'Sidebar Position', 'inmedical' ),
                'id'      => $prefix . 'sidebar_position',
                'type'    => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'inmedical' ),
                    'none'   => esc_html__( 'Without Sidebar', 'inmedical' ),
                    'right'     => esc_html__( 'Right', 'inmedical' ),
                    'left'     => esc_html__( 'Left', 'inmedical' ),
                    'bottom'     => esc_html__( 'Bottom', 'inmedical' ),
                ),
            ),
            array(
                'name'    => esc_html__( 'Sidebar', 'inmedical' ),
                'id'      => $prefix . 'sidebar_name',
                'type'    => 'select',
                'options' => $sideBars,
            ),
            array(
                'name'    => esc_html__( 'Primary Menu', 'inmedical' ),
                'id'      => $prefix . 'primary_menu',
                'type'    => 'select',
                'options' => $menuArr,
            ),
            array(
                'name' => esc_html__( 'Footer Options', 'inmedical' ),
                'id'   => $prefix . 'footer_options_title',
                'type' => 'title',
            ),
            array(
                'name'    => esc_html__( 'Footer style', 'inmedical' ),
                'id'      => $prefix . 'footer_option',
                'type'    => 'select',
                'options' => array(
                ''        => esc_html__( 'Default', 'inmedical' ),
                'default' => esc_html__( 'Footer version 1', 'inmedical' ),
                ),
            ),
            array(
                'name'    => esc_html__('Footer Background Color', 'inmedical'),
                'id'      => $prefix . 'footer_bg_color',
                'type'    => 'colorpicker',
                'default' => '',
            ),
            array(
                'name'    => esc_html__('Copyright Background Color', 'inmedical'),
                'id'      => $prefix . 'copyright_bg_color',
                'type'    => 'colorpicker',
                'default' => '',
            ),
            array(
                'name' => esc_html__( 'Change image footer widget contact information', 'inmedical' ),
                'id'   => $prefix . 'image-information',
                'type' => 'file',
            ),
            array(
                'id'      => $prefix . 'use_scroll_footer',
                'desc' => __('A footer area will be showed when you scroll to end page.', 'inmedical'),
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'inmedical' ),
                    '1' => __('Yes', 'inmedical'),
                    '0' => __('No', 'inmedical'),
                ),
            ),
            array(
                'name' => esc_html__( 'Change Background Image Footer Scroll', 'inmedical' ),
                'id'   => $prefix . 'bg_footer_scroll',
                'type' => 'file',
            ),
            array(
                'desc' => esc_html__('Please content footer scroll.', 'inmedical'),
                'id' => $prefix . 'content_footer_scroll',
                'type' => 'textarea'
            ),
        )
    );

    return $meta_boxes;
}