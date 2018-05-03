<?php

add_action('init', 'inwave_of_options');

if (!function_exists('inwave_of_options')) {
    function inwave_of_options()
    {
        global $wp_registered_sidebars;
        $sidebar_options[] = 'None';
         $sidebars = $wp_registered_sidebars;
        if (is_array($sidebars) && !empty($sidebars)) {
            foreach ($sidebars as $sidebar) {
                $sidebar_options[] = $sidebar['name'];
            }
        }

        //get slug menu in admin
        $menuArr = array();
        $menus = get_terms('nav_menu');
        foreach ( $menus as $menu ) {
            $menuArr[$menu->slug] = $menu->name;
        }

        //Access the WordPress Categories via an Array
        $of_categories = array();
        $of_categories_obj = get_categories('hide_empty=0');
        foreach ($of_categories_obj as $of_cat) {
            $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;
        }

        //Access the WordPress Pages via an Array
        $of_pages = array();
        $of_pages_obj = get_pages('sort_column=post_parent,menu_order');
        foreach ($of_pages_obj as $of_page) {
            $of_pages[$of_page->ID] = $of_page->post_name;
        }

        /*-----------------------------------------------------------------------------------*/
        /* TO DO: Add options/functions that use these */
        /*-----------------------------------------------------------------------------------*/
        $google_fonts = inwave_get_googlefonts(false);

        /*-----------------------------------------------------------------------------------*/
        /* The Options Array */
        /*-----------------------------------------------------------------------------------*/

        // Set the Options Array
        global $inwave_of_options;
        $inwave_of_options = array();

        // GENERAL SETTING
        $inwave_of_options[] = array("name" => esc_html__("General setting", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Show demo setting panel", 'inmedical'),
            "desc" => esc_html__("Check this box to active the setting panel. This panel should be shown only in demo mode", 'inmedical'),
            "id" => "show_setting_panel",
            "std" => 0,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show page heading", 'inmedical'),
            "desc" => esc_html__("Check this box to show or hide page heading", 'inmedical'),
            "id" => "show_page_heading",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show breadcrumbs", 'inmedical'),
            "desc" => esc_html__("Check to display the breadcrumbs in general. Uncheck to hide them.", 'inmedical'),
            "id" => "breadcrumbs",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show preload", 'inmedical'),
            "desc" => esc_html__("Check to display the preload page.", 'inmedical'),
            "id" => "show_preload",
            "std" => 0,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Retina support:", 'inmedical'),
            "desc" => esc_html__("Each time an image is uploaded, a higher quality version is created and stored with @2x added to the filename in the media upload folder. These @2x images will be loaded with high-resolution screens.", 'inmedical'),
            "id" => "retina_support",
            "std" => 0,
            "type" => "checkbox");
        
        $inwave_of_options[] = array("name" => esc_html__("Google API", 'inmedical'),
            "desc" => wp_kses(__('Use for process data from google service. Eg: map, photo, video... To get Google api, you can access via <a href="https://console.developers.google.com/" target="_blank">here</a>.', 'inmedical'), inwave_allow_tags('a')),
            "id" => "google_api",
            "std" => '',
            "type" => "text");

        $inwave_of_options[] = array(
			"name" => esc_html__("Layout", 'inmedical'),
            "desc" => esc_html__("Select boxed or wide layout.", 'inmedical'),
            "id" => "body_layout",
            "std" => "wide",
            "type" => "select",
            "options" => array(
                'boxed' => 'Boxed',
                'wide' => 'Wide',
            ));

        $inwave_of_options[] = array(
			"name" => esc_html__("Sidebar Position", 'inmedical'),
            "desc" => esc_html__("Select slide bar position", 'inmedical'),
            "id" => "sidebar_position",
            "std" => "right",
            "type" => "select",
            "options" => array(
                'none' => 'Without Sidebar',
                'right' => 'Right',
                'left' => 'Left',
                'bottom' => 'Bottom'
            ));

        $inwave_of_options[] = array("name" => "Background Image",
            "desc" => esc_html__("Please choose an image or insert an image url to use for the background.", 'inmedical'),
            "id" => "bg_image",
            "std" => "",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array(
			"name" => esc_html__("Background Image Size", 'inmedical'),
            "desc" => esc_html__("Select background image size.", 'inmedical'),
            "id" => "bg_size",
            "std" => 'cover',
            "type" => "select",
            "options" => array('auto' => esc_html__('auto', 'inmedical'), 'cover' => esc_html__('cover', 'inmedical'), 'contain' => esc_html__('contain', 'inmedical')));

        $inwave_of_options[] = array(
			"name" => esc_html__("Background Repeat", 'inmedical'),
            "desc" => esc_html__("Choose how the background image repeats.", 'inmedical'),
            "id" => "bg_repeat",
            "std" => "",
            "type" => "select",
            "options" => array('repeat' => esc_html__('repeat', 'inmedical'), 'repeat-x' => esc_html__('repeat-x', 'inmedical'), 'repeat-y' => esc_html__('repeat-y', 'inmedical'), 'no-repeat' => esc_html__('no-repeat', 'inmedical')));

        $inwave_of_options[] = array("name" => esc_html__("Develop mode", 'inmedical'),
            "desc" => esc_html__("Check this box to active develop mode. This option should be used only while developing this theme", 'inmedical'),
            "id" => "develop_mode",
            "std" => 0,
            "type" => "checkbox");

        //TYPO
        $inwave_of_options[] = array(
			"name" => esc_html__("Typography", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array( "name" => esc_html__("Body Font Family", 'inmedical'),
            "desc" => esc_html__("Select a font family for body text", 'inmedical') .'</br>'.esc_html__("- Home page Children, Pet choose font 'Chewy'", 'inmedical').'</br>'.esc_html__("- Home page Plastic Surgery choose font 'Merriweather'", 'inmedical'),
            "id" => "f_body",
            "std" => "Hind",
            "type" => "select",
            "options" => $google_fonts);
        $inwave_of_options[] = array( "name" => esc_html__("Body Font Settings", 'inmedical'),
            "desc" => esc_html__("Adjust the settings below to load different character sets and types for fonts. More character sets and types equals to slower page load.", 'inmedical'),
            "id" => "f_body_settings",
            "std" => "300,400,500,600,700,800,900",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Headings Font", 'inmedical'),
            "desc" => esc_html__("Select a font family for body text", 'inmedical') .'</br>'.esc_html__("- Home page Children, Pet choose font 'Chewy'", 'inmedical').'</br>'.esc_html__("- Home page Plastic Surgery choose font 'Merriweather'", 'inmedical'),
            "id" => "f_headings",
            "std" => "Poppins",
            "type" => "select",
            "options" => $google_fonts);
        $inwave_of_options[] = array( "name" => esc_html__("Headings Font Settings", 'inmedical'),
            "desc" => esc_html__("Adjust the settings below to load different character sets and types for fonts. More character sets and types equals to slower page load.", 'inmedical'),
            "id" => "f_headings_settings",
            "std" => "300,400,500,600,700",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Menu Font", 'inmedical'),
            "desc" => esc_html__("Select a font family for body text", 'inmedical') .'</br>'.esc_html__("- Home page Children, Pet choose font 'Chewy'", 'inmedical').'</br>'.esc_html__("- Home page Plastic Surgery choose font 'Merriweather'", 'inmedical'),
            "id" => "f_nav",
            "std" => "",
            "type" => "select",
            "options" => $google_fonts);
        $inwave_of_options[] = array( "name" => esc_html__("Menu Font Settings", 'inmedical'),
            "desc" => esc_html__("Adjust the settings below to load different character sets and types for fonts. More character sets and types equals to slower page load.", 'inmedical'),
            "id" => "f_nav_settings",
            "std" => "",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Default Font Size", 'inmedical'),
            "desc" => esc_html__("Default is 15px", 'inmedical'),
            "id" => "f_size",
            "std" => "15px",
            "type" => "text"
        );
        $inwave_of_options[] = array( "name" => esc_html__("Default Font Line Height", 'inmedical'),
            "desc" => esc_html__("Default is 28px", 'inmedical'),
            "id" => "f_lineheight",
            "std" => "28px",
            "type" => "text",
        );

        // COLOR PRESETS
        $inwave_of_options[] = array("name" => esc_html__("Color presets", 'inmedical'),
            "type" => "heading"
        );

        $inwave_of_options[] = array("name" => esc_html__("Primary Color", 'inmedical'),
            "desc" => esc_html__("Controls several items, ex: link hovers, highlights, and more.", 'inmedical').'</br>'.esc_html__("- Default choose code color '#39c5de'", 'inmedical') .'</br>'.esc_html__("- Home page Children choose code color '#e77c80'", 'inmedical').'</br>'.esc_html__("- Home page Plastic Surgery choose code color '#e182a8'", 'inmedical').'</br>'.esc_html__("- Home page Pet choose code color '#97b714'", 'inmedical'),
            "id" => "primary_color",
            "std" => "#3c8dc5", //#39c5de
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Background Color", 'inmedical'),
            "desc" => esc_html__("Select a background color.", 'inmedical'),
            "id" => "bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Content Background Color", 'inmedical'),
            "desc" => esc_html__("Controls the background color of the main content area.", 'inmedical'),
            "id" => "content_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Body Text Color", 'inmedical'),
            "desc" => esc_html__("Controls the text color of body font.", 'inmedical'),
            "id" => "body_text_color",
            "std" => "#777777",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Link Color", 'inmedical'),
            "desc" => esc_html__("Controls the color of all text links as well as the '>' in certain areas.", 'inmedical'),
            "id" => "link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Blockquote Color", 'inmedical'),
            "desc" => esc_html__("Controls the color of blockquote.", 'inmedical'),
            "id" => "blockquote_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array(
			"name" => esc_html__("Color Scheme", 'inmedical'),
            "desc" => "",
            "id" => "color_pagetitle_breadcrumb",
            "std" => "<h3>".esc_html__('Page Title & Breadcrumb Color', 'inmedical')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Page Title Color", 'inmedical'),
            "desc" => esc_html__("Controls the text color of the page title.", 'inmedical'),
            "id" => "page_title_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Page Title Background Color", 'inmedical'),
            "desc" => esc_html__("Controls background color of the page title.", 'inmedical'),
            "id" => "page_title_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Background Color", 'inmedical'),
            "desc" => esc_html__("Controls the background color of the breadcrumb.", 'inmedical'),
            "id" => "breadcrumbs_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Border Color", 'inmedical'),
            "desc" => esc_html__("Controls the Border color of the breadcrumb.", 'inmedical'),
            "id" => "breadcrumbs_bd_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Text Color", 'inmedical'),
            "desc" => esc_html__("Controls the text color of the breadcrumb font.", 'inmedical'),
            "id" => "breadcrumbs_text_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Link Color", 'inmedical'),
            "desc" => esc_html__("Controls the link color of the breadcrumb font.", 'inmedical'),
            "id" => "breadcrumbs_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Color Scheme", 'inmedical'),
            "desc" => "",
            "id" => "color_scheme_header",
            "std" => "<h3>".esc_html__('Header Color', 'inmedical')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Header Link Color", 'inmedical'),
            "desc" => esc_html__("Select a color for the header link.", 'inmedical'),
            "id" => "header_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Header Sub Link Color", 'inmedical'),
            "desc" => esc_html__("Select a color for the header sub link.", 'inmedical'),
            "id" => "header_sub_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Header Background Color", 'inmedical'),
            "desc" => esc_html__("Select a color for the header background.", 'inmedical'),
            "id" => "header_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Header Border Color", 'inmedical'),
            "desc" => esc_html__("Select a color for the header border.", 'inmedical'),
            "id" => "header_bd_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Color Scheme", 'inmedical'),
            "desc" => "",
            "id" => "color_scheme_font",
            "std" => "<h3>".esc_html__('Footer Color', 'inmedical')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");

        $inwave_of_options[] = array("name" => esc_html__("Footer Background Color", 'inmedical'),
            "desc" => esc_html__("Select a color for the footer background.", 'inmedical'),
            "id" => "footer_bg_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Border Color", 'inmedical'),
            "desc" => esc_html__("Select a color for the footer border.", 'inmedical'),
            "id" => "footer_border_color",
            "std" => "",
            "type" => "color");


        $inwave_of_options[] = array("name" => esc_html__("Footer Headings Color", 'inmedical'),
            "desc" => esc_html__("Controls the text color of the footer heading font.", 'inmedical'),
            "id" => "footer_headings_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Font Color", 'inmedical'),
            "desc" => esc_html__("Controls the text color of the footer font.", 'inmedical'),
            "id" => "footer_text_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Link Color", 'inmedical'),
            "desc" => esc_html__("Controls the text color of the footer link font.", 'inmedical'),
            "id" => "footer_link_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Color Scheme", 'inmedical'),
            "desc" => "",
            "id" => "color_copyright",
            "std" => "<h3>".esc_html__('Copyright Color', 'inmedical')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Copyright Background Color", 'inmedical'),
            "desc" => esc_html__("Controls the background color of the copyright section.", 'inmedical'),
            "id" => "copyright_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Copyright Text Color", 'inmedical'),
            "desc" => esc_html__("Controls the text color of the breadcrumb font.", 'inmedical'),
            "id" => "copyright_text_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Copyright Link Color", 'inmedical'),
            "desc" => esc_html__("Controls the link color of the breadcrumb font.", 'inmedical'),
            "id" => "copyright_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");

        //HEADER OPTIONS
        $inwave_of_options[] = array("name" => esc_html__("Header Options", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Header Info", 'inmedical'),
            "desc" => "",
            "id" => "header_info_content_options",
            "std" => "<h3>".esc_html__('Header Content Options', 'inmedical')."</h3>",
            "type" => "info");

        $inwave_of_options[] = array("name" => esc_html__("Select a Header Layout", 'inmedical'),
            "desc" => "",
            "id" => "header_layout",
            "std" => "v4",
            "type" => "images",
            "options" => array(
                "default" => get_template_directory_uri() . "/assets/images/header/default.jpg",
                "v2" => get_template_directory_uri() . "/assets/images/header/v2.png",
                "v3" => get_template_directory_uri() . "/assets/images/header/v3.jpg",
                "v4" => get_template_directory_uri() . "/assets/images/header/v4.jpg",
                "v5" => get_template_directory_uri() . "/assets/images/header/v5.jpg",
                "v6" => get_template_directory_uri() . "/assets/images/header/kid.jpg",
                "v7" => get_template_directory_uri() . "/assets/images/header/pet.jpg",
            ));
        $inwave_of_options[] = array("name" => esc_html__("Sticky Header", 'inmedical'),
            "desc" => esc_html__("Check to enable a fixed header when scrolling, uncheck to disable.", 'inmedical'),
            "id" => "header_sticky",
            "std" => '1',
            "type" => "checkbox");        

        $inwave_of_options[] = array(
			"name" => esc_html__("Logo", 'inmedical'),
            "desc" => esc_html__("Please choose an image file for your logo.", 'inmedical'),
            "id" => "logo",
            "std" => get_template_directory_uri() . "/assets/images/logo.png",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array(
            "name" => esc_html__("Logo Sticky", 'inmedical'),
            "desc" => esc_html__("Please choose an image file for your logo sticky.", 'inmedical'),
            "id" => "logo_sticky",
            "std" => get_template_directory_uri() . "/assets/images/logo_sticky.png",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array("name" => esc_html__("Show Seach Button", 'inmedical'),
            "desc" => esc_html__("Show or hidden search button in Header Style v2, v3, v4.", 'inmedical'),
            "id" => "show_search_form",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Make Appointment Button", 'inmedical'),
            "desc" => esc_html__("Show or hidden Make Appointment button in Header.", 'inmedical'),
            "id" => "show_make_appointment",
            "std" => '0',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Cart Button", 'inmedical'),
            "desc" => esc_html__("Show or hidden Cart button in Header Style v1, v4 .", 'inmedical'),
            "id" => "show_cart_button",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array(
            "name" => esc_html__("Make Appointment button link", 'inmedical'),
            "desc" => esc_html__("Make Appointment button link to show in header using", 'inmedical'),
            "id"   => "make_appointment_link",
            "std"  => "",
            "type" => "text"
        );

        $inwave_of_options[] = array(
            "name" => esc_html__("Make Appointment button text", 'inmedical'),
            "desc" => esc_html__("Make Appointment button text to show in header. Only header layout 2nd", 'inmedical'),
            "id"   => "make_appointment_text",
            "std"  => esc_html__("Make Appointment", 'inmedical'),
            "type" => "text"
        );

        $inwave_of_options[] = array("name" => esc_html__("Header contact", 'inmedical'),
            "desc" => esc_html__("Enter information contact for default header, v2, v3", 'inmedical'),
            "id" => "header-contact",
            "std" => '',
            "mod" => "",
            "type" => "textarea");

        $inwave_of_options[] = array("name" => esc_html__("Header contact V4", 'inmedical'),
            "desc" => esc_html__("Please enter text information contact for header v4.", 'inmedical'),
            "id" => "header-contact-v2",
            "std" => '',
            "mod" => "",
            "type" => "textarea");

        $inwave_of_options[] = array("name" => esc_html__("Menu Destination Height in Header version 3", 'inmedical'),
            "desc" => esc_html__("In pixels, ex: 10px", 'inmedical'),
            "id" => "height_destination_extend",
            "std" => "290px",
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Header Info", 'inmedical'),
            "desc" => "",
            "id" => "header_info_page_title_options",
            "std" => "<h3>".esc_html__("Page Title Bar Options", 'inmedical')."</h3>",
            "type" => "info");
        $inwave_of_options[] = array("name" => esc_html__("Page Title Height", 'inmedical'),
            "desc" => esc_html__("In pixels, ex: 10px", 'inmedical'),
            "id" => "page_title_height",
            "std" => "190px",
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Page Title Background", 'inmedical'),
            "desc" => esc_html__("Please choose an image or insert an image url to use for the page title background.", 'inmedical'),
            "id" => "page_title_bg",
            "std" => get_template_directory_uri() . "/assets/images/bg-page-heading.png",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array(
			"name" => esc_html__("Background Image Size", 'inmedical'),
            "desc" => esc_html__("Select Page Title background image size.", 'inmedical'),
            "id" => "page_title_bg_size",
            "std" => 'cover',
            "type" => "select",
            "options" => array(
				'auto' => esc_html__('auto', 'inmedical'),
				'cover' => esc_html__('cover', 'inmedical'),
				'contain' => esc_html__('contain', 'inmedical')
			)
		);

        $inwave_of_options[] = array("name" => esc_html__("Background Repeat", 'inmedical'),
            "desc" => esc_html__("Choose how the background image repeats.", 'inmedical'),
            "id" => "page_title_bg_repeat",
            "std" => "",
            "type" => "select",
            "options" => array('repeat' => esc_html__('repeat', 'inmedical'), 'repeat-x' => esc_html__('repeat-x', 'inmedical'), 'repeat-y' => esc_html__('repeat-y', 'inmedical'), 'no-repeat' => esc_html__('no-repeat', 'inmedical')));

        // FOOTER OPTIONS
        $inwave_of_options[] = array("name" => esc_html__("Footer options", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Footer style", 'inmedical'),
            "desc" => "",
            "id" => "footer_option",
            "std" => "default",
            "type" => "images",
            "options" => array(
            "default" => get_template_directory_uri() . "/assets/images/footer/footer-default.png",
            ));
        $inwave_of_options[] = array("name" => esc_html__("Footer Default columns", 'inmedical'),
            "id" => "footer_number_widget",
            "std" => "4",
            "type" => "select",
            "options" => array(
                '4' => '4',
                '3' => '3',
                '2' => '2',
                '1' => '1',
            ));

        $inwave_of_options[] = array("name" => esc_html__("Use Scroll Footer", 'inmedical'),
            "id" => "use_scroll_footer",
            "desc" => __("A footer area will be showed when you scroll to end page. Only for Page Template: Home Page Kid, Home Page Pet, Home Page plastic Surgery", 'inmedical'),
            "std" => "0",
            "type" => "select",
            "options" => array(
                '1' => __('Yes', 'inmedical'),
                '0' => __('No', 'inmedical'),
            ));

        $inwave_of_options[] = array(
            "name" => esc_html__("Background Footer Scroll", 'inmedical'),
            "desc" => esc_html__("Please choose an image file.", 'inmedical'),
            "id" => "bg_footer_scroll",
            "std" => "",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array("name" => esc_html__("Content Footer Scroll", 'inmedical'),
            "desc" => esc_html__("Please content footer scroll", 'inmedical'),
            "id" => "content_footer_scroll",
            "std" => wp_kses_post(__('
            <div class="title">Your chilrent need help?</div>
            <div class="sub-title">We review and accept patient’s requests on a 24/7 basis</div>
            <div class="button-appointment"><a href="#">Make an appointment</a></div>', 'inmedical')),
            "mod" => "",
            "type" => "textarea");

        $inwave_of_options[] = array("name" => esc_html__("Footer copyright", 'inmedical'),
            "desc" => esc_html__("Please enter text copyright for footer.", 'inmedical'),
            "id" => "footer-copyright",
            "std" => wp_kses_post(__("Copyright 2017 © <a href='#'> Medic Center</a>.  All rights reserved. All right reserved. Designed by <a href='#'> WordPress themes - InwaveThemes.</a>", 'inmedical')),
            "mod" => "",
            "type" => "textarea");

        //CUSTOM SIDEBAR
        $inwave_of_options[] = array("name" => esc_html__("Custom Sidebar", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Custom Sidebar", 'inmedical'),
            "desc" => esc_html__("Custom sidebar", 'inmedical'),
            "id" => "custom_sidebar",
            "type" => "addoption",
            'option_label' => esc_html__('Sidebar', 'inmedical'),
            'add_btn_text' => esc_html__('Add New Sidebar', 'inmedical')
        );

        // SHOP OPTIONS
        $inwave_of_options[] = array("name" => esc_html__("Shop options", 'inmedical'),
            "type" => "heading");

        $inwave_of_options[] = array("name" => esc_html__("Show Woocommerce Cart Icon in Top Navigation", 'inmedical'),
            "desc" => esc_html__("Check the box to show the Cart icon & Cart widget, uncheck to disable.", 'inmedical'),
            "id" => "woocommerce_cart_top_nav",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show Quick View Button", 'inmedical'),
            "desc" => esc_html__("Check the box to show the quick view button on product image.", 'inmedical'),
            "id" => "woocommerce_quickview",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Quick View Effect", 'inmedical'),
            "desc" => esc_html__("Select effect of the quick view box.", 'inmedical'),
            "id" => "woocommerce_quickview_effect",
            "std" => 'fadein',
            "type" => "select",
            "options" => array(
                'fadein' => esc_html__('Fadein', 'inmedical'),
                'slide' => esc_html__('Slide', 'inmedical'),
                'newspaper' => esc_html__('Newspaper', 'inmedical'),
                'fall' => esc_html__('Fall', 'inmedical'),
                'sidefall' => esc_html__('Side Fall', 'inmedical'),
                'blur' => esc_html__('Blur', 'inmedical'),
                'flip' => esc_html__('Flip', 'inmedical'),
                'sign' => esc_html__('Sign', 'inmedical'),
                'superscaled' => esc_html__('Super Scaled', 'inmedical'),
                'slit' => esc_html__('Slit', 'inmedical'),
                'rotate' => esc_html__('Rotate', 'inmedical'),
                'letmein' => esc_html__('Letmein', 'inmedical'),
                'makeway' => esc_html__('Makeway', 'inmedical'),
                'slip' => esc_html__('Slip', 'inmedical')
            ));
        $inwave_of_options[] = array("name" => esc_html__("Shop column", 'inmedical'),
            "desc" => esc_html__("Column in shop page.", 'inmedical'),
            "id" => "woocommerce_shop_column",
            "std" => '3',
            "type" => "select",
            "options" => array(
                '3' => '3',
                '4' => '4',
            ));
        $inwave_of_options[] = array("name" => esc_html__("Troubleshooting", 'inmedical'),
            "desc" => wp_kses(__("Woocommerce jquery cookie fix<br> Read more: <a href='http://docs.woothemes.com/document/jquery-cookie-fails-to-load/'>jquery-cookie-fails-to-load</a>", 'inmedical'), inwave_allow_tags(array('br', 'a'))),
            "id" => "fix_woo_jquerycookie",
            "std" => 0,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Blog", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Blog Listing", 'inmedical'),
            "desc" => "",
            "id" => "blog_single_post",
            "std" => "<h3>".esc_html__("Blog Listing", 'inmedical')."</h3>",
            "icon" => true,
            "type" => "info");
        $inwave_of_options[] = array("name" => esc_html__("Post Category Title", 'inmedical'),
            "desc" => esc_html__("Check the box to display the post category title in each post.", 'inmedical'),
            "id" => "blog_category_title_listing",
            "std" => 1,
            "type" => "checkbox");
		$inwave_of_options[] = array("name" => esc_html__("Show Post Tags", 'inmedical'),
            "desc" => esc_html__("Check the box to display blog post tags.", 'inmedical'),
            "id" => "show_post_tag",
            "std" => 0,
            "type" => "checkbox");
		$inwave_of_options[] = array("name" => esc_html__("Show Post Date", 'inmedical'),
            "desc" => esc_html__("Check the box to display blog post date.", 'inmedical'),
            "id" => "show_post_date",
            "std" => 1,
            "type" => "checkbox");
		$inwave_of_options[] = array("name" => esc_html__("Show Post Author", 'inmedical'),
            "desc" => esc_html__("Check the box to display blog post author.", 'inmedical'),
            "id" => "show_post_author",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show Post Comment", 'inmedical'),
            "desc" => esc_html__("Check the box to display blog post comment.", 'inmedical'),
            "id" => "show_post_comment",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array(
			"name" => esc_html__("Social Sharing Box", 'inmedical'),
            "desc" => esc_html__("Check the box to display the social sharing box in blog listing", 'inmedical'),
            "id" => "social_sharing_box_category",
            "std" => 0,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Blog Single Post", 'inmedical'),
            "desc" => "",
            "id" => "blog_single_post",
            "std" => "<h3>".esc_html__("Blog Single Post", 'inmedical')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");

        $inwave_of_options[] = array("name" => esc_html__("Featured Image on Single Post Page", 'inmedical'),
            "desc" => esc_html__("Check the box to display featured images on single post pages.", 'inmedical'),
            "id" => "featured_images_single",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Post Title", 'inmedical'),
            "desc" => esc_html__("Check the box to display the post title that goes below the featured images.", 'inmedical'),
            "id" => "blog_post_title",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Post Category Title", 'inmedical'),
            "desc" => esc_html__("Check the box to display the post category title that goes below the featured images.", 'inmedical'),
            "id" => "blog_category_title",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Author Info", 'inmedical'),
            "desc" => esc_html__("Check the box to display the author info in the post.", 'inmedical'),
            "id" => "author_info",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Related Posts", 'inmedical'),
            "desc" => esc_html__("Check the box to display related posts.", 'inmedical'),
            "id" => "related_posts",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Social Sharing Box", 'inmedical'),
            "desc" => esc_html__("Check the box to display the social sharing box.", 'inmedical'),
            "id" => "social_sharing_box",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Entry footer", 'inmedical'),
            "desc" => esc_html__("Check the box to display the tags and edit link (admin only).", 'inmedical'),
            "id" => "entry_footer",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");

        //SOCIAL MEDIA
        $inwave_of_options[] = array("name" => esc_html__("Social Media", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Social Sharing", 'inmedical'),
            "desc" => "",
            "id" => "social_sharing",
            "std" => "<h3>".esc_html__("Social Sharing", 'inmedical')."</h3>",
            "type" => "info");
        $inwave_of_options[] = array("name" => esc_html__("Facebook", 'inmedical'),
            "desc" => esc_html__("Check the box to show the facebook sharing icon in blog, woocommerce and portfolio detail page.", 'inmedical'),
            "id" => "sharing_facebook",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Twitter", 'inmedical'),
            "desc" => esc_html__("Check the box to show the twitter sharing icon in blog, woocommerce and portfolio detail page.", 'inmedical'),
            "id" => "sharing_twitter",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("LinkedIn", 'inmedical'),
            "desc" => esc_html__("Check the box to show the linkedin sharing icon in blog, woocommerce and portfolio detail page.", 'inmedical'),
            "id" => "sharing_linkedin",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Google Plus", 'inmedical'),
            "desc" => esc_html__("Check the box to show the g+ sharing icon in blog, woocommerce and portfolio detail page.", 'inmedical'),
            "id" => "sharing_google",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Tumblr", 'inmedical'),
            "desc" => esc_html__("Check the box to show the tumblr sharing icon in blog, woocommerce and portfolio detail page.", 'inmedical'),
            "id" => "sharing_tumblr",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Pinterest", 'inmedical'),
            "desc" => esc_html__("Check the box to show the pinterest sharing icon in blog, woocommerce and portfolio detail page.", 'inmedical'),
            "id" => "sharing_pinterest",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Email", 'inmedical'),
            "desc" => esc_html__("Check the box to show the email sharing icon in blog, woocommerce and portfolio detail page.", 'inmedical'),
            "id" => "sharing_email",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array(
            "name" => esc_html__("Social Link Configs", 'inmedical'),
            "desc" => "",
            "id" => "social_link_configs",
            "std" => '<h3>'.esc_html__("Social Link Configs", 'inmedical').'</h3>',
            "type" => "info",
        );
        $inwave_of_options[] = array("name" => esc_html__("Social links", 'inmedical'),
            "desc" => wp_kses_post(__("Add new social links. Awesome icon You can get at <a target='_blank' href='https://fortawesome.github.io/Font-Awesome/'>here</a>", 'inmedical')),
            "id" => "social_links",
            "std" => array(
                array(
                    'order' => 0,
                ),
                array(
                    'title' => __('Facebook', 'inmedical'),
                    'icon' => 'fa-facebook',
                    'link' => 'http://facebook.com'
                ),
                array(
                    'title' => __('Twitter', 'inmedical'),
                    'icon' => 'fa-twitter',
                    'link' => 'http://twitter.com'
                ),
                array(
                    'title' => __('Google Plush', 'inmedical'),
                    'icon' => 'fa-google',
                    'link' => 'http://google-plus.com'
                ),
                array(
                    'title' => __('Pinterest', 'inmedical'),
                    'icon' => 'fa-pinterest ',
                    'link' => 'http://pinterest.com'
                )
            ),
            "type" => "social_link"
        );

        //Twitter
        $inwave_of_options[] = array("name" => esc_html__("Twitter", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array(
            "type" => "sinfo",
            "std"=> wp_kses(__('Get them creating your Twitter Application <a href="https://dev.twitter.com/apps" target="_blank">here</a>', 'inmedical'), inwave_allow_tags('a')));

        $inwave_of_options[] = array( "name" => esc_html__("Consumer Key", 'inmedical'),
            "id" => "tw_consumer_key",
            "std" => "",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Consumer Secret", 'inmedical'),
            "id" => "tw_consumer_secret",
            "std" => "",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Access Token", 'inmedical'),
            "id" => "tw_access_token",
            "std" => "",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Access Token Secret", 'inmedical'),
            "id" => "tw_access_token_secret",
            "std" => "",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Twitter Username", 'inmedical'),
            "id" => "tw_username",
            "std" => "",
            "type" => "text");

        // IMPORT EXPORT
        $inwave_of_options[] = array("name" => esc_html__("Import Demo", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Import Demo Content", 'inmedical'),
            "desc" => wp_kses(__("We recommend you to <a href='https://wordpress.org/plugins/wordpress-reset/' target='_blank'>reset data</a>  & clean wp-content/uploads before import to prevent duplicate content!", 'inmedical'), inwave_allow_tags('a')),
            "id" => "demo_data",
            "std" => admin_url('themes.php?page=optionsframework') . "&import_data_content=true",
            "btntext" => esc_html__('Import Demo Content', 'inmedical'),
            "type" => "import_button");

        // BACKUP OPTIONS
        $inwave_of_options[] = array("name" => esc_html__("Backup Options", 'inmedical'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Backup and Restore Options", 'inmedical'),
            "id" => "of_backup",
            "std" => "",
            "type" => "backup",
            "desc" => esc_html__('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', 'inmedical'),
        );

        $inwave_of_options[] = array("name" => esc_html__("Transfer Theme Options Data", 'inmedical'),
            "id" => "of_transfer",
            "std" => "",
            "type" => "transfer",
            "desc" => esc_html__('You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".', 'inmedical'),
        );

    }//End function: inwave_of_options()
}//End chack if function exists: inwave_of_options()
?>
