<?php
/**
 * SMOF Options Machine Class
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.0.0
 * @author      Syamil MJ
 */
class inwave_Options_Machine {

	/**
	 * PHP5 contructor
	 *
	 * @since 1.0.0
	 */
	function __construct($options) {
		
		$return = $this->optionsframework_machine($options);
		
		$this->Inputs = $return[0];
		$this->Menu = $return[1];
		$this->Defaults = $return[2];
		
	}

	/**
	 * Sanitize value
	 *
	 */
	static function satitize(&$value)
	{
		if(is_array($value)){
			foreach($value as $key => $_value){
				self::satitize($value[$key]);
			}
		}
		elseif(is_object($value)){
			foreach($value as $key => $_value){
				self::satitize($_value);
			}
		}
		else{
			$value = addslashes($value);
		}
	}

	/** 
	 * Sanitize option
	 *
	 * Sanitize & returns default values if don't exist
	 * 
	 * Notes:
	 *	- For further uses, you can check for the $value['type'] and performs
	 *	  more speficic sanitization on the option
	 *	- The ultimate objective of this function is to prevent the "undefined index"
	 *	  errors some authors are having due to malformed options array
	 */
	public static function sanitize_option( $value ) {

		$defaults = array(
			"name" 		=> "",
			"desc" 		=> "",
			"id" 		=> "",
			"std" 		=> "",
			"mod"		=> "",
			"type" 		=> ""
		);

		$value = wp_parse_args( $value, $defaults );

		return $value;

	}


	/**
	 * Process options data and build option fields
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function optionsframework_machine($options) {
		global $inwave_smof_output;
	    $inwave_theme_option = inwave_of_get_options();
		$data = $inwave_theme_option;
		$defaults = array();   
	    $counter = 0;
		$menu = '';
		$output = '';
		
		do_action('optionsframework_machine_before', array(
				'options'	=> $options,
				'theme_option'	=> $inwave_theme_option,
			));
		$output .= $inwave_smof_output;
		
		foreach ($options as $value) {
			
			// sanitize option
			$value = self::sanitize_option($value);
			if(!isset($inwave_theme_option[$value['id']])){
				$inwave_theme_option[$value['id']] = '';
			}
			
			$counter++;
			$val = '';
			
			//create array of defaults		
			if ($value['type'] == 'multicheck'){
				if (is_array($value['std'])){
					foreach($value['std'] as $i=>$key){
						$defaults[$value['id']][$key] = true;
					}
				} else {
						$defaults[$value['id']][$value['std']] = true;
				}
			} else {
				if (isset($value['id'])) $defaults[$value['id']] = $value['std'];
			}
			
			/* condition start */
			if(count($inwave_theme_option) > 1 || count($data) > 1){
			
			 if( $value['type'] == 'accordion' && $value['position'] == 'start' ) {
			 	$output .= '<div class="accordion">';
			 }

			//Start Heading
			 if ( $value['type'] != "heading" )
			 {
			 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
				
				//hide items in checkbox group
				$fold='';
				if (array_key_exists("fold",$value)) {
					if (isset($inwave_theme_option[$value['fold']]) && $inwave_theme_option[$value['fold']]) {
						$fold="f_".$value['fold']." ";
					} else {
						$fold="f_".$value['fold']." temphide ";
					}
				}
	
				$output .= '<div id="section-'.esc_attr($value['id']).'" class="'.esc_attr($fold).'section section-'.esc_attr($value['type']).' '. esc_attr($class) .'">'."\n";
				
				//only show header if 'name' value exists
				if($value['name']) $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
				
				$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";
	
			 } 
			 //End Heading

			//switch statement to handle various options type                              
			switch ( $value['type'] ) {
			
				//text input
				case 'text':
					
					$t_value = stripslashes($value['std']);
					$t_value = stripslashes($inwave_theme_option[$value['id']]);
					
					$mini ='';
					if(!isset($value['mod'])) $value['mod'] = '';
					if($value['mod'] == 'mini') { $mini = 'mini';}
					
					$output .= '<input class="of-input '.esc_attr($mini).'" name="'.esc_attr($value['id']).'" id="'. esc_attr($value['id']) .'" type="'. esc_attr($value['type']) .'" value="'. esc_attr($t_value) .'" />';
				break;
				
				//select option
				case 'select':
					$mini ='';
					if(!isset($value['mod'])) $value['mod'] = '';
					if($value['mod'] == 'mini') { $mini = 'mini';}
					$output .= '<div class="select_wrapper ' . $mini . '">';
					$output .= '<select class="select of-input" name="'.esc_attr($value['id']).'" id="'. esc_attr($value['id']) .'">';
					
					foreach ($value['options'] as $select_ID => $option) {
						$theValue = $select_ID;
						$output .= '<option id="' . esc_attr($select_ID) . '" value="'.esc_attr($theValue).'" ' . selected($inwave_theme_option[$value['id']], $theValue, false) . ' />'.$option.'</option>';
					 } 
					$output .= '</select></div>';
				break;
				
				//textarea option
				case 'textarea':	
					$cols = '8';
					$ta_value = '';
					
					if(isset($value['options'])){
							$ta_options = $value['options'];
							if(isset($ta_options['cols'])){
							$cols = $ta_options['cols'];
							} 
						}
						
						$ta_value = stripslashes($inwave_theme_option[$value['id']]);
						$output .= '<textarea class="of-input" name="'.esc_attr($value['id']).'" id="'. esc_attr($value['id']) .'" cols="'. esc_attr($cols) .'" rows="8">'.$ta_value.'</textarea>';
				break;
				
				//radiobox option
				case "radio":
					$checked = (isset($inwave_theme_option[$value['id']])) ? checked($inwave_theme_option[$value['id']], $option, false) : '';
					 foreach($value['options'] as $option=>$name) {
						$output .= '<input class="of-input of-radio" name="'.$value['id'].'" type="radio" value="'.$option.'" ' . checked($inwave_theme_option[$value['id']], $option, false) . ' /><label class="radio">'.$name.'</label><br/>';
					}
				break;
				
				//checkbox option
				case 'checkbox':
					if (!isset($inwave_theme_option[$value['id']])) {
						$inwave_theme_option[$value['id']] = 0;
					}
					
					$fold = '';
					if (array_key_exists("folds",$value)) $fold="fld ";
		
					$output .= '<input type="hidden" class="'.esc_attr($fold).'checkbox of-input" name="'.esc_attr($value['id']).'" id="'. esc_attr($value['id']) .'" value="0"/>';
					$output .= '<input type="checkbox" class="'.esc_attr($fold).'checkbox of-input" name="'.esc_attr($value['id']).'" id="'. esc_attr($value['id']) .'" value="1" '. checked($inwave_theme_option[$value['id']], 1, false) .' />';
				break;
				
				//multiple checkbox option
				case 'multicheck': 			
					(isset($inwave_theme_option[$value['id']]))? $multi_stored = $inwave_theme_option[$value['id']] : $multi_stored="";
								
					foreach ($value['options'] as $key => $option) {
						if (!isset($multi_stored[$key])) {$multi_stored[$key] = '';}
						$of_key_string = $value['id'] . '_' . $key;
						$output .= '<input type="checkbox" class="checkbox of-input" name="'.$value['id'].'['.$key.']'.'" id="'. esc_attr($of_key_string) .'" value="1" '. checked($multi_stored[$key], 1, false) .' /><label class="multicheck" for="'. $of_key_string .'">'. $option .'</label><br />';
					}			 
				break;
				
				// Color picker
				case "color":
					$default_color = '';
					if ( isset($value['std']) ) {
						if ( $inwave_theme_option[$value['id']] !=  $value['std'] )
							$default_color = ' data-default-color="' .$value['std'] . '" ';
					}
					$output .= '<input name="' .esc_attr($value['id']) . '" id="' . esc_attr($value['id']) . '" class="of-color"  type="text" value="' . esc_attr($inwave_theme_option[$value['id']]) . '"' . $default_color .' />';
		 	
				break;

				//typography option	
				case 'typography':
				
					$typography_stored = isset($inwave_theme_option[$value['id']]) ? $inwave_theme_option[$value['id']] : $value['std'];
					
					/* Font Size */
					
					if(isset($typography_stored['size'])) {
						$output .= '<div class="select_wrapper typography-size" original-title="Font size">';
						$output .= '<select class="of-typography of-typography-size select" name="'.esc_attr($value['id']).'[size]" id="'. esc_attr($value['id']).'_size">';
							for ($i = 9; $i < 20; $i++){ 
								$test = $i.'px';
								$output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $test, false) . '>'. $i .'px</option>'; 
								}
				
						$output .= '</select></div>';
					
					}
					
					/* Line Height */
					if(isset($typography_stored['height'])) {
					
						$output .= '<div class="select_wrapper typography-height" original-title="'.esc_attr(esc_html__('Line height', 'inmedical')).'">';
						$output .= '<select class="of-typography of-typography-height select" name="'.esc_attr($value['id']).'[height]" id="'. esc_attr($value['id']).'_height">';
							for ($i = 20; $i < 38; $i++){ 
								$test = $i.'px';
								$output .= '<option value="'. $i .'px" ' . selected($typography_stored['height'], $test, false) . '>'. $i .'px</option>'; 
								}
				
						$output .= '</select></div>';
					
					}
						
					/* Font Face */
					if(isset($typography_stored['face'])) {
					
						$output .= '<div class="select_wrapper typography-face" original-title="'.esc_attr(esc_html__('Font family', 'inmedical')).'">';
						$output .= '<select class="of-typography of-typography-face select" name="'.esc_attr($value['id']).'[face]" id="'. esc_attr($value['id']).'_face">';
						
						$faces = array('arial'=> esc_html__('Arial', 'inmedical'),
										'verdana'=> esc_html__('Verdana', 'inmedical'),
										'trebuchet'=> esc_html__('Trebuchet', 'inmedical'),
										'georgia' => esc_html__('Georgia', 'inmedical'),
										'times'=> esc_html__('Times New Roman', 'inmedical'),
										'tahoma'=> esc_html__('Tahoma, Geneva', 'inmedical'),
										'palatino'=> esc_html__('Palatino', 'inmedical'),
										'helvetica'=> esc_html__('Helvetica', 'inmedical'));
						foreach ($faces as $i=>$face) {
							$output .= '<option value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
						}			
										
						$output .= '</select></div>';
					
					}
					
					/* Font Weight */
					if(isset($typography_stored['style'])) {
					
						$output .= '<div class="select_wrapper typography-style">';
						$output .= '<select class="of-typography of-typography-style select" name="'.esc_attr($value['id']).'[style]" id="'. esc_attr($value['id']).'_style">';
						$styles = array('normal'=>'Normal',
										'italic'=>'Italic',
										'bold'=>'Bold',
										'bold italic'=>'Bold Italic');
										
						foreach ($styles as $i=>$style){
						
							$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';		
						}
						$output .= '</select></div>';
					
					}
					
					/* Font Color */
					if(isset($typography_stored['color'])) {
					
						$output .= '<div id="' . esc_attr($value['id']) . '_color_picker" class="colorSelector typography-color"><div style="background-color: '.esc_attr($typography_stored['color']).'"></div></div>';
						$output .= '<input class="of-color of-typography of-typography-color" original-title="Font color" name="'.$value['id'].'[color]" id="'. esc_attr($value['id']) .'_color" type="text" value="'. $typography_stored['color'] .'" />';
					
					}
					
				break;
				
				//border option
				case 'border':
						
					/* Border Width */
					$border_stored = $inwave_theme_option[$value['id']];
					
					$output .= '<div class="select_wrapper border-width">';
					$output .= '<select class="of-border of-border-width select" name="'.esc_attr($value['id']).'[width]" id="'. esc_attr($value['id']).'_width">';
						for ($i = 0; $i < 21; $i++){ 
						$output .= '<option value="'. $i .'" ' . selected($border_stored['width'], $i, false) . '>'. $i .'</option>';				 }
					$output .= '</select></div>';
					
					/* Border Style */
					$output .= '<div class="select_wrapper border-style">';
					$output .= '<select class="of-border of-border-style select" name="'.esc_attr($value['id']).'[style]" id="'. esc_attr($value['id']).'_style">';
					
					$styles = array('none'=>'None',
									'solid'=>'Solid',
									'dashed'=>'Dashed',
									'dotted'=>'Dotted');
									
					foreach ($styles as $i=>$style){
						$output .= '<option value="'. $i .'" ' . selected($border_stored['style'], $i, false) . '>'. $style .'</option>';		
					}
					
					$output .= '</select></div>';
					
					/* Border Color */		
					$output .= '<div id="' . esc_attr($value['id']) . '_color_picker" class="colorSelector"><div style="background-color: '.esc_attr($border_stored['color']).'"></div></div>';
					$output .= '<input class="of-color of-border of-border-color" name="'.esc_attr($value['id']).'[color]" id="'. esc_attr($value['id']) .'_color" type="text" value="'. esc_attr($border_stored['color']) .'" />';
					
				break;
				
				//images checkbox - use image as checkboxes
				case 'images':
				
					$i = 0;
					
					$select_value = (isset($inwave_theme_option[$value['id']])) ? $inwave_theme_option[$value['id']] : '';
					
					foreach ($value['options'] as $key => $option) 
					{ 
					$i++;
			
						$checked = '';
						$selected = '';
						if(NULL!=checked($select_value, $key, false)) {
							$checked = checked($select_value, $key, false);
							$selected = 'of-radio-img-selected';  
						}
						$output .= '<span>';
						$output .= '<input type="radio" id="of-radio-img-' . esc_attr($value['id'] . $i) . '" class="checkbox of-radio-img-radio" value="'.esc_attr($key).'" name="'.esc_attr($value['id']).'" '.$checked.' />';
						$output .= '<div class="of-radio-img-label">'. $key .'</div>';
						$output .= '<img data-rel="of-radio-img-' . esc_attr($value['id'] . $i) . '" src="'.esc_url($option).'" alt="" class="of-radio-img-img '. esc_attr($selected) .'" />';
						$output .= '</span>';				
					}
					
				break;
				
				//info (for small intro box etc)
				case "info":
					$info_text = $value['std'];
					$output .= '<div class="of-info">'.$info_text.'</div>';
				break;
				case "sinfo":
					$info_text = $value['std'];
					$output .= '<div class="of-sinfo">'.$info_text.'</div>';
				break;
				case "accordion":
					$info_text = $value['std'];
					$output .= '<div class="of-info">'.$info_text.'<span class="dashicons dashicons-plus"></span></div>';
				break;
				
				//display a single image
				case "image":
					$src = $value['std'];
					$output .= '<img src="'.esc_url($src).'">';
				break;
				case "button":
					$src = $value['std'];
					$output .= '<a href="' . esc_url($src) . '" class="button-primary">' . $value['btntext'] . '</a>';
				break;
                case 'import_button':
                    $src = $value['std'];
                    $output .= '<div class="import-block">';
                    $output .= '<div class="import-block-title">Select the data you want to import: </div>';
                    $output .= '<div class="iw-data-list">';
					$output .= '<div class="data-list">';
                    $import_types = inwave_get_import_data_types();
                    foreach($import_types['post-type'] as $data_key => $data_value){
                        $output .= '<div class="iw-data-option"><input class="post-type" name="post-type-'.esc_attr($data_key).'" type="checkbox" checked="checked" value="1" /> '.$data_value.'</div>';
                    }
                    foreach($import_types['data-type'] as $data_key => $data_value){
                        $output .= '<div class="iw-data-option"><input class="data-type" name="data-type-'.esc_attr($data_key).'" type="checkbox" checked="checked" value="1" /> '.$data_value.'</div>';
                    }
                    $output .= '</div>';
					$output .= '<div class="system-required">';
					$output .= '<h3>' . esc_html__('System Requirements', 'inmedical') . '</h3>';
					$output .= self::getSystemRequireStatus();
					$output .= '</div>';
					$output .= '<div class="clear"></div>';
					$output .= '</div>';
                    $output .= '<div class="iw-import-button"><a href="' . esc_url($src) . '" class="button-primary">' . esc_html($value['btntext']) . '</a></div>';
                    $output .= '<div class="iw-loading-progress"><div class="iw-loading-bar"><div class="iw-loading-state"></div><div class="iw-loading-info">Progress: 0%...</div></div><div class="iw-import-output">Prepare data...</div></div>';
                    $output .= '</div>';

                    break;
				//tab heading
				case 'heading':
					if($counter >= 2){
					   $output .= '</div>'."\n";
					}
					//custom icon
					$icon = '';
					if(isset($value['icon'])){
						$icon = ' style="background-image: url('. $value['icon'] .');"';
					}
					$header_class = str_replace(' ','',strtolower($value['name']));
					$header_class = str_replace('/','',$header_class);
					$jquery_click_hook = str_replace(' ', '', strtolower($value['name']) );
					$jquery_click_hook = str_replace('/', '', $jquery_click_hook );
					$jquery_click_hook = "of-option-" . $jquery_click_hook;
					$menu .= '<li class="'. esc_html($header_class) .'"><a title="'.  esc_attr($value['name']) .'" href="#'.  esc_attr($jquery_click_hook)  .'"'. $icon .'>'.  esc_html($value['name']) .'</a></li>';
					$output .= '<div class="group" id="'. esc_attr($jquery_click_hook)  .'"><h2>'.$value['name'].'</h2>'."\n";
				break;
				
				//drag & drop slide manager
				case 'slider':
					$output .= '<div class="slider"><ul id="'.esc_attr($value['id']).'">';
					$slides = $inwave_theme_option[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= inwave_Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= inwave_Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button slide_add_button">'.esc_html__('Add New Slide', 'inmedical').'</a></div>';
					
				break;
				//drag & drop option
				case 'addoption':
					$output .= '<input type="hidden" class="slide of-input order" name="'. esc_attr($value['id']) .'[0][order]" id="'. esc_attr($value['id']).'_0_slide_order" value="" />';
					$output .= '<div class="slider"><ul id="'.esc_attr($value['id']).'">';
					$addoptions = $inwave_theme_option[$value['id']];
					$count = count($addoptions);
					$label = isset($value['option_label']) ? $value['option_label'] : esc_html__('Option', 'inmedical');
					$add_btn_text = isset($value['add_btn_text']) ? $value['add_btn_text'] : esc_html__('Add New Option', 'inmedical');
					$i = 0;
					if ($count > 1) {
						unset($addoptions[0]);
						foreach ($addoptions as $addoption) {
							$oldorder = $addoption['order'];
							$i++;
							$order = $i;
							$output .= inwave_Options_Machine::optionsframework_addoption_function($value['id'], $value['std'], $label, $oldorder, $order);
						}
					}
					$output .= '</ul>';
					$output .= '<a href="'.esc_url($label).'" class="button addoption_add_button">'.$add_btn_text.'</a></div>';
				break;

				case 'social_link':
					$output .= '<input type="hidden" class="slide of-input order" name="'. esc_attr($value['id']) .'[0][order]" id="'. esc_attr($value['id']).'_0_slide_order" value="" />';
					$output .= '<div class="slider"><ul id="'.esc_attr($value['id']).'">';
					$addoptions = isset($inwave_theme_option[$value['id']]) ? $inwave_theme_option[$value['id']] : $value['std'];
					$count = count($addoptions);
					$label = isset($value['option_label']) ? $value['option_label'] : esc_html__('Social', 'inmedical');
					$add_btn_text = isset($value['add_btn_text']) ? $value['add_btn_text'] : esc_html__('Add New Social', 'inmedical');
					$i = 0;
					if ($count > 1) {
						unset($addoptions[0]);
						foreach ($addoptions as $addoption) {
							$i++;
							$order = $i;
							$output .= inwave_Options_Machine::optionsframework_social_link_function($value['id'], $addoption, $order);
						}
					}
					$output .= '</ul>';
					$output .= '<a href="'.esc_url($label).'" class="button add_iconlink_add_button">'.$add_btn_text.'</a></div>';
				break;
				
				//drag & drop block manager
				case 'sorter':

				    // Make sure to get list of all the default blocks first
				    $all_blocks = $value['std'];

				    $temp = array(); // holds default blocks
				    $temp2 = array(); // holds saved blocks

					foreach($all_blocks as $blocks) {
					    $temp = array_merge($temp, $blocks);
					}

				    $sortlists = isset($data[$value['id']]) && !empty($data[$value['id']]) ? $data[$value['id']] : $value['std'];

				    foreach( $sortlists as $sortlist ) {
					$temp2 = array_merge($temp2, $sortlist);
				    }

				    // now let's compare if we have anything missing
				    foreach($temp as $k => $v) {
					if(!array_key_exists($k, $temp2)) {
					    $sortlists['disabled'][$k] = $v;
					}
				    }

				    // now check if saved blocks has blocks not registered under default blocks
				    foreach( $sortlists as $key => $sortlist ) {
					foreach($sortlist as $k => $v) {
					    if(!array_key_exists($k, $temp)) {
						unset($sortlist[$k]);
					    }
					}
					$sortlists[$key] = $sortlist;
				    }

				    // assuming all sync'ed, now get the correct naming for each block
				    foreach( $sortlists as $key => $sortlist ) {
					foreach($sortlist as $k => $v) {
					    $sortlist[$k] = $temp[$k];
					}
					$sortlists[$key] = $sortlist;
				    }

				    $output .= '<div id="'.esc_attr($value['id']).'" class="sorter">';


				    if ($sortlists) {

					foreach ($sortlists as $group=>$sortlist) {

					    $output .= '<ul id="'.esc_attr($value['id']).'_'.$group.'" class="sortlist_'.esc_attr($value['id']).'">';
					    $output .= '<h3>'.$group.'</h3>';

					    foreach ($sortlist as $key => $list) {

						$output .= '<input class="sorter-placebo" type="hidden" name="'.esc_attr($value['id']).'['.esc_attr($group).'][placebo]" value="placebo">';

						if ($key != "placebo") {

						    $output .= '<li id="'.esc_attr($key).'" class="sortee">';
						    $output .= '<input class="position" type="hidden" name="'.esc_attr($value['id']).'['.esc_attr($group).']['.esc_attr($key).']" value="'.esc_attr($list).'">';
						    $output .= $list;
						    $output .= '</li>';

						}

					    }

					    $output .= '</ul>';
					}
				    }

				    $output .= '</div>';
				break;
				
				//background images option
				case 'tiles':
					
					$i = 0;
					$select_value = isset($inwave_theme_option[$value['id']]) && !empty($inwave_theme_option[$value['id']]) ? $inwave_theme_option[$value['id']] : '';
					if (is_array($value['options'])) {
						foreach ($value['options'] as $key => $option) { 
						$i++;
				
							$checked = '';
							$selected = '';
							if(NULL!=checked($select_value, $option, false)) {
								$checked = checked($select_value, $option, false);
								$selected = 'of-radio-tile-selected';  
							}
							$output .= '<span>';
							$output .= '<input type="radio" id="of-radio-tile-' . esc_attr($value['id'] . $i) . '" class="checkbox of-radio-tile-radio" value="'.esc_attr($option).'" name="'.esc_attr($value['id']).'" '.$checked.' />';
							$output .= '<div class="of-radio-tile-img '. $selected .'" style="background: url('.$option.')" onClick="document.getElementById(\'of-radio-tile-'. esc_attr($value['id'] . $i).'\').checked = true;"></div>';
							$output .= '</span>';				
						}
					}
					
				break;
				
				//backup and restore options data
				case 'backup':
				
					$instructions = $value['desc'];
					$backup = get_option(INWAVE_OF_BACKUPS);
					$init = inwave_of_get_options('smof_init');

					if(!isset($backup['backup_log'])) {
						$log = 'No backups yet';
					} else {
						$log = $backup['backup_log'];
					}
					
					$output .= '<div class="backup-box">';
					$output .= '<div class="instructions">'.$instructions."\n";
					$output .= '<p><strong>'. esc_html__('Last Backup : ','inmedical').'<span class="backup-log">'.$log.'</span></strong></p></div>'."\n";
					$output .= '<a href="#" id="of_backup_button" class="button">'.esc_html__('Backup Options','inmedical').'</a>';
					$output .= '<a href="#" id="of_restore_button" class="button">'.esc_html__('Restore Options','inmedical').'</a>';
					$output .= '</div>';
				
				break;
				
				//export or import data between different installs
				case 'transfer':
					$instructions = $value['desc'];
					self::satitize($inwave_theme_option);
					if(defined('JSON_UNESCAPED_SLASHES')){
						$output .= '<textarea id="export_data" rows="8">'.json_encode($inwave_theme_option, JSON_UNESCAPED_SLASHES) .'</textarea>'."\n";
					}else{
						$output .= '<textarea id="export_data" rows="8">'.json_encode($inwave_theme_option, 64) .'</textarea>'."\n";
					}
					$output .= '<a href="#" id="of_import_button" class="button">'.esc_html__('Import Options','inmedical').'</a>';
				
				break;
				
				// google font field
				case 'select_google_font':
					$output .= '<div class="select_wrapper">';
					$output .= '<select class="select of-input google_font_select" name="'.esc_attr($value['id']).'" id="'. esc_attr($value['id']) .'">';
					foreach ($value['options'] as $select_key => $option) {
						$output .= '<option value="'.$select_key.'" ' . selected((isset($inwave_theme_option[$value['id']]))? $inwave_theme_option[$value['id']] : "", $option, false) . ' />'.$option.'</option>';
					} 
					$output .= '</select></div>';
					
					if(isset($value['preview']['text'])){
						$g_text = $value['preview']['text'];
					} else {
						$g_text = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
					}
					if(isset($value['preview']['size'])) {
						$g_size = 'style="font-size: '. esc_attr($value['preview']['size']) .';"';
					} else { 
						$g_size = '';
					}
					
					$output .= '<p class="'.$value['id'].'_ggf_previewer google_font_preview" '. $g_size .'>'. $g_text .'</p>';
				break;
				
				//JQuery UI Slider
				case 'sliderui':
					$s_val = $s_min = $s_max = $s_step = $s_edit = '';//no errors, please
					
					$s_val  = stripslashes($inwave_theme_option[$value['id']]);
					
					if(!isset($value['min'])){ $s_min  = '0'; }else{ $s_min = $value['min']; }
					if(!isset($value['max'])){ $s_max  = $s_min + 1; }else{ $s_max = $value['max']; }
					if(!isset($value['step'])){ $s_step  = '1'; }else{ $s_step = $value['step']; }
					
					if(!isset($value['edit'])){ 
						$s_edit  = ' readonly="readonly"'; 
					}
					else
					{
						$s_edit  = '';
					}
					
					if ($s_val == '') $s_val = $s_min;
					
					//values
					$s_data = 'data-id="'.esc_attr($value['id']).'" data-val="'.esc_attr($s_val).'" data-min="'.esc_attr($s_min).'" data-max="'.esc_attr($s_max).'" data-step="'.esc_attr($s_step).'"';
					
					//html output
					$output .= '<input type="text" name="'.esc_attr($value['id']).'" id="'.esc_attr($value['id']).'" value="'.esc_attr($s_val) .'" class="mini" '. esc_attr($s_edit) .' />';
					$output .= '<div id="'.esc_attr($value['id']).'-slider" class="smof_sliderui" '. esc_attr($s_data) .'></div>';
					
				break;
				
				
				//Switch option
				case 'switch':
					if (!isset($inwave_theme_option[$value['id']])) {
						$inwave_theme_option[$value['id']] = 0;
					}
					
					$fold = '';
					if (array_key_exists("folds",$value)) $fold="s_fld ";
					
					$cb_enabled = $cb_disabled = '';//no errors, please
					
					//Get selected
					if ($inwave_theme_option[$value['id']] == 1){
						$cb_enabled = ' selected';
						$cb_disabled = '';
					}else{
						$cb_enabled = '';
						$cb_disabled = ' selected';
					}
					
					//Label ON
					if(!isset($value['on'])){
						$on = "On";
					}else{
						$on = $value['on'];
					}
					
					//Label OFF
					if(!isset($value['off'])){
						$off = "Off";
					}else{
						$off = $value['off'];
					}
					
					$output .= '<p class="switch-options">';
						$output .= '<label class="'.esc_attr($fold).'cb-enable'. esc_attr($cb_enabled) .'" data-id="'.esc_attr($value['id']).'"><span>'. $on .'</span></label>';
						$output .= '<label class="'.esc_attr($fold).'cb-disable'. esc_attr($cb_disabled) .'" data-id="'.esc_attr($value['id']).'"><span>'. $off .'</span></label>';
						
						$output .= '<input type="hidden" class="'.esc_attr($fold).'checkbox of-input" name="'.esc_attr($value['id']).'" id="'. esc_attr($value['id']) .'" value="0"/>';
						$output .= '<input type="checkbox" id="'.esc_attr($value['id']).'" class="'.esc_attr($fold).'checkbox of-input main_checkbox" name="'.esc_attr($value['id']).'"  value="1" '. checked($inwave_theme_option[$value['id']], 1, false) .' />';
						
					$output .= '</p>';
					
				break;

				// Uploader 3.5
				case "upload":
				case "media":

					if(!isset($value['mod'])) $value['mod'] = '';
					
					$u_val = '';
					if(isset($inwave_theme_option[$value['id']]) && $inwave_theme_option[$value['id']]){
						$u_val = stripslashes($inwave_theme_option[$value['id']]);
					}

					$output .= inwave_Options_Machine::optionsframework_media_uploader_function($value['id'],$u_val, $value['mod']);
					
				break;
				
			}

			do_action('optionsframework_machine_loop', array(
					'options'	=> $options,
					'theme_option'	=> $inwave_theme_option,
					'defaults'	=> $defaults,
					'counter'	=> $counter,
					'menu'		=> $menu,
					'output'	=> $output,
					'value'		=> $value
				));
			$output .= $inwave_smof_output;
			
			//description of each option
			if ( $value['type'] != 'heading') { 
				if(!isset($value['desc'])){ $explain_value = ''; } else{ 
					$explain_value = '<div class="explain">'. $value['desc'] .'</div>'."\n";
				} 
				$output .= '</div>'.$explain_value."\n";
				$output .= '<div class="clear"> </div></div></div>'."\n";
				}

			   if( $value['type'] == 'accordion' && $value['position'] == 'end' ) {
			 	 $output .= '</div>';
			   }
			
			} /* condition empty end */
		   
		}
		
	    $output .= '</div>';

	    do_action('optionsframework_machine_after', array(
					'options'		=> $options,
					'theme_option'		=> $inwave_theme_option,
					'defaults'		=> $defaults,
					'counter'		=> $counter,
					'menu'			=> $menu,
					'output'		=> $output,
					'value'			=> $value
				));
	    $output .= $inwave_smof_output;
	    
	    return array($output,$menu,$defaults);
	    
	}


	/**
	 * Native media library uploader
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_media_uploader_function($id,$std,$mod){

	    $data = inwave_of_get_options();
	    $inwave_theme_option = inwave_of_get_options();
		
		$uploader = '';
	    $upload = isset($inwave_theme_option[$id]) ? $inwave_theme_option[$id] : '';
		$hide = '';
		
		if ($mod == "min") {$hide ='hide';}
		
	    if ( $upload != "") { $val = $upload; } else {$val = $std;}
	    
		$uploader .= '<input class="'.esc_attr($hide).' upload of-input" name="'. esc_attr($id).'" id="'. esc_attr($id) .'_upload" value="'. esc_attr($val) .'" />';
		
		//Upload controls DIV
		$uploader .= '<div class="upload_button_div">';
		//If the user has WP3.5+ show upload/remove button
		if ( function_exists( 'wp_enqueue_media' ) ) {
			$uploader .= '<span class="button media_upload_button" id="'.esc_attr($id).'">'.esc_html__('Upload', 'inmedical').'</span>';
			
			if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
			$uploader .= '<span class="button remove-image '. esc_attr($hide).'" id="reset_'. esc_attr($id) .'" title="' . esc_attr($id) . '">'.esc_html__('Remove', 'inmedical'). '</span>';
		}
		else 
		{
			$output .= '<p class="upload-notice"><i>'.esc_html__('Upgrade your version of WordPress for full media support', 'inmedical').'</i></p>';
		}

		$uploader .='</div>' . "\n";

		//Preview
		$uploader .= '<div class="screenshot">';
		if(!empty($upload)){	
			if(substr_count($upload,'http://') || substr_count($upload,'https://')){
				$imageurl = $upload;
			}else{
				$imageurl = get_site_url().'/'.$upload;
			}
	    	$uploader .= '<a class="of-uploaded-image" href="'. esc_url($imageurl) . '">';
	    	$uploader .= '<img class="of-option-image bg-grey" id="image_'.esc_attr($id).'" src="'.esc_url($imageurl).'" alt="" />';
	    	$uploader .= '</a>';			
			}
		$uploader .= '</div>';
		$uploader .= '<div class="clear"></div>' . "\n"; 
	
		return $uploader;
		
	}

	/**
	 * Drag and drop slides manager
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_slider_function($id,$std,$oldorder,$order){
		
	    $data = inwave_of_get_options();
	    $inwave_theme_option = inwave_of_get_options();
		
		$slider = '';
		$slide = array();
	    $slide = isset($inwave_theme_option[$id]) ? $inwave_theme_option[$id] : '';
		
	    if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		
		//initialize all vars
		$slidevars = array('title','url','link','description');
		
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>'.esc_html__('Slide', 'inmedical').' '.$order.'</strong>';
		}
		
		$slider .= '<input type="hidden" class="slide of-input order" name="'. esc_attr($id) .'['.esc_attr($order).'][order]" id="'. esc_attr($id).'_'.esc_attr($order) .'_slide_order" value="'.esc_attr($order).'" />';
	
		$slider .= '<a class="slide_edit_button" href="#">'.esc_html__('Edit','inmedical').'</a></div>';
		
		$slider .= '<div class="slide_body">';
		
		$slider .= '<label>'.esc_html__('Title','inmedical').'</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. esc_attr($id) .'['.esc_attr($order).'][title]" id="'. esc_attr($id) .'_'.esc_attr($order) .'_slide_title" value="'. esc_attr($val['title']) .'" />';
		
		$slider .= '<label>'.esc_html__('Image URL','inmedical').'</label>';
		$slider .= '<input class="upload slide of-input" name="'. esc_attr($id) .'['.esc_attr($order).'][url]" id="'. esc_attr($id) .'_'.esc_attr($order) .'_slide_url" value="'. esc_attr($val['url']) .'" />';
		
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.esc_attr($id.'_'.$order) .'">'.esc_html__('Upload','inmedical').'</span>';
		
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button remove-image '. esc_attr($hide).'" id="reset_'. esc_attr($id .'_'.$order) .'" title="' . esc_attr($id . '_'.$order) .'">'.esc_html__('Remove','inmedical').'</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			
	    	$slider .= '<a class="of-uploaded-image" href="'. esc_url($val['url']) . '">';
	    	$slider .= '<img class="of-option-image" id="image_'.esc_attr($id.'_'.$order) .'" src="'.esc_url($val['url']).'" alt="" />';
	    	$slider .= '</a>';
			
			}
		$slider .= '</div>';	
		$slider .= '<label>'.esc_html__('Link URL (optional)', 'inmedical'). '</label>';
		$slider .= '<input class="slide of-input" name="'. esc_attr($id) .'['.esc_attr($order).'][link]" id="'. esc_attr($id .'_'.$order) .'_slide_link" value="'. esc_attr($val['link']) .'" />';
		
		$slider .= '<label>'.esc_html__('Description (optional)', 'inmedical').'</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
	
		$slider .= '<a class="slide_delete_button" href="#">'.esc_html__('Delete','inmedical').'</a>';
	    $slider .= '<div class="clear"></div>' . "\n";
	
		$slider .= '</div>';
		$slider .= '</li>';
	
		return $slider;
		
	}

	/**
	 * Drag and drop slides manager
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_addoption_function($id,$std,$label,$oldorder,$order){

	    $data = inwave_of_get_options();
	    $inwave_theme_option = inwave_of_get_options();

		$addoption = '';
		$slide = array();
	    $slide = isset($inwave_theme_option[$id]) ? $inwave_theme_option[$id] : '';

	    if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}

		//initialize all vars
		$options = array('option');

		foreach ($options as $option) {
			if (!isset($val[$option])) {
				$val[$option] = '';
			}
		}

		//begin slider interface
		if (!empty($val['option'])) {
			$addoption .= '<li><div class="slide_header"><strong>'.stripslashes($val['option']).'</strong>';
		} else {
			$addoption .= '<li><div class="slide_header"><strong>'.$label.' '.$order.'</strong>';
		}

		$addoption .= '<input type="hidden" class="slide of-input order" name="'. esc_attr($id) .'['.esc_attr($order).'][order]" id="'. esc_attr($id.'_'.$order) .'_slide_order" value="'.$order.'" />';

		$addoption .= '<a class="slide_edit_button" href="#">'.esc_html__('Edit','inmedical').'</a></div>';

		$addoption .= '<div class="slide_body">';
		$addoption .= '<input class="slide of-input of-slider-title" name="'. esc_attr($id) .'['.esc_attr($order).'][option]" id="'. esc_attr($id .'_'.$order) .'_slide_title" value="'. stripslashes($val['option']) .'" />';
		$addoption .= '<a class="slide_delete_button" href="#">'.esc_html__('Delete','inmedical').'</a>';
	    $addoption .= '<div class="clear"></div>' . "\n";

		$addoption .= '</div>';
		$addoption .= '</li>';

		return $addoption;

	}

	/**
	 * Drag and drop slides manager
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_social_link_function($id,$val,$order){

		$slider = '';

		//initialize all vars
		$slidevars = array('title','icon','link');

		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		//begin slider interface
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>Social '.$order.'</strong>';
		}

		$slider .= '<input type="hidden" class="slide of-input order" name="'. esc_attr($id) .'['.esc_attr($order).'][order]" id="'. esc_attr($id.'_'.$order) .'_slide_order" value="'.esc_attr($order).'" />';

		$slider .= '<a class="slide_edit_button" href="#">'.esc_html__('Edit','inmedical').'</a></div>';

		$slider .= '<div class="slide_body">';

		$slider .= '<label>Title</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. esc_attr($id) .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';

		$slider .= '<label>Icon</label>';
		$slider .= '<input class="slide of-input" name="'. esc_attr($id) .'['.$order.'][icon]" id="'. esc_attr($id .'_'.$order) .'_slide_link" value="'. esc_attr($val['icon']) .'" />';

		$slider .= '<label>Link</label>';
		$slider .= '<input class="slide of-input" name="'. esc_attr($id) .'['.$order.'][link]" id="'. esc_attr($id .'_'.$order) .'_slide_link" value="'. esc_attr($val['link']).'" />';

		$slider .= '<a class="slide_delete_button" href="#">'.esc_html__('Delete','inmedical').'</a>';
		$slider .= '<div class="clear"></div>' . "\n";

		$slider .= '</div>';
		$slider .= '</li>';

		return $slider;

	}
	
	static function getSystemRequireStatus() {
        ob_start();
        $php_version = phpversion();
        $max_execution_time = ini_get('max_execution_time');
        $memory_limit = ini_get('memory_limit');
        $uploads = wp_upload_dir();
        $upload_path = $uploads['basedir'];
		$file_get_content = wp_remote_get('http://inwavethemes.com/wordpress/index.htm',array('decompress'  => false));
		?>
        <ul class="check-system-status">
            <li>
                <?php
                echo wp_kses(__('<span class="label1">PHP Version: </span>', 'inmedical'), inwave_allow_tags('span'));
                if (version_compare($php_version,'5.3.0','<')) {
                    echo '<span class="invalid"><i class="fa fa-times"></i></span>' . sprintf(wp_kses(__('<span class="label2">Currently: %s</span>', 'inmedical'), inwave_allow_tags('span')), $php_version) . '(' . esc_html__('Min: 5.3', 'inmedical') . ')';
                } else {
                    echo '<span class="valid"><i class="fa fa-check"></i></span>' . sprintf(wp_kses(__('<span class="label2">Currently: %s</span>', 'inmedical'), inwave_allow_tags('span')), $php_version);
                }
                ?>
            </li>
            <li>
                <?php
                echo wp_kses(__('<span class="label1">Maximum execution time: </span>', 'inmedical'), inwave_allow_tags('span'));
                if ($max_execution_time < '90') {
                    echo '<span class="invalid"><i class="fa fa-times"></i></span>' . sprintf(wp_kses(__('<span class="label2">Currently: %s</span>', 'inmedical'), inwave_allow_tags('span')), $max_execution_time) . '(' . esc_html__('Min: 90', 'inmedical') . ')';
                } else {
                    echo '<span class="valid"><i class="fa fa-check"></i></span>' . sprintf(wp_kses(__('<span class="label2">Currently: %s</span>', 'inmedical'), inwave_allow_tags('span')), $max_execution_time);
                }
                ?>
            </li>
            <li>
                <?php
                echo wp_kses(__('<span class="label1">Memory Limit: </span>', 'inmedical'), inwave_allow_tags('span'));
                if (intval($memory_limit) < '128') {
                    echo '<span class="invalid"><i class="fa fa-times"></i></span>' . sprintf(wp_kses(__('<span class="label2">Currently: %s</span>', 'inmedical'), inwave_allow_tags('span')), $memory_limit) . '(' . esc_html__('Min: 128M', 'inmedical') . ')';
                } else {
                    echo '<span class="valid"><i class="fa fa-check"></i></span>' . sprintf(wp_kses(__('<span class="label2">Currently: %s</span>', 'inmedical'), inwave_allow_tags('span')), $memory_limit);
                }
                ?>
            </li>
            <li>
                <?php
                echo wp_kses(__('<span class="label1">Uploads folder writable: </span>', 'inmedical'), inwave_allow_tags('span'));
                if (!is_writable($upload_path)) {
                    echo '<span class="invalid"><i class="fa fa-times"></i></span>';
                } else {
                    echo '<span class="valid"><i class="fa fa-check"></i></span>';
                }
                ?>
            </li>
			<li>
				<?php
				echo wp_kses(__('<span class="label1">Connect InwaveThemes Server: </span>', 'inmedical'), inwave_allow_tags('span'));
				if (!$file_get_content) {
					echo '<span class="invalid"><i class="fa fa-times"></i></span>';
				} else {
					echo '<span class="valid"><i class="fa fa-check"></i></span>';
				}
				?>
			</li>
            <li>
                <?php
                echo wp_kses(__('<span class="label1">Start Session: </span>', 'inmedical'), inwave_allow_tags('span'));

                if (!session_id()) {
                    session_start();
                }
                if(!session_id()){
                    echo '<span class="invalid"><i class="fa fa-times"></i></span>' . wp_kses(__('<span class="label2">Failed to start session. Please check session save_path in your php setting</span>', 'inmedical'), inwave_allow_tags('span'));
                }else {
                    echo '<span class="valid"><i class="fa fa-check"></i></span>';
                }
                ?>
            </li>

        </ul>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}//end Options Machine class

?>
