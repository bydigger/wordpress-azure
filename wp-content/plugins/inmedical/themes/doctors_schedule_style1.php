<?php
	$navigation = $show_navigation ? 'true' : 'false';
	$sliderConfig = '{';
	$sliderConfig .= '"navigation":false';
	$sliderConfig .= ',"autoPlay":true';
	$sliderConfig .= ',"pagination":'.$navigation;
	$sliderConfig .= ',"singleItem":true';
//	$sliderConfig .= ',"navigationText": ["<i class=\"fa fa-angle-left\"></i>", "<i class=\"fa fa-angle-right\"></i>"]';
	$sliderConfig .= '}';
	
	wp_enqueue_style('owl-carousel');
	wp_enqueue_style('owl-theme');
	wp_enqueue_style('owl-transitions');
	wp_enqueue_script('owl-carousel');
	
	$working_table 	= new inMedicalWorkingTable();
	$doctor 		= new inMediacalDoctor();
	$timetables = $working_table->getTimeTableEvents();	
	?>
	
	<div class="iw-doctors-schedule <?php echo $class ?>">
		<?php if ($title_block){ ?>
			<div class="title-wrap">
				<h3 class="title-block"><?php echo $title_block; ?></h3>
			</div>
		<?php } ?>
		<div class="iw-schedule-list">
			<?php if (!empty($timetables)){ ?>
				<div class="owl-carousel" data-plugin-options='<?php echo $sliderConfig; ?>'>
				<?php 
					$total_item = count($timetables);
					if ($max_item_show){
						$total_item = $max_item_show;
					}
					$i = 0;
					foreach($timetables as $item){
					$e_settings = unserialize(get_post_meta($item->ID, 'imd_event_settings', true));
					$doctor_info = $doctor->getDoctorInformation($e_settings['doctor']);
				?>	
					<?php 
						if ($i % $number_of_row == 0){
							echo '<div class="row-item">';
						}
					?>
						<div class="schedule-item">
							<div class="schedule-item-inner">
								<div class="schedule-date">
									<span class="date-day"><?php echo date('d', $item->event_date); ?></span>
									<span class="date-month"><?php echo date('M', $item->event_date); ?></span>
								</div>
								<div class="schedule-detail">
									<h3 class="title">
										<a class="theme-color-hover" href="<?php echo get_permalink($item->event_post); ?>"><?php echo $item->post_title; ?></a>
									</h3>
									<div class="schedule-meta">
										<div class="schedule-time">
											<i class="fa fa-clock-o"></i><?php echo $item->time_start; ?> - <?php echo $item->time_end; ?>
										</div>
										<div class="doctor-name">
											<i class="icon-font ion-ios-person"></i><a href="<?php echo get_permalink($doctor_info->id); ?>"><?php echo $doctor_info->title; ?></a>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					<?php 
						if (($i + 1) % $number_of_row == 0 || ($i + 1) == $total_item) {
						 echo '</div>';
						}
						if ($max_item_show && (($i+1) == $max_item_show)){
							break;
						}
					?>
					
				<?php 
					$i++;
					
					}
				?>
				</div>
				<?php if ($view_all_schedule){ ?>
					<div class="readmore"><a class="theme-color-hover" href="<?php echo esc_url($view_all_schedule); ?>"><?php _e('View full schedules','inwavethemes');?></a></div>
				<?php } ?>
			<?php } else { ?>
				<div class="empty-schedule"><?php _e('There is no schedule today.','inwavethemes');?></div>
			<?php } ?>
		</div>
	
	</div>
