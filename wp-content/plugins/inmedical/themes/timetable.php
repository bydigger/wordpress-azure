<?php
global $imd_settings;
$timetable = new inMedicalWorkingTable();
$doctor = new inMediacalDoctor();
$department = new inMediacalDepartment();
$departments = $department->getDepartments();
$c_dep = isset($_GET['department']) ? sanitize_text_field($_GET['department']) : '';
?>
<div class="iw-schedule-table">
    <div class="filter-department">
        <form method="GET" action="<?php echo get_permalink(); ?>">
            <select class="js-selected medical-select2" onchange="this.form.submit();" name="department">
                <option value=""><?php _e('Select department', 'inwavethemes'); ?></option>
                <?php
                foreach ($departments as $dep) {
                    echo '<option ' . ($c_dep == $dep->slug ? 'selected' : '') . ' value="' . $dep->slug . '">' . $dep->title . '</option>';
                }
                ?>
            </select>
        </form>
    </div>
    <div class="cd-schedule loading">
        <div class="timeline">
            <ul>
                <li><span>07:00</span></li>
                <li><span>08:00</span></li>
                <li><span>09:00</span></li>
                <li><span>10:00</span></li>
                <li><span>11:00</span></li>
                <li><span>12:00</span></li>
                <li><span>13:00</span></li>
                <li><span>14:00</span></li>
                <li><span>15:00</span></li>
                <li><span>16:00</span></li>
                <li><span>17:00</span></li>
                <li><span>18:00</span></li>
                <li><span>19:00</span></li>
                <li><span>20:00</span></li>
                <li><span>21:00</span></li>
            </ul>
        </div> <!-- .timeline -->

        <div class="events">
            <ul>
                <?php
                $monday_events = $timetable->getTimeTableEvents(strtotime('monday this week'));
                if ((empty($monday_events) && $imd_settings['general']['show_empty_day']) || !empty($monday_events)):
                    ?>
                    <li class="events-group">
                        <div class="top-info"><span><?php _e('Monday', 'inwavethemes'); ?></span></div>
                        <?php
                        print($timetable->getEventDayHtml($monday_events, $c_dep));
                        ?>
                    </li>
                    <?php
                endif;
                $tues_events = $timetable->getTimeTableEvents(strtotime('tuesday this week'));
                if ((empty($tues_events) && $imd_settings['general']['show_empty_day']) || !empty($tues_eventsv)):
                    ?>
                    <li class="events-group">
                        <div class="top-info"><span><?php _e('Tuesday', 'inwavethemes'); ?></span></div>
                        <?php
                        print($timetable->getEventDayHtml($tues_events, $c_dep));
                        ?>

                    </li>
                    <?php
                endif;
                $wednesday_events = $timetable->getTimeTableEvents(strtotime('wednesday this week'));
                if ((empty($wednesday_events) && $imd_settings['general']['show_empty_day']) || !empty($wednesday_events)):
                    ?>

                    <li class="events-group">
                        <div class="top-info"><span><?php _e('Wednesday', 'inwavethemes'); ?></span></div>
                        <?php
                        print($timetable->getEventDayHtml($wednesday_events, $c_dep));
                        ?>
                    </li>
                    <?php
                endif;
                $thursday_events = $timetable->getTimeTableEvents(strtotime('thursday this week'));
                if ((empty($thursday_events) && $imd_settings['general']['show_empty_day']) || !empty($thursday_events)):
                    ?>
                    <li class="events-group">
                        <div class="top-info"><span><?php _e('Thursday', 'inwavethemes'); ?></span></div>
                        <?php
                        print($timetable->getEventDayHtml($thursday_events, $c_dep));
                        ?>
                    </li>
                    <?php
                endif;
                $friday_events = $timetable->getTimeTableEvents(strtotime('friday this week'));
                if ((empty($friday_events) && $imd_settings['general']['show_empty_day']) || !empty($friday_events)):
                    ?>
                    <li class="events-group">
                        <div class="top-info"><span><?php _e('Friday', 'inwavethemes'); ?></span></div>
                        <?php
                        print($timetable->getEventDayHtml($friday_events, $c_dep));
                        ?>
                    </li>
                    <?php
                endif;
                $saturday_events = $timetable->getTimeTableEvents(strtotime('saturday this week'));
                if ((empty($saturday_events) && $imd_settings['general']['show_empty_day']) || !empty($saturday_events)):
                    ?>
                    <li class="events-group">
                        <div class="top-info"><span><?php _e('Saturday', 'inwavethemes'); ?></span></div>
                        <?php
                        print($timetable->getEventDayHtml($saturday_events, $c_dep));
                        ?>
                    </li>
                    <?php
                endif;
                $sunday_events = $timetable->getTimeTableEvents(strtotime('sunday this week'));
                if ((empty($sunday_events) && $imd_settings['general']['show_empty_day']) || !empty($sunday_events)):
                    ?>
                    <li class="events-group">
                        <div class="top-info"><span><?php _e('Sunday', 'inwavethemes'); ?></span></div>
                                <?php
                                print($timetable->getEventDayHtml($sunday_events, $c_dep));
                                ?>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div> <!-- .cd-schedule -->
</div>