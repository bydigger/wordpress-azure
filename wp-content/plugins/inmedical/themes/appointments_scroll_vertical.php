<?php
wp_enqueue_script('spin');
wp_enqueue_script('custombox');
wp_enqueue_style('custombox');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('jcarousellite');

$item_per_page = isset($item_per_page) ? $item_per_page : 3;
$offset = isset($offset) ? $offset : 0;
$currentDayTimeStamp = strtotime(date('Y/m/d', current_time('timestamp')));
$start_date = isset($start_date) ? $start_date : $currentDayTimeStamp;
$end_date = isset($end_date) ? $end_date : '';
$department_ids = isset($department_ids) && $department_ids ? explode(",", $department_ids) : array();
$doctor_ids = isset($doctor_ids) && $doctor_ids ? explode(",", $doctor_ids) : array();
$number_days_show = isset($number_days_show) ? $number_days_show : 7;
$number_items_show = isset($number_items_show) ? $number_items_show : 3;

global $inwave_theme_option;
$all_appointments_page_link = isset($inwave_theme_option['make_appointment_link']) ? $inwave_theme_option['make_appointment_link'] : '';
$day = date('D', $start_date);
$total = 0;
$appointments = array();
for($i = 1 ; $i <= $number_days_show ; $i++){
	$start_date = $currentDayTimeStamp + (($i - 1) * 86400);
	$day = date('D', $start_date);
	if(!in_array($day, imd_get_day_off_work())){
		$_appointments = IMD_Appointment::get_appointments($day, $start_date, $end_date, $doctor_ids, $department_ids, $item_per_page, $offset);
		if($_appointments){
			foreach ($_appointments as $appointment){
				$count = 0;
				$canbook = false;
				$appointment = IMD_Appointment::init($appointment);
				$canbook = $appointment->can_book($start_date);
				if($canbook){
					$count += $appointment->get_slot_available($start_date);
				}
				if($count){
					$appointment->_date = $start_date;
					$appointments[] = $appointment;
				}
			}
		}
	}
}
?>
<div class="imd-appointment-scroll-vertical imd-appoinment-form-parent"	data-items-show="<?php echo $number_items_show; ?>">
	<div class="appointment-available-list">
		<ul>
		<?php
		if($appointments){
			foreach ($appointments as $appointment){
				$doctor_id = $appointment->get_doctor();
				$thumb_id = get_post_thumbnail_id($doctor_id);
				$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail', true);
				$doctor_image = $thumb_url[0];
				$slot_available = $appointment->get_slot_available($appointment->_date);
				?>
				<li class="timeslot clearfix" >
					<span class="doctor-image" ><img src="<?php echo $doctor_image; ?>" alt=""/></span>
					<div class="timeslot-col doctor-date-info clearfix">
						<div class="doctor-name"><a href="<?php echo get_permalink($doctor_id); ?>" target="_blank"><?php echo get_the_title($doctor_id); ?></a></div>
						<div class="department-name"><?php echo get_the_title(get_post_meta($doctor_id, 'imd_doctor_info_department', true)); ?></div>
						<div class="date-time-range">
                                                    <span class="date"><i class="ion-ios-calendar-outline"></i><?php echo date_i18n(_x('j F', 'Date format', 'inwavethemes'), $appointment->_date); ?></span>
							<span class="time"><i class="ion-ios-clock-outline"></i><?php echo $appointment->get_time_range(); ?></span>
						</div>
					</div>
					<div class="timeslot-col slot-info clearfix">
						<div class="timeslot-slot">
							<?php if($slot_available){
								echo sprintf(_n('%d Slot Available', '%d Slots Available', $slot_available, 'inwavethemes'), $slot_available);
							} ?>
						</div>
						<span class="book-appointment" data-id="<?php echo $appointment->get_id(); ?>" data-date="<?php echo $appointment->_date; ?>"><?php echo __('Book Appointment', 'inwavethemes'); ?></span>
					</div>
				</li>
				<?php
			}
		}
		?>
		</ul>
	</div>
	<div class="appointment-list-bar clearfix">
            <div class="date-title"><?php echo sprintf(__('Available Appointments from <strong class="theme-color">%s</strong> to <strong class="theme-color">%s</strong>', 'inwavethemes'), date_i18n(get_option('date_format'), $currentDayTimeStamp), date(get_option('date_format'), $start_date)); ?></div>
		<div class="button-action">
			<?php $id_r = rand(10, 1000); ?>
			<span class="appointment-list-prev" id="appointment-list-prev-<?php echo $id_r;?>"><i class="ion-arrow-left-c"></i></span>
			<span class="appointment-list-next" id="appointment-list-next-<?php echo $id_r;?>"><i class="ion-arrow-right-c"></i></span>
			<?php if($all_appointments_page_link){ ?>
			<a href="<?php echo $all_appointments_page_link; ?>" class="appointments-all"><i class="ion-ios-calendar-outline"></i></a>
			<?php } ?>
		</div>
	</div>
	<div class="hide">
		<form class="book-appointment-form" id="appointment-form-<?php echo rand(10, 100); ?>"></form>
	</div>
</div>