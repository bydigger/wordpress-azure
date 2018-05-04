<?php
if(!isset($month)){ $month = date("n", current_time('timestamp')); }
if(!isset($year)){ $year = date("Y", current_time('timestamp')); }
//calender variable
$monthTimeStamp = strtotime("$year-$month-01");
$monthName = date("F", $monthTimeStamp);
$numDays = date("t" , $monthTimeStamp);
$firstDayTimeStamp = strtotime("{$year}/{$month}/01");
$firstDay = date('w', strtotime("{$year}-{$month}-00"));
$lastDay = date('w', strtotime("{$year}-{$month}-".$numDays));
$total_day = $numDays;
if($firstDay > 0){
	$total_day += $firstDay;
	$firstDayTimeStamp -= ($firstDay * 86400);
}

if($lastDay < 6){
	$total_day += 7 - $lastDay;
}
$appointmentdays = array();
$currentDayTimeStamp = strtotime(date('Y/m/d', current_time('timestamp')));

/*$start_date = isset($start_date) ? $start_date : $currentDayTimeStamp;
$end_date = isset($end_date) ? $end_date : '';*/
$department_ids = isset($department_ids) && $department_ids ? explode(",", $department_ids) : array();
//$doctor_ids = isset($doctor_ids) && $doctor_ids ? explode(",", $doctor_ids) : array();
?>

<tr>
	<?php
	$days = IMD_Appointment::get_day_array();

	foreach ($days as $day=>$title){
		if(!in_array($day, imd_get_day_off_work())){
			$get_all_appointments = IMD_Appointment::get_backend_appointments_by_day($day);
			foreach ( $get_all_appointments as $get_all_appointment ) {
				$start_date = $get_all_appointment->date_start ? $get_all_appointment->date_start : $currentDayTimeStamp;
				$date_end = $get_all_appointment->date_end ? $get_all_appointment->date_end : '';
				$doctor_ids = $get_all_appointment->doctor ? explode(",", $get_all_appointment->doctor) : array();
				$appointmentdays[$day] = IMD_Appointment::get_appointments($day, $start_date, $date_end, $doctor_ids, $department_ids);
			}
		}else{
			$appointmentdays[$day] = array();
		}
	}

	for($i = 1; $i <= $total_day; $i++){
		$timeStamp = $firstDayTimeStamp + (($i-1) * 86400);
		?>
		<td>
			<?php
			$day = date('D', $timeStamp);
			//$appointments = $appointmentdays[$day];
			$is_current_day = ($timeStamp == $currentDayTimeStamp) ? true : false;
			$count = 0;

			$get_all_appointments = IMD_Appointment::get_backend_appointments_by_day($day);
			if($get_all_appointments){
				//check available
				foreach ($get_all_appointments as $appointment){
					$appointment = IMD_Appointment::init($appointment);
					$canbook = $appointment->can_book($timeStamp);
					if($canbook){
						$count += $appointment->get_slot_available($timeStamp);
					}
				}
			}
			if($count){
				echo '<span class="date has-appointment '.($is_current_day ? 'current-day' : '').'" data-date="'.$timeStamp.'"><span class="number has-tooltip" data-title="'.sprintf(__('%d Available', 'inwavethemes'), $count).'">'.date('j', $timeStamp).'</span></span>';
			}
			else{
				echo '<span class="date '.($is_current_day ? 'current-day' : '').'"><span class="number">'.date('j', $timeStamp).'</span></span>';
			}
			?>
		</td>
		<?php
		if($i > 0 && ($i % 7) == 0 && $i<$total_day){
			echo '</tr><tr>';
		}
	}
	?>
</tr>
