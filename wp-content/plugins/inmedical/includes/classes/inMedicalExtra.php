<?php

/*
 * @package Inwave Medical
 * @version 1.0.0
 * @created Jul 30, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of imdookingExtra
 *
 * @developer duongca
 */
class inMedicalExtra {

    private $id;
    private $name;
    private $icon;
    private $type;
    private $default_value;
    private $description;
    private $ordering;
    private $published;

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getDefault_value() {
        return $this->default_value;
    }

    function getDescription() {
        return $this->description;
    }

    function getOrdering() {
        return $this->ordering;
    }

    function getPublished() {
        return $this->published;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setDefault_value($default_value) {
        $this->default_value = $default_value;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setOrdering($ordering) {
        $this->ordering = $ordering;
    }

    function setPublished($published) {
        $this->published = $published;
    }

    function getIcon() {
        return $this->icon;
    }

    function setIcon($icon) {
        $this->icon = $icon;
    }

    public function __construct() {
        
    }

    function addMedicalExtra($medicalExtra) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($medicalExtra);
        $ins = $wpdb->insert($wpdb->prefix . "imd_extrafield", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    function editMedicalExtra($medicalExtra) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($medicalExtra);
        unset($data['id']);
        $update = $wpdb->update($wpdb->prefix . "imd_extrafield", $data, array('id' => $medicalExtra->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['msg'] = 'Update success';
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    function deleteMedicalExtra($medical_id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $check = $wpdb->get_results(
                $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'imd_extrafield_value WHERE extrafield_id = %d', $medical_id)
        );
            $del = $wpdb->delete($wpdb->prefix . 'imd_extrafield', array('id' => $medical_id));
            if ($del) {
                $return['success'] = true;
                $return['msg'] = __('Extrafield has been deleted', 'inwavethemes');
            } else {
                if ($wpdb->last_error) {
                    $return['msg'] = $wpdb->last_error;
                } else {
                    $return['msg'] = sprintf(__('No extrafield with id: %d', 'inwavethemes'), $medical_id);
                }
            }
        return serialize($return);
    }

    public function deleteMedicalExtras($ids) {
        global $wpdb;
        if (!empty($ids)) {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'imd_extrafield WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $msg['success'] = sprintf(__('Success delete Room extrafield with id: <strong>%s</strong>', 'inwavethemes'), implode(', ', $ids));
        }
        return $msg;
    }

    public function getMedicalExtraFields($start, $limit = 20, $keyword = '') {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'imd_extrafield WHERE name LIKE %s LIMIT %d,%d', '%' . $keyword . '%', $start, $limit));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $extra = new inMedicalExtra();
                $extra->setId($value->id);
                $extra->setName($value->name);
                $extra->setIcon($value->icon);
                $extra->setType($value->type);
                $extra->setDefault_value($value->default_value);
                $extra->setDescription($value->description);
                $extra->setOrdering($value->ordering);
                $extra->setPublished($value->published);
                $rs[] = $extra;
            }
        }
        return $rs;
    }

    /**
     * @param $id Extrafield id
     * @return imdookingExtra Extrafield Object
     */
    public function getMedicalExtra($id) {
        global $wpdb;
        $extra = new inMedicalExtra();
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'imd_extrafield WHERE id=%d', $id));
        if ($row) {
            $extra->setId($row->id);
            $extra->setName($row->name);
            $extra->setType($row->type);
            $extra->setIcon($row->icon);
            $extra->setDefault_value($row->default_value);
            $extra->setDescription($row->description);
            $extra->setOrdering($row->ordering);
            $extra->setPublished($row->published);
        }
        return $extra;
    }

    public function getCountExtrafield($keywork = '') {
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'imd_extrafield WHERE name LIKE \'%' . $keywork . '%\'');
    }

    public function getDoctorExtrafielđetail($doctor) {
        global $wpdb;
        $fields = array();
        $extfields = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'imd_extrafield');
        if (!empty($extfields)) {
            foreach ($extfields as $extrafield) {
                $value = $wpdb->get_var($wpdb->prepare('SELECT value FROM ' . $wpdb->prefix . 'imd_extrafield_value WHERE extrafield_id=%d AND doctor_id=%d', $extrafield->id, $doctor));
                $data = array(
                    'name' => __(stripslashes($extrafield->name), 'inwavethemes'),
                    'desc' => __(stripslashes($extrafield->description), 'inwavethemes'),
                    'id' => '_imd_' . $extrafield->id,
                    'type' => $extrafield->type,
                    'title' => __(htmlentities(stripslashes($extrafield->name)), 'inwavethemes'),
                    'std' => $extrafield->default_value,
                    'value' => $value
                );
                array_push($fields, $data);
            }
        }
        return $fields;
    }

}
