<?php 
/**
 * SMOF Interface
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.0
 * @author      Syamil MJ
 */

/**
 * Admin Init
 *
 * @uses wp_verify_nonce()
 * @uses header()
 *
 * @since 1.0.0
 */
function inwave_of_admin_init()
{

	// Rev up the Options Machine
	global $inwave_of_options, $inwave_options_machine;
	$inwave_options_machine = new inwave_Options_Machine($inwave_of_options);
	$inwave_theme_option = inwave_of_get_options();
	$data = $inwave_theme_option;
	do_action('inwave_of_admin_init_before', array(
			'inwave_of_options'		=> $inwave_of_options,
			'options_machine'	=> $inwave_options_machine,
			'theme_option'			=> $inwave_theme_option
		));
	if (empty($inwave_theme_option['smof_init'])) { // Let's set the values if the theme's already been active
		inwave_of_save_options($inwave_options_machine->Defaults);
		inwave_of_save_options(date('r'), 'smof_init');
		$inwave_theme_option = inwave_of_get_options();
		$inwave_options_machine = new inwave_Options_Machine($inwave_of_options);
	}
	do_action('inwave_of_admin_init_after', array(
			'inwave_of_options'		=> $inwave_of_options,
			'options_machine'	=> $inwave_options_machine,
			'theme_option'			=> $inwave_theme_option
		));

}

/**
 * Create Options page
 *
 * @uses add_theme_page()
 * @uses add_action()
 *
 * @since 1.0.0
 */
function inwave_of_add_admin() {
	
    $of_page = add_theme_page( INWAVE_OF_THEMENAME, 'Theme Options', 'manage_options', 'optionsframework', 'inwave_options_page');
	// Add framework functionaily to the head individually
	add_action("admin_print_scripts-$of_page", 'inwave_of_load_only');
	add_action("admin_print_styles-$of_page",'inwave_of_style_only');
	
}


/**
 * Build Options page
 *
 * @since 1.0.0
 */
function inwave_options_page(){
	
	global $inwave_options_machine;
	
	/*
	//for debugging

	$inwave_theme_option = inwave_of_get_options();
	print_r($inwave_theme_option);

	*/	
	
	include_once( INWAVE_OF_PATH . 'inc/view.fontend_options.php' );

}

/**
 * Create Options page
 *
 * @uses wp_enqueue_style()
 *
 * @since 1.0.0
 */
function inwave_of_style_only(){
	wp_enqueue_style('iw-admin-style', INWAVE_OF_DIR . 'assets/css/admin-style.css');
	wp_enqueue_style('jquery-ui-custom-admin', INWAVE_OF_DIR .'assets/css/jquery-ui-custom.css');

	if ( !wp_style_is( 'wp-color-picker','registered' ) ) {
		wp_register_style( 'wp-color-picker', INWAVE_OF_DIR . 'assets/css/color-picker.min.css' );
	}
	wp_enqueue_style( 'wp-color-picker' );

}	

/**
 * Create Options page
 *
 * @uses add_action()
 * @uses wp_enqueue_script()
 *
 * @since 1.0.0
 */
function inwave_of_load_only()
{
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-input-mask', INWAVE_OF_DIR .'assets/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('tipsy', INWAVE_OF_DIR .'assets/js/jquery.tipsy.js', array( 'jquery' ));
	wp_enqueue_script('cookie', INWAVE_OF_DIR . 'assets/js/cookie.js', 'jquery');
    wp_enqueue_script('icheck', INWAVE_OF_DIR . 'assets/js/icheck.min.js', 'jquery');
	wp_enqueue_script('smof', INWAVE_OF_DIR .'assets/js/smof.js', array( 'jquery' ));


	// Enqueue colorpicker scripts for versions below 3.5 for compatibility
	if ( !wp_script_is( 'wp-color-picker', 'registered' ) ) {
		wp_register_script( 'iris', INWAVE_OF_DIR .'assets/js/iris.min.js', array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_register_script( 'wp-color-picker', INWAVE_OF_DIR .'assets/js/color-picker.min.js', array( 'jquery', 'iris' ) );
	}
	wp_enqueue_script( 'wp-color-picker' );
	

	/**
	 * Enqueue scripts for file uploader
	 */

	if ( function_exists( 'wp_enqueue_media' ) )
		wp_enqueue_media();

}


/**
 * Ajax Save Options
 *
 * @uses get_option()
 *
 * @since 1.0.0
 */
function inwave_of_ajax_callback()
{
	global $inwave_options_machine, $inwave_of_options;

	$nonce=$_POST['security'];

	if (! wp_verify_nonce($nonce, 'of_ajax_nonce') ) die('-1'); 
			
	//get options array from db
	$all = inwave_of_get_options();
	
	$save_type = $_POST['type'];

	//Uploads
	if($save_type == 'upload')
	{
		
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
		 
			$upload_tracking[] = $clickedID;
				
			//update $options array w/ image URL			  
			$upload_image = $all; //preserve current data
			
			$upload_image[$clickedID] = $uploaded_file['url'];
			
			inwave_of_save_options($upload_image);
		
				
		 if(!empty($uploaded_file['error'])) {echo esc_html('Upload Error: ' . $uploaded_file['error']); }
		 else { echo esc_url($uploaded_file['url']); } // Is the Response
		 
	}
	elseif($save_type == 'image_reset')
	{
			
			$id = $_POST['data']; // Acts as the name
			
			$delete_image = $all; //preserve rest of data
			$delete_image[$id] = ''; //update array key with empty value	 
			inwave_of_save_options($delete_image ) ;
	
	}
	elseif($save_type == 'backup_options')
	{
			
		$backup = $all;
		$backup['backup_log'] = date('r');

        update_option(INWAVE_OF_BACKUPS, $backup);
			
		die('1'); 
	}
	elseif($save_type == 'restore_options')
	{
			
		$inwave_theme_option = get_option(INWAVE_OF_BACKUPS);

		inwave_of_save_options($inwave_theme_option);
		
		die('1'); 
	}
	elseif($save_type == 'import_options'){
        $imported_data = $_POST['data'];
		$imported_data = stripcslashes($imported_data);
		$imported_data = json_decode($imported_data, true);
        inwave_of_save_options($imported_data);

		die('1'); 
	}
	elseif ($save_type == 'save')
	{

		wp_parse_str(stripslashes($_POST['data']), $inwave_theme_option);
		unset($inwave_theme_option['security']);
		unset($inwave_theme_option['of_save']);
		inwave_of_save_options($inwave_theme_option);

		die('1');
	}
	elseif ($save_type == 'reset')
	{
		inwave_of_save_options($inwave_options_machine->Defaults);
		
        die('1'); //options reset
	}

  	die();
}
