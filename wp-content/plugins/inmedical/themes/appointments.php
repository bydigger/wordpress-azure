<?php
wp_enqueue_script('spin');
wp_enqueue_script('custombox');
wp_enqueue_style('custombox');
wp_enqueue_script('jquery-validate');

if(!isset($month)){ $month = date("n", current_time('timestamp')); }
if(!isset($year)){ $year = date("Y", current_time('timestamp')); }
$monthTimeStamp = strtotime("$year-$month-01");
$monthName = date("F", $monthTimeStamp);

?>
<div class="imd-appoinment-calendar imd-appoinment-form-parent" data-current-date="<?php echo current_time('timestamp'); ?>" data-original-date="<?php echo current_time('timestamp'); ?>">
	<table>
		<thead>
			<tr class="months">
				<td colspan="7">
                    <span class="booked-calendar-prev-month hide"><i class="ion-arrow-left-c"></i></span>
                    <h3 class="table-title"><?php echo $monthName." ".$year ?></h3>
                    <span class="booked-calendar-next-month"><i class="ion-arrow-right-c"></i></span>
                </td>
			</tr>
			<tr class="days">
				<?php
				$days = IMD_Appointment::get_day_array();
				foreach ($days as $day=>$title){
					echo '<td>'.$day.'</td>';
				}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			if (inMedicalGetTemplatePath('inmedical/appointments_body')) {
				include inMedicalGetTemplatePath('inmedical/appointments_body');
			} else {
				$imd_theme = INMEDICAL_THEME_PATH . 'appointments_body.php';
				if (file_exists($imd_theme)) {
					include $imd_theme;
				} else {
					echo esc_html__('No theme was found', 'inwavethemes');
				}
			}
			?>
		</tbody>
	</table>
	<div class="hide">
		<form class="book-appointment-form" id="appointment-form-<?php echo rand(10, 100); ?>"></form>
	</div>
</div>