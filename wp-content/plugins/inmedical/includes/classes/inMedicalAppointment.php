<?php
class IMD_Appointment{
    public $appointment;

    function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    static function init($data){
        if($data){
            if(is_numeric($data)){
                global $wpdb;
                $sql = "select * from {$wpdb->prefix}imd_appointments WHERE id = %d";
                $appointment = $wpdb->get_row($wpdb->prepare($sql, $data));
            }
            else{
                $appointment = $data;
            }

            if(is_object($appointment)){
                return new IMD_Appointment($appointment);
            }
        }

        return null;
    }

    static function get_gender_array(){
        return array(
            'male' => __('Male', 'inwavethemes'),
            'female' => __('Female', 'inwavethemes'),
            'child' => __('Child', 'inwavethemes'),
        );
    }

    static function get_gender_title($loop){

        $loops = self::get_gender_array();
        if(isset($loops[$loop])){
            return $loops[$loop];
        }

        return '';
    }

    static function get_day_array(){
        return array(
            'Mon' => __('Monday', 'inwavethemes'),
            'Tue' => __('Tuesday', 'inwavethemes'),
            'Wed' => __('Wednesday', 'inwavethemes'),
            'Thu' => __('Thurday', 'inwavethemes'),
            'Fri' => __('Friday', 'inwavethemes'),
            'Sat' => __('Saturday', 'inwavethemes'),
            'Sun' => __('Sunday', 'inwavethemes'),
        );
    }

    static function get_day_title($day){
        $days = self::get_day_array();
        if(isset($days[$day])){
            return $days[$day];
        }

        return '';
    }

    static function get_hour_array(){
        return array(
            0 => __('0 AM', 'inwavethemes'),
            1 => __('1 AM', 'inwavethemes'),
            2 => __('2 AM', 'inwavethemes'),
            3 => __('3 AM', 'inwavethemes'),
            4 => __('4 AM', 'inwavethemes'),
            5 => __('5 AM', 'inwavethemes'),
            6 => __('6 AM', 'inwavethemes'),
            7 => __('7 AM', 'inwavethemes'),
            8 => __('8 AM', 'inwavethemes'),
            9 => __('9 AM', 'inwavethemes'),
            10 => __('10 AM', 'inwavethemes'),
            11 => __('11 AM', 'inwavethemes'),
            12 => __('12 AM', 'inwavethemes'),
            13 => __('1 PM', 'inwavethemes'),
            14 => __('2 PM', 'inwavethemes'),
            15 => __('3 PM', 'inwavethemes'),
            16 => __('4 PM', 'inwavethemes'),
            17 => __('5 PM', 'inwavethemes'),
            18 => __('6 PM', 'inwavethemes'),
            19 => __('7 PM', 'inwavethemes'),
            20 => __('8 PM', 'inwavethemes'),
            21 => __('9 PM', 'inwavethemes'),
            22 => __('10 PM', 'inwavethemes'),
            23 => __('11 PM', 'inwavethemes'),
            24 => __('12 PM', 'inwavethemes'),
        );
    }

    static function get_hour_title($hour){
        $hours = self::get_hour_array();
        if(isset($hours[$hour])){
            return $hours[$hour];
        }

        return '';
    }

    static function add_appointment($data){
        if(!$data){
            return null ;
        }

        global $wpdb;
        $sql = "SELECT MAX(sort) FROM {$wpdb->prefix}imd_appointments WHERE day = %s";
        $max_sort = $wpdb->get_var($wpdb->prepare($sql, $data['day']));
        $insert_data =  array(
            'title' => $data['title'],
            'doctor' => $data['doctor'],
            'day' => $data['day'],
            'time_start' => $data['time_start'],
            'time_end' => $data['time_end'],
            'sort' => $max_sort + 1,
        );
        $format = array(
            '%s',
            '%d',
            '%s',
            '%d',
            '%d',
            '%d',
        );
        if($data['slot']){
            $insert_data['slot'] = $data['slot'];
            $format[] = '%d';
        }
        if($data['date_start']){
            $insert_data['date_start'] = $data['date_start'];
            $format[] = '%d';
        }
        if($data['date_end']){
            $insert_data['date_end'] = $data['date_end'];
            $format[] = '%d';
        }
        $insert = $wpdb->insert($wpdb->prefix."imd_appointments", $insert_data, $format);

        if($insert){
            return $wpdb->insert_id;
        }

        return null;
    }

    static function update_appointment($data){
        if(!$data){
            return null ;
        }
        global $wpdb;
        $update_data =  array(
            'title' => $data['title'],
            'doctor' => $data['doctor'],
            'time_start' => $data['time_start'],
            'time_end' => $data['time_end'],
            'slot' => $data['slot'],
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],
        );
        $format = array(
            '%s',
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
        );

        $update = $wpdb->update($wpdb->prefix."imd_appointments", $update_data, array( 'id' => (int)$data['id'] ), $format);
        if($update){
            return $data['id'];
        }

        return null;
    }

    static function delete_appointment($id){
        if(!$id){
            return null ;
        }
        global $wpdb;

        //delete booking appointments
        $sql = "SELECT ID FROM {$wpdb->posts} as p JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id WHERE p.post_type = %s AND pm.meta_key = %s AND pm.meta_value = %s";
        $booking_appointments = $wpdb->get_results($wpdb->prepare($sql, 'imd_bapm', 'imd_appointment_id', $id));
        if($booking_appointments){
            foreach ($booking_appointments as $booking_appointment){
                wp_delete_post($booking_appointment->ID);
            }
        }

        return $wpdb->delete("{$wpdb->prefix}imd_appointments", array('id' => $id), array('%d'));
    }

    static function duplicate_appointment($id){
        if(!$id){
            return null ;
        }

        $appointment = IMD_Appointment::init($id);
        $new_data = (array)$appointment->appointment;
        unset($new_data['id']);

        return IMD_Appointment::add_appointment($new_data);
    }

    function get_id(){
        return $this->appointment->id;
    }
    function get_title(){
        return $this->appointment->title;
    }

    function get_time(){
        return $this->appointment->time_start .' - '. $this->appointment->time_end;
    }

    function get_time_range(){
        return self::get_hour_title($this->appointment->time_start) .' - '. self::get_hour_title($this->appointment->time_end);
    }

    function get_date_start(){
	    return $this->appointment->date_start;
    }
    function get_date_end(){
	    return $this->appointment->date_end;
    }

    function get_slot(){
        return $this->appointment->slot;
    }

    function get_slot_available($time_stamp){
        if($this->get_slot()){
            $total = $this->get_total_booked($time_stamp);
            $availble = $this->get_slot() - $total;
            if($availble < 0 ){
                return 0;
            }

            return $availble;
        }

        return true;
    }

    function get_backend_appointment_html(){
        $html = '';
        $html  .= '<div class="appointment-item" data-id="'.$this->appointment->id.'" data-doctor="'.$this->appointment->doctor.'" data-title="'.$this->appointment->title.'" 
                    data-slot="'.$this->appointment->slot.'" data-time-start="'.$this->appointment->time_start.'" data-time-end="'.$this->appointment->time_end.'"
                    data-date-start="'.($this->appointment->date_start ? date('Y/m/d', $this->appointment->date_start) : '').'" 
                    data-date-end="'.($this->appointment->date_end ? date('Y/m/d', $this->appointment->date_end) : '').'">';
        $html .= '<span class="handle" title="'.__('Drag & Drop Appointment', 'inwavethemes').'"><i class="fa fa-arrows"></i></span>';
        $html  .= '<div class="item-info">';
        $html  .= '<h3 class="appointment-title">'.$this->appointment->title.'</h3>';
        $html  .= '<div class="appointment-time"><strong>'.__('Time: ', 'inwavethemes').'</strong>'. self::get_hour_title($this->appointment->time_start) . '-' . self::get_hour_title($this->appointment->time_end) .'</div>';
        $html  .= '<div class="appointment-date"><strong>'.__('Date: ', 'inwavethemes'). '</strong>' . ($this->appointment->date_start ? date(get_option('date_format'), $this->appointment->date_start) : date(get_option('date_format', current_time('timestamp'))));
        $html  .= ' - ' . ($this->appointment->date_end ? date(get_option('date_format'), $this->appointment->date_end) : __('Unlimited', 'inwavethemes')) .'</div>';
        $html  .= '<div class="appointment-doctor"><strong>'.__('Doctor: ', 'inwavethemes') . '</strong>'. get_the_title($this->appointment->doctor) .'</div>';
        $html  .= '<div class="appointment-slot"><strong>'.__('Slots: ', 'inwavethemes'). '</strong>'.$this->appointment->slot .'</div>';
        $html  .= '</div>';
        $html  .= '<div class="appointment-action">
            <a class="duplicate-appointment" data-process-text="'.__('Duplicating...', 'inwavethemes').'" title="'.__('Duplicate', 'inwavethemes').'"><i class="fa fa-copy"></i></a>
            <a class="appointment-edit" title="'.__('Edit', 'inwavethemes').'"><i class="fa fa-pencil"></i></a>
            <a class="appointment-delete" title="'.__('Delete', 'inwavethemes').'"><i class="fa fa-remove"></i></a>
        </div>';

        $html  .= '</div>';

        return $html;
    }

    function get_doctor(){
        return $this->appointment->doctor;
    }

    function can_book($time_stamp){

        if(!$this->appointment){
            return false;
        }

        if($this->appointment->date_start && $this->appointment->date_start > $time_stamp){
            return false;
        }

        if($this->appointment->date_end && $this->appointment->date_end <= $time_stamp){
            return false;
        }

        global $imd_settings;
        $book_before_hours = isset($imd_settings['general']['book_before_hours']) ? $imd_settings['general']['book_before_hours'] : 0;
        //check day
        $time_stamp_today = strtotime(date('Y/m/d', current_time('timestamp')));
        /*if($time_stamp < $time_stamp_today){
            return false;
        }*/

        //cehck hours
        if(current_time('timestamp') + ((int)$book_before_hours * 3600) >= $time_stamp + ($this->appointment->time_start * 3600)){
            return false;
        }

        if($this->appointment->slot){
            if($this->appointment->slot <= $this->get_total_booked($time_stamp)){
                return false;
            }
        }

        return true;
    }

    function get_total_booked($time_stamp){
        static $cache = array();
        $key = md5($this->appointment->id.'-'.$time_stamp);
        if(!isset($cache[$key])){
            global  $wpdb;
            $sql = "SELECT COUNT(DISTINCT  p.ID) FROM {$wpdb->posts} AS p 
                JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id 
                JOIN {$wpdb->postmeta} AS pm1 ON p.ID = pm1.post_id 
                JOIN {$wpdb->postmeta} AS pm2 ON p.ID = pm2.post_id 
                WHERE pm.meta_key = 'imd_status' AND (pm.meta_value = '0' || pm.meta_value = '1')
                      AND pm1.meta_key = 'imd_appointment_id' AND pm1.meta_value = %s
                      AND pm2.meta_key = 'imd_date' AND pm2.meta_value = %s
                  AND p.post_status = 'publish'";
            $cache[$key] = $wpdb->get_var($wpdb->prepare($sql, $this->appointment->id, $time_stamp));
        }

        return $cache[$key];

    }

    static function get_backend_appointments_by_day($day){
        global  $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}imd_appointments WHERE day = %s ORDER BY sort";
        return $wpdb->get_results($wpdb->prepare($sql, $day));
    }


    static function get_appointments($day = '', $date_start = '', $date_end = '', $doctor_ids = array(), $deparment_ids = array(), $item_per_page = '', $offset = '', $order = '', $direction = ''){
        global  $wpdb;
        $where = array();
        if($day){
            $where[] = $wpdb->prepare('day = %s', $day);
        }
        if($date_start){
            $where[] = $wpdb->prepare('(date_start <= %d  OR date_start = 0 OR date_start IS NULL)', $date_start);
        }
        if($date_end){
            $where[] = $wpdb->prepare('(date_end >= %d OR date_end = 0 OR date_end IS NULL)', $date_end);
        }

        if($deparment_ids){
            $sql = "SELECT p.ID FROM {$wpdb->posts} AS p JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
                    WHERE p.post_type = 'indoctor' AND p.post_status = 'publish' AND pm.meta_key = 'imd_doctor_info_department' AND pm.meta_value IN (".implode(",", $deparment_ids).")";
            $doctors = $wpdb->get_results($sql);
            if($doctors){
                foreach ($doctors as $doctor){
                    $doctor_ids[] = $doctor->ID;
                }
            }
        }
        $doctor_ids = array_unique($doctor_ids);
        if($doctor_ids){
            $where[] = 'doctor IN ('.implode(",", $doctor_ids).')';
        }
        $where = $where ? " WHERE ".implode(" AND ", $where) : '';
        $limit = '';
        if($item_per_page){
            if($offset){
                $limit = "LIMIT {$offset},{$item_per_page}";
            }
            else{
                $limit = "LIMIT {$item_per_page}";
            }
        }

        if(!$order){
            $order = 'time_start ASC, time_end ASC, sort ';
            $direction = 'ASC';
        }
        $sql = "SELECT * FROM {$wpdb->prefix}imd_appointments {$where} ORDER BY {$order} {$direction} {$limit}";

        return $wpdb->get_results($sql);
    }

	static function get_all_appointments() {
		global $wpdb;
		$sql          = "SELECT * FROM {$wpdb->prefix}imd_appointments";
		$appointments = $wpdb->get_results( $sql );
		if ( $appointments ) {
			return $appointments;
		} else {
			return null;
		}
	}
}