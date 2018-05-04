<?php
/**
 * SMOF Admin
 *
 * @package monalisa
 * @subpackage  SMOF
 * @since       1.0
 * @author      Syamil MJ
 */


/**
 * Head Hook
 *
 * @since 1.0.0
 */
function inwave_of_head() { do_action( 'inwave_of_head' ); }

/**
 * Check options initial
 */

function inwave_of_option_check_setup(){
	global $inwave_theme_option;
	if(count($inwave_theme_option) < 10){
		inwave_of_option_setup();
	}
}

/**
 * Add default options upon activation else DB does not exist
 *
 * @since 1.0.0
 */
function inwave_of_option_setup()
{
	global $inwave_of_options, $inwave_options_machine;
	$inwave_options_machine = new inwave_Options_Machine($inwave_of_options);
	if (count(inwave_of_get_options()) < 10)
	{
		inwave_of_save_options($inwave_options_machine->Defaults);
	}
}

/**
 * Change activation message
 *
 * @since 1.0.0
 */
function inwave_of_admin_message() {

	//Tweaked the message on theme activate
	?>
	<script type="text/javascript">
		jQuery(function(){

			var message = '<p>This theme comes with an <a href="<?php echo admin_url('admin.php?page=optionsframework'); ?>">options panel</a> to configure settings. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';
			jQuery('.themes-php #message2').html(message);

		});
	</script>
	<?php

}

/**
 * Get header classes
 *
 * @since 1.0.0
 */
function inwave_of_get_header_classes_array()
{
	global $inwave_of_options;

	foreach ($inwave_of_options as $value)
	{
		if ($value['type'] == 'heading')
			$hooks[] = str_replace(' ','',strtolower($value['name']));
	}

	return $hooks;
}

/**
 * Get options from the database and process them with the load filter hook.
 *
 * @author Jonah Dahlquist
 * @since 1.0
 * @return array
 */
function inwave_of_get_options($key = null, $data = null) {

	do_action('inwave_of_get_options_before', array(
		'key'=>$key, 'data'=>$data
	));
	if ($key != null) { // Get one specific value
		$data = get_theme_mod($key, $data);
	} else { // Get all values
		$data = get_theme_mods();
	}

	$data = apply_filters('inwave_of_options_after_load', $data);
	do_action('inwave_of_option_setup_before', array(
		'key'=>$key, 'data'=>$data
	));

	/*
    if(is_admin() && count($data)>5){
        global $inwave_of_options;
        if(empty($inwave_of_options)){
            inwave_of_options();
        }
        foreach($inwave_of_options as $of_opt){
            if(isset($of_opt['id']) && $of_opt['id'] && isset($of_opt['std']) && !isset($data[$of_opt['id']])){
                $data[$of_opt['id']] = $of_opt['std'];
            }
        }
    }
    */
	return $data;

}

/**
 * Save options to the database after processing them
 *
 * @param $data Options array to save
 * @author Jonah Dahlquist
 * @since 1.0
 * @uses update_option()
 * @return void
 */

function inwave_of_save_options($data, $key = null) {
	global $inwave_theme_option;
	if (empty($data))
		return;
	/*
    do_action('inwave_of_save_options_before', array(
		'key'=>$key, 'data'=>$data
	));
	*/
	$data = apply_filters('inwave_of_options_before_save', $data);
	if ($key != null) { // Update one specific value
		if ($key == INWAVE_OF_BACKUPS) {
			unset($data['smof_init']); // Don't want to change this.
		}
		set_theme_mod($key, $data);
	} else { // Update all values in $data
		foreach ( $data as $k=>$v ) {
			if (!isset($inwave_theme_option[$k]) || $inwave_theme_option[$k] != $v) { // Only write to the DB when we need to
				set_theme_mod($k, $v);
			}
		}
	}

	$inwave_theme_option = inwave_of_get_options();
	do_action('inwave_of_save_options_after');

}


/**
 * For use in themes
 *
 * @since forever
 */

$inwave_theme_option = inwave_of_get_options();
$data = $inwave_theme_option;