<?php

/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created May 26, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of Inwave_Session
 *
 * @author duongca
 */
class inMedicalBookingEvent {

    private $id;
    private $event_post;
    private $department_post;
    private $doctor_post;
    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $date_of_birth;
    private $gender;
    private $address;
    private $appointment_date;
    private $appointment_reason;
    private $status;

    function getId() {
        return $this->id;
    }

    function getEvent_post() {
        return $this->event_post;
    }

    function getDepartment_post() {
        return $this->department_post;
    }

    function getDoctor_post() {
        return $this->doctor_post;
    }

    function getFirst_name() {
        return $this->first_name;
    }

    function getLast_name() {
        return $this->last_name;
    }

    function getEmail() {
        return $this->email;
    }

    function getPhone() {
        return $this->phone;
    }

    function getDate_of_birth() {
        return $this->date_of_birth;
    }

    function getGender() {
        return $this->gender;
    }

    function getAddress() {
        return $this->address;
    }

    function getAge() {
        $birthaday = $this->getDate_of_birth();
        if($birthaday){
            return (int)date('Y', $birthaday) - (int)date('Y');
        }
        return '';
    }

    function getAppointment_date() {
        return $this->appointment_date;
    }

    function getAppointment_reason() {
        return $this->appointment_reason;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEvent_post($event_post) {
        $this->event_post = $event_post;
    }

    function setDepartment_post($department_post) {
        $this->department_post = $department_post;
    }

    function setDoctor_post($doctor_post) {
        $this->doctor_post = $doctor_post;
    }

    function setFirst_name($first_name) {
        $this->first_name = $first_name;
    }

    function setLast_name($last_name) {
        $this->last_name = $last_name;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }

    function setDate_of_birth($date_of_birth) {
        $this->date_of_birth = $date_of_birth;
    }

    function setGender($gender) {
        $this->gender = $gender;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setAppointment_date($appointment_date) {
        $this->appointment_date = $appointment_date;
    }

    function setAppointment_reason($appointment_reason) {
        $this->appointment_reason = $appointment_reason;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function getAppointment($id) {
        global $wpdb;
        $app = new inMedicalBookingEvent();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'imd_booking_ticket  WHERE id=%d', $id));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $app->setId($value->id);
                $app->setEvent_post($value->event_post);
                $app->setDepartment_post($value->department_post);
                $app->setDoctor_post($value->doctor_post);
                $app->setFirst_name($value->first_name);
                $app->setLast_name($value->last_name);
                $app->setEmail($value->email);
                $app->setPhone($value->phone);
                $app->setDate_of_birth($value->date_of_birth);
                $app->setGender($value->gender);
                $app->setAddress($value->address);
                $app->setAppointment_date($value->appointment_date);
                $app->setAppointment_reason($value->appointment_reason);
                $app->setStatus($value->status);
            }
        }
        return $app;
    }
    
    function getBookings($start, $limit = 20, $filter = '', $orderby = '') {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results('SELECT o.* FROM ' . $wpdb->prefix . 'imd_booking_ticket as o INNER JOIN ' . $wpdb->prefix . 'posts AS p ON o.event_post = p.ID' . ($filter ? ' WHERE ' . $filter : '') . ($orderby ? ' ' . $orderby : '') . ' LIMIT ' . $start . ',' . $limit);
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $app = new inMedicalBookingEvent();
                $app->setId($value->id);
                $app->setEvent_post($value->event_post);
                $app->setDepartment_post($value->department_post);
                $app->setDoctor_post($value->doctor_post);
                $app->setFirst_name($value->first_name);
                $app->setLast_name($value->last_name);
                $app->setEmail($value->email);
                $app->setPhone($value->phone);
                $app->setDate_of_birth($value->date_of_birth);
                $app->setGender($value->gender);
                $app->setAddress($value->address);
                $app->setAppointment_date($value->appointment_date);
                $app->setAppointment_reason($value->appointment_reason);
                $app->setStatus($value->status);
                $rs[] = $app;
            }
        }
        return $rs;
    }

    public function addBooking($booking) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($booking);
        $ins = $wpdb->insert($wpdb->prefix . "imd_booking_ticket", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = __('Insert success', 'inwavethemes');
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function editBooking($appointment) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($appointment);
        unset($data['id']);
        foreach ($data as $k => $v) {
            if ($v === NULL) {
                unset($data[$k]);
            }
        }
        $update = $wpdb->update($wpdb->prefix . "imd_booking_ticket", $data, array('id' => $appointment->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['msg'] = __('Update success', 'inwavethemes');
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    /**
     * Function to delete single sponsor
     * @global type $wpdb
     * @param type $id sponsor id
     * @return string serialize data of result
     */
    public function deleteAppointment($id) {
        global $wpdb;
        $app = $this->getAppointment($id);
        if ($app->getStatus() == '1') {
            $settings = unserialize(get_post_meta($app->getEvent_post(), 'imd_event_settings', true));
            $settings['available_tickets'] = $settings['available_tickets'] + 1;
            update_post_meta($app->getEvent_post(), 'imd_event_settings', serialize($settings));
        }
        $wpdb->delete($wpdb->prefix . 'imd_booking_ticket', array('id' => $id));
    }

    /**
     * Function to delete multiple sponsor
     * @global type $wpdb
     * @param type $ids list ids to delete
     * @return string delete message result
     */
    public function deleteOrders($ids) {
        global $wpdb;
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $app = $this->getAppointment($id);
                if ($app->getStatus() == '1') {
                    $settings = unserialize(get_post_meta($app->getEvent_post(), 'imd_event_settings', true));
                    $settings['available_tickets'] = $settings['available_tickets'] + 1;
                    update_post_meta($app->getEvent_post(), 'imd_event_settings', serialize($settings));
                }
            }
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'imd_booking_ticket WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
        }
    }

    public function getCountOrder($filter = '') {
        global $wpdb;
        $count = $wpdb->get_var('SELECT count(id) FROM ' . $wpdb->prefix . 'imd_booking_ticket' . ($filter ? ' WHERE ' . $filter : ''));
        return $count;
    }

    public function changeBookingStatus($id, $status=1){
        global $wpdb;
        return $wpdb->update($wpdb->prefix . "imd_booking_ticket", array('status'=>$status), array('id' => $id));
    }

    public function getCountBookings($keywork='') {
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'imd_booking_ticket WHERE appointment_reason LIKE \'%' . $keywork . '%\'');
    }

}
