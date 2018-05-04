<?php
/*
 * @package Inwave Charity
 * @version 1.0.0
 * @created Mar 2, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of utility
 *
 * @developer duongca
 */
require_once 'classes/Inwave_Session.php';
require_once 'classes/inMedicalWorkingTable.php';
require_once 'classes/inMediacalDepartment.php';
require_once 'classes/inMediacalDoctor.php';
require_once 'classes/inMedicalAppointment.php';
require_once 'classes/inMedicalBookingAppointment.php';
require_once 'classes/inMedicalEmail.php';
require_once 'classes/inMedicalExtra.php';
require_once 'classes/iwPaging.php';
require_once 'classes/inMedicalBookingEvent.php';
if (!class_exists('inMedicalUtility')) {

    class inMedicalUtility {

        function categoryField($name, $value, $multiple = true) {
            $categories = get_terms('iwevent_category', 'hide_empty=0');
            $html = array();
            $multiselect = '';
            if ($multiple) {
                $multiselect = 'multiple="multiple"';
                $html[] = '<select id="category_field" name="' . $name . '[]" ' . $multiselect . '>';
                $html[] = '<option ' . (empty($value) ? 'selected="selected"' : '' ) . ' value="0">' . __('Select all', 'inwavethemes') . '</option>';
            } else {
                $html[] = '<select id="category_field" name="' . $name . '">';
                $html[] = '<option value="0">' . __('Select category', 'inwavethemes') . '</option>';
            }
            foreach ($categories as $category) {
                if (is_array($value)) {
                    if (in_array($category->term_id, $value)) {
                        $html[] = '<option value="' . $category->term_id . '" selected="selected">' . $category->name . '</option>';
                    } else {
                        $html[] = '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                    }
                } else {
                    $html[] = '<option value="' . $category->term_id . '" ' . (($category->term_id == $value) ? 'selected="selected"' : '') . '>' . $category->name . '</option>';
                }
            }
            $html[] = '</select>';
            $html[] = '<script type="text/javascript">';
            $html[] = '(function ($) {';
            $html[] = '$(document).ready(function () {';
            $html[] = '$("#category_field").select2({';
            $html[] = 'placeholder: "' . __('Select category', 'inwavethemes') . '",';
            $html[] = 'allowClear: true';
            $html[] = '});';
            $html[] = '});';
            $html[] = '})(jQuery);';
            $html[] = '</script>';
            return implode($html);
        }

        /**
         * Function create select option field
         * 
         * @param type $id
         * @param String $name Name of field
         * @param String $value The value field
         * @param Array $data list data option of field
         * @param String $text Default value of field
         * @param String $class Class of field
         * @param Bool $multi Field allow multiple select of not
         * @return String Select option field
         * 
         */
        static function selectFieldRender($id, $name, $value, $data, $text = '', $class = '', $multi = true, $extra = '', $html5_data = array()) {
            $html = array();
            $multiselect = '';
//Kiem tra neu bien class ton tai thi them class vao field
            if ($class) {
                $class = 'class="' . $class . '"';
            }

            $html_data = '';
            if (!empty($html5_data)) {
                foreach ($html5_data as $key => $value) {
                    $html_data .= 'data-' . $key . '="' . $value . '" ';
                }
            }

//Kiem tra neu field can tao cho phep multiple
            if ($multi) {
                $multiselect = 'multiple="multiple"';
                $html[] = '<select' . ($id ? ' id="' . $id . '"' : ' ') . ($html_data ? $html_data : '') . ' ' . $class . ' name="' . $name . '[]" ' . $multiselect . ' ' . $extra . '>';
                if ($text) {
                    $html[] = '<option value="">' . __($text) . '</option>';
                }
            } else {
                $html[] = '<select ' . $class . ' name="' . $name . '" ' . ($html_data ? $html_data : '') . ($id ? ' id="' . $id . '"' : ' ') . $extra . '>';
                if ($text) {
                    $html[] = '<option value="">' . __($text) . '</option>';
                }
            }

//Duyet qua tung phan tu cua mang du lieu de tao option tuong ung
            foreach ($data as $option) {
                if (is_array($value)) {
                    if (in_array($option['value'], $value)) {
                        $html[] = '<option value="' . $option['value'] . '" selected="selected">' . $option['text'] . '</option>';
                    } else {
                        $html[] = '<option value="' . $option['value'] . '">' . __($option['text']) . '</option>';
                    }
                } else {
                    $html[] = '<option value="' . $option['value'] . '" ' . (($option['value'] == $value) ? 'selected="selected"' : '') . '>' . __($option['text']) . '</option>';
                }
            }
            $html[] = '</select>';
            if ($id) {
                $html[] = '<script type="text/javascript">';
                $html[] = '(function ($) {';
                $html[] = '$(document).ready(function () {';
                $html[] = '$("#' . $id . '").select2({';
                $html[] = 'placeholder: "' . $text . '",';
                $html[] = 'allowClear: true';
                $html[] = '});';
                $html[] = '});';
                $html[] = '})(jQuery);';
                $html[] = '</script>';
            }
            echo implode($html);
        }

        function getMessage($message, $type = 'success') {
            $html = array();
            $class = 'success';
            if ($type == 'error') {
                $class = 'error';
            }
            if ($type == 'notice') {
                $class = 'notice';
            }
            $html[] = '<div class="in-message ' . $class . '">';
            $html[] = '<div class="message-text">' . $message . '</div>';
            $html[] = '</div>';
            return implode($html);
        }

        /**
         * Function check and create alias
         * @param type $title
         * @param type $isCopy
         * @return type
         */
        public static function createAlias($title, $table, $isCopy = FALSE) {
            require_once 'classes/unicodetoascii.php';
            if (class_exists('unicodetoascii')) {
                $calias = new unicodetoascii();
                $alias = $calias->asciiAliasCreate($title);
            } else {
                $alias = str_replace(' ', '-', strtolower($title));
            }
//xu ly truong hop alias duoc tao ra do copy tu 1 item khac
            if ($isCopy) {
                $newAlias = explode('-', $alias);
                if (count($newAlias) > 1 && is_numeric(end($newAlias))) {
                    unset($newAlias[count($newAlias) - 1]);
                }
                $alias = implode('-', $newAlias);
            }
            $listAlias = self::getAllAlias($alias, $table);
            $alias = self::generateAlias($alias, $listAlias);
            return $alias;
        }

        /**
         * function create alias
         * 
         * @param String $alias
         * @param Array $listAlias
         * @return string
         */
        static function generateAlias($alias, $listAlias) {
            if ($listAlias) {
                $listEndAlias = array();
                foreach ($listAlias as $value) {
                    $parseAlias = explode("-", $value['alias']);
                    if (is_numeric(end($parseAlias))) {
                        $listEndAlias[] = end($parseAlias);
                    }
                }
                if (empty($listEndAlias)) {
                    $alias = $alias . '-2';
                } else {
                    $endmax = max($listEndAlias);
                    $alias = $alias . '-' . ($endmax + 1);
                }
            }
            return $alias;
        }

        /**
         * function takes on all the alias alias similar to the present
         * @global type $wpdb
         * @param String $alias
         * @return Array list alias
         */
        static function getAllAlias($alias, $table) {
            global $wpdb;
            $listAlias = $wpdb->get_results('SELECT id, alias FROM ' . $wpdb->prefix . $table . ' WHERE alias LIKE "' . $alias . '%"');
            foreach ($listAlias as $value) {
                $rs[] = array('id' => $value->id, 'alias' => $value->alias);
            }
            return $rs;
        }

        public function MakeTree($categories, $id = 0) {
            $tree = array();
            $tree = self::TreeTitle($categories, $tree, 0);
            $tree_array = array();
            if ($id > 0) {
                $tree_sub = array();
                $id_sub = '';
                $subcategories = self::SubTree($categories, $tree_sub, 0, $id_sub);
                foreach ($subcategories as $key0 => $value0) {
                    $subcategories_array[$key0] = explode(',', $value0);
                }

                foreach ($tree as $key => $value) {

                    foreach ($categories as $key2 => $value2) {
                        $syntax_check = 1;

                        if ($id == $key) {
                            $syntax_check = 0;
                        }

                        foreach ($subcategories_array as $key3 => $value3) {
                            foreach ($value3 as $key4 => $value4) {
                                if ($value4 == $id && $key == $key3) {
                                    $syntax_check = 0;
                                }
                            }
                        }

                        if ($syntax_check == 1) {
                            if ($key == $value2->value) {
                                $tree_object = new JObject();
                                $tree_object->text = $value;
                                $tree_object->value = $key;
                                $tree_array[] = $tree_object;
                            }
                        }
                    }
                }
            } else {
                foreach ($tree as $key => $value) {
                    foreach ($categories as $key2 => $value2) {
                        if ($key == $value2->value) {
                            $tree_object = new JObject();
                            $tree_object->text = $value;
                            $tree_object->value = $key;
                            $tree_array[] = $tree_object;
                        }
                    }
                }
            }
            return $tree_array;
        }

        static function TreeTitle($data, $tree, $id = 0, $text = '') {

            foreach ($data as $key) {
                $show_text = $text . $key->text;
                if ($key->parent_id == $id) {
                    $tree[$key->value] = $show_text;
                    $tree = self::TreeTitle($data, $tree, $key->value, $text . " -- ");
                }
            }
            return ($tree);
        }

        static function SubTree($data, $tree, $id = 0, $id_sub = '') {
            foreach ($data as $key) {
                $show_id_sub = $id_sub . $key->value;
                if ($key->parent_id == $id) {
                    $tree[$key->value] = $id_sub;
                    $tree = self::SubTree($data, $tree, $key->value, $show_id_sub . ",");
                }
            }
            return ($tree);
        }

        /**
         * 
         * @param type $email
         * @param type $type: order_created, order_change_status, order_info
         * @return type
         */
        function sendEmail($email, $data, $type) {
            global $inf_settings, $inf_order;
            $mail_template = $inf_settings['email_template'];
            $mail_content = '';
            $mail_title = '';
            $result = array();
            $result['success'] = false;
            $admin_email = get_option('admin_email');

            if (isset($data['full_name'])) {
                $inf_order->member = $data['full_name'];
            }
            if (isset($data['reason'])) {
                $inf_order->reason = $data['reason'];
            }

            switch ($type) {
                case 'order_created':
                case 'order_info':
                    $mail_title = strip_tags(apply_filters('the_content', $mail_template['order_info']['title']));
                    $mail_content = apply_filters('the_content', $mail_template['order_info']['content']);
                    break;
                case 'order_change_status':
                    $mail_title = strip_tags(apply_filters('the_content', $mail_template['order_change_state']['title']));
                    $mail_content = apply_filters('the_content', $mail_template['order_change_state']['content']);
                    break;
                case 'user_register':
                    $mail_title = strip_tags(apply_filters('the_content', $mail_template['register_info']['title']));
                    $mail_content = apply_filters('the_content', $mail_template['register_info']['content']);
                    break;
                case 'offline_payment_notice':
                    $mail_title = __('Thanks for donate', 'inwavethemes');

                    $mail_content = apply_filters('the_content', $inf_settings['inf_payment']['custom_payment']['content']);
                    break;

                default:
                    break;
            }

            $html = '
<html>
<head>
  <title>' . $mail_title . '</title>
</head>
<body>' . $mail_content . '</body>
</html>
';

// To send HTML mail, the Content-type header must be set
            $headers = "From: [" . get_option('blogname') . "] <" . $admin_email . "> \r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

            if (wp_mail($email, $mail_title, $html, $headers)) {
                $result['success'] = true;
                $result['message'] = __('The email has been sent', 'inwavethemes');
            } else {
                $infLog = new inMedicalLog();
                $infLog->addLog(new inMedicalLog(null, 'error', time(), __('Can\'t send email to donor when donate, please check settings or code.', 'inwavethemes')));
                $result['message'] = __('Can\'t send email present', 'inwavethemes');
            }
            return serialize($result);
        }

        function prepareMemberFieldValue($value) {
            global $inf_settings;
            $memberinfo = array();
            $memberFields = $inf_settings['register_form_fields'];
            foreach ($value as $k => $v) {
                foreach ($memberFields as $field) {
                    if ($k == $field['name']) {
                        $val = $v;
                        if ($field['type'] == 'select') {
                            foreach ($field['values'] as $f_val) {
                                if ($v == $f_val['value']) {
                                    $val = $f_val;
                                    break;
                                }
                            }
                        }
                        $memberinfo[] = array('name' => $k, 'label' => $field['label'], 'type' => $field['type'], 'value' => $val);
                        break;
                    }
                }
            }
            return $memberinfo;
        }

        function getIWEventcurrencies() {
            return array(
                array('value' => 'AED', 'text' => __('United Arab Emirates dirham', 'inwavethemes')),
                array('value' => 'AFN', 'text' => __('Afghan afghani', 'inwavethemes')),
                array('value' => 'ALL', 'text' => __('Albanian lek', 'inwavethemes')),
                array('value' => 'AMD', 'text' => __('Armenian dram', 'inwavethemes')),
                array('value' => 'ANG', 'text' => __('Netherlands Antillean guilder', 'inwavethemes')),
                array('value' => 'AOA', 'text' => __('Angolan kwanza', 'inwavethemes')),
                array('value' => 'ARS', 'text' => __('Argentine peso', 'inwavethemes')),
                array('value' => 'AUD', 'text' => __('Australian dollar', 'inwavethemes')),
                array('value' => 'AWG', 'text' => __('Aruban florin', 'inwavethemes')),
                array('value' => 'AZN', 'text' => __('Azerbaijani manat', 'inwavethemes')),
                array('value' => 'BAM', 'text' => __('Bosnia and Herzegovina convertible mark', 'inwavethemes')),
                array('value' => 'BBD', 'text' => __('Barbadian dollar', 'inwavethemes')),
                array('value' => 'BDT', 'text' => __('Bangladeshi taka', 'inwavethemes')),
                array('value' => 'BGN', 'text' => __('Bulgarian lev', 'inwavethemes')),
                array('value' => 'BHD', 'text' => __('Bahraini dinar', 'inwavethemes')),
                array('value' => 'BIF', 'text' => __('Burundian franc', 'inwavethemes')),
                array('value' => 'BMD', 'text' => __('Bermudian dollar', 'inwavethemes')),
                array('value' => 'BND', 'text' => __('Brunei dollar', 'inwavethemes')),
                array('value' => 'BOB', 'text' => __('Bolivian boliviano', 'inwavethemes')),
                array('value' => 'BRL', 'text' => __('Brazilian real', 'inwavethemes')),
                array('value' => 'BSD', 'text' => __('Bahamian dollar', 'inwavethemes')),
                array('value' => 'BTC', 'text' => __('Bitcoin', 'inwavethemes')),
                array('value' => 'BTN', 'text' => __('Bhutanese ngultrum', 'inwavethemes')),
                array('value' => 'BWP', 'text' => __('Botswana pula', 'inwavethemes')),
                array('value' => 'BYR', 'text' => __('Belarusian ruble', 'inwavethemes')),
                array('value' => 'BZD', 'text' => __('Belize dollar', 'inwavethemes')),
                array('value' => 'CAD', 'text' => __('Canadian dollar', 'inwavethemes')),
                array('value' => 'CDF', 'text' => __('Congolese franc', 'inwavethemes')),
                array('value' => 'CHF', 'text' => __('Swiss franc', 'inwavethemes')),
                array('value' => 'CLP', 'text' => __('Chilean peso', 'inwavethemes')),
                array('value' => 'CNY', 'text' => __('Chinese yuan', 'inwavethemes')),
                array('value' => 'COP', 'text' => __('Colombian peso', 'inwavethemes')),
                array('value' => 'CRC', 'text' => __('Costa Rican col&oacute;n', 'inwavethemes')),
                array('value' => 'CUC', 'text' => __('Cuban convertible peso', 'inwavethemes')),
                array('value' => 'CUP', 'text' => __('Cuban peso', 'inwavethemes')),
                array('value' => 'CVE', 'text' => __('Cape Verdean escudo', 'inwavethemes')),
                array('value' => 'CZK', 'text' => __('Czech koruna', 'inwavethemes')),
                array('value' => 'DJF', 'text' => __('Djiboutian franc', 'inwavethemes')),
                array('value' => 'DKK', 'text' => __('Danish krone', 'inwavethemes')),
                array('value' => 'DOP', 'text' => __('Dominican peso', 'inwavethemes')),
                array('value' => 'DZD', 'text' => __('Algerian dinar', 'inwavethemes')),
                array('value' => 'EGP', 'text' => __('Egyptian pound', 'inwavethemes')),
                array('value' => 'ERN', 'text' => __('Eritrean nakfa', 'inwavethemes')),
                array('value' => 'ETB', 'text' => __('Ethiopian birr', 'inwavethemes')),
                array('value' => 'EUR', 'text' => __('Euro', 'inwavethemes')),
                array('value' => 'FJD', 'text' => __('Fijian dollar', 'inwavethemes')),
                array('value' => 'FKP', 'text' => __('Falkland Islands pound', 'inwavethemes')),
                array('value' => 'GBP', 'text' => __('Pound sterling', 'inwavethemes')),
                array('value' => 'GEL', 'text' => __('Georgian lari', 'inwavethemes')),
                array('value' => 'GGP', 'text' => __('Guernsey pound', 'inwavethemes')),
                array('value' => 'GHS', 'text' => __('Ghana cedi', 'inwavethemes')),
                array('value' => 'GIP', 'text' => __('Gibraltar pound', 'inwavethemes')),
                array('value' => 'GMD', 'text' => __('Gambian dalasi', 'inwavethemes')),
                array('value' => 'GNF', 'text' => __('Guinean franc', 'inwavethemes')),
                array('value' => 'GTQ', 'text' => __('Guatemalan quetzal', 'inwavethemes')),
                array('value' => 'GYD', 'text' => __('Guyanese dollar', 'inwavethemes')),
                array('value' => 'HKD', 'text' => __('Hong Kong dollar', 'inwavethemes')),
                array('value' => 'HNL', 'text' => __('Honduran lempira', 'inwavethemes')),
                array('value' => 'HRK', 'text' => __('Croatian kuna', 'inwavethemes')),
                array('value' => 'HTG', 'text' => __('Haitian gourde', 'inwavethemes')),
                array('value' => 'HUF', 'text' => __('Hungarian forint', 'inwavethemes')),
                array('value' => 'IDR', 'text' => __('Indonesian rupiah', 'inwavethemes')),
                array('value' => 'ILS', 'text' => __('Israeli new shekel', 'inwavethemes')),
                array('value' => 'IMP', 'text' => __('Manx pound', 'inwavethemes')),
                array('value' => 'INR', 'text' => __('Indian rupee', 'inwavethemes')),
                array('value' => 'IQD', 'text' => __('Iraqi dinar', 'inwavethemes')),
                array('value' => 'IRR', 'text' => __('Iranian rial', 'inwavethemes')),
                array('value' => 'ISK', 'text' => __('Icelandic kr&oacute;na', 'inwavethemes')),
                array('value' => 'JEP', 'text' => __('Jersey pound', 'inwavethemes')),
                array('value' => 'JMD', 'text' => __('Jamaican dollar', 'inwavethemes')),
                array('value' => 'JOD', 'text' => __('Jordanian dinar', 'inwavethemes')),
                array('value' => 'JPY', 'text' => __('Japanese yen', 'inwavethemes')),
                array('value' => 'KES', 'text' => __('Kenyan shilling', 'inwavethemes')),
                array('value' => 'KGS', 'text' => __('Kyrgyzstani som', 'inwavethemes')),
                array('value' => 'KHR', 'text' => __('Cambodian riel', 'inwavethemes')),
                array('value' => 'KMF', 'text' => __('Comorian franc', 'inwavethemes')),
                array('value' => 'KPW', 'text' => __('North Korean won', 'inwavethemes')),
                array('value' => 'KRW', 'text' => __('South Korean won', 'inwavethemes')),
                array('value' => 'KWD', 'text' => __('Kuwaiti dinar', 'inwavethemes')),
                array('value' => 'KYD', 'text' => __('Cayman Islands dollar', 'inwavethemes')),
                array('value' => 'KZT', 'text' => __('Kazakhstani tenge', 'inwavethemes')),
                array('value' => 'LAK', 'text' => __('Lao kip', 'inwavethemes')),
                array('value' => 'LBP', 'text' => __('Lebanese pound', 'inwavethemes')),
                array('value' => 'LKR', 'text' => __('Sri Lankan rupee', 'inwavethemes')),
                array('value' => 'LRD', 'text' => __('Liberian dollar', 'inwavethemes')),
                array('value' => 'LSL', 'text' => __('Lesotho loti', 'inwavethemes')),
                array('value' => 'LYD', 'text' => __('Libyan dinar', 'inwavethemes')),
                array('value' => 'MAD', 'text' => __('Moroccan dirham', 'inwavethemes')),
                array('value' => 'MDL', 'text' => __('Moldovan leu', 'inwavethemes')),
                array('value' => 'MGA', 'text' => __('Malagasy ariary', 'inwavethemes')),
                array('value' => 'MKD', 'text' => __('Macedonian denar', 'inwavethemes')),
                array('value' => 'MMK', 'text' => __('Burmese kyat', 'inwavethemes')),
                array('value' => 'MNT', 'text' => __('Mongolian t&ouml;gr&ouml;g', 'inwavethemes')),
                array('value' => 'MOP', 'text' => __('Macanese pataca', 'inwavethemes')),
                array('value' => 'MRO', 'text' => __('Mauritanian ouguiya', 'inwavethemes')),
                array('value' => 'MUR', 'text' => __('Mauritian rupee', 'inwavethemes')),
                array('value' => 'MVR', 'text' => __('Maldivian rufiyaa', 'inwavethemes')),
                array('value' => 'MWK', 'text' => __('Malawian kwacha', 'inwavethemes')),
                array('value' => 'MXN', 'text' => __('Mexican peso', 'inwavethemes')),
                array('value' => 'MYR', 'text' => __('Malaysian ringgit', 'inwavethemes')),
                array('value' => 'MZN', 'text' => __('Mozambican metical', 'inwavethemes')),
                array('value' => 'NAD', 'text' => __('Namibian dollar', 'inwavethemes')),
                array('value' => 'NGN', 'text' => __('Nigerian naira', 'inwavethemes')),
                array('value' => 'NIO', 'text' => __('Nicaraguan c&oacute;rdoba', 'inwavethemes')),
                array('value' => 'NOK', 'text' => __('Norwegian krone', 'inwavethemes')),
                array('value' => 'NPR', 'text' => __('Nepalese rupee', 'inwavethemes')),
                array('value' => 'NZD', 'text' => __('New Zealand dollar', 'inwavethemes')),
                array('value' => 'OMR', 'text' => __('Omani rial', 'inwavethemes')),
                array('value' => 'PAB', 'text' => __('Panamanian balboa', 'inwavethemes')),
                array('value' => 'PEN', 'text' => __('Peruvian nuevo sol', 'inwavethemes')),
                array('value' => 'PGK', 'text' => __('Papua New Guinean kina', 'inwavethemes')),
                array('value' => 'PHP', 'text' => __('Philippine peso', 'inwavethemes')),
                array('value' => 'PKR', 'text' => __('Pakistani rupee', 'inwavethemes')),
                array('value' => 'PLN', 'text' => __('Polish z&#x142;oty', 'inwavethemes')),
                array('value' => 'PRB', 'text' => __('Transnistrian ruble', 'inwavethemes')),
                array('value' => 'PYG', 'text' => __('Paraguayan guaran&iacute;', 'inwavethemes')),
                array('value' => 'QAR', 'text' => __('Qatari riyal', 'inwavethemes')),
                array('value' => 'RON', 'text' => __('Romanian leu', 'inwavethemes')),
                array('value' => 'RSD', 'text' => __('Serbian dinar', 'inwavethemes')),
                array('value' => 'RUB', 'text' => __('Russian ruble', 'inwavethemes')),
                array('value' => 'RWF', 'text' => __('Rwandan franc', 'inwavethemes')),
                array('value' => 'SAR', 'text' => __('Saudi riyal', 'inwavethemes')),
                array('value' => 'SBD', 'text' => __('Solomon Islands dollar', 'inwavethemes')),
                array('value' => 'SCR', 'text' => __('Seychellois rupee', 'inwavethemes')),
                array('value' => 'SDG', 'text' => __('Sudanese pound', 'inwavethemes')),
                array('value' => 'SEK', 'text' => __('Swedish krona', 'inwavethemes')),
                array('value' => 'SGD', 'text' => __('Singapore dollar', 'inwavethemes')),
                array('value' => 'SHP', 'text' => __('Saint Helena pound', 'inwavethemes')),
                array('value' => 'SLL', 'text' => __('Sierra Leonean leone', 'inwavethemes')),
                array('value' => 'SOS', 'text' => __('Somali shilling', 'inwavethemes')),
                array('value' => 'SRD', 'text' => __('Surinamese dollar', 'inwavethemes')),
                array('value' => 'SSP', 'text' => __('South Sudanese pound', 'inwavethemes')),
                array('value' => 'STD', 'text' => __('S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'inwavethemes')),
                array('value' => 'SYP', 'text' => __('Syrian pound', 'inwavethemes')),
                array('value' => 'SZL', 'text' => __('Swazi lilangeni', 'inwavethemes')),
                array('value' => 'THB', 'text' => __('Thai baht', 'inwavethemes')),
                array('value' => 'TJS', 'text' => __('Tajikistani somoni', 'inwavethemes')),
                array('value' => 'TMT', 'text' => __('Turkmenistan manat', 'inwavethemes')),
                array('value' => 'TND', 'text' => __('Tunisian dinar', 'inwavethemes')),
                array('value' => 'TOP', 'text' => __('Tongan pa&#x2bb;anga', 'inwavethemes')),
                array('value' => 'TRY', 'text' => __('Turkish lira', 'inwavethemes')),
                array('value' => 'TTD', 'text' => __('Trinidad and Tobago dollar', 'inwavethemes')),
                array('value' => 'TWD', 'text' => __('New Taiwan dollar', 'inwavethemes')),
                array('value' => 'TZS', 'text' => __('Tanzanian shilling', 'inwavethemes')),
                array('value' => 'UAH', 'text' => __('Ukrainian hryvnia', 'inwavethemes')),
                array('value' => 'UGX', 'text' => __('Ugandan shilling', 'inwavethemes')),
                array('value' => 'USD', 'text' => __('United States dollar', 'inwavethemes')),
                array('value' => 'UYU', 'text' => __('Uruguayan peso', 'inwavethemes')),
                array('value' => 'UZS', 'text' => __('Uzbekistani som', 'inwavethemes')),
                array('value' => 'VEF', 'text' => __('Venezuelan bol&iacute;var', 'inwavethemes')),
                array('value' => 'VND', 'text' => __('Vietnamese &#x111;&#x1ed3;ng', 'inwavethemes')),
                array('value' => 'VUV', 'text' => __('Vanuatu vatu', 'inwavethemes')),
                array('value' => 'WST', 'text' => __('Samoan t&#x101;l&#x101;', 'inwavethemes')),
                array('value' => 'XAF', 'text' => __('Central African CFA franc', 'inwavethemes')),
                array('value' => 'XCD', 'text' => __('East Caribbean dollar', 'inwavethemes')),
                array('value' => 'XOF', 'text' => __('West African CFA franc', 'inwavethemes')),
                array('value' => 'XPF', 'text' => __('CFP franc', 'inwavethemes')),
                array('value' => 'YER', 'text' => __('Yemeni rial', 'inwavethemes')),
                array('value' => 'ZAR', 'text' => __('South African rand', 'inwavethemes')),
                array('value' => 'ZMW', 'text' => __('Zambian kwacha', 'inwavethemes'))
            );
        }

        public static function getIWCurrencySymbol($currency) {
            $symbols = array(
                'AED' => '&#x62f;.&#x625;',
                'AFN' => '&#x60b;',
                'ALL' => 'L',
                'AMD' => 'AMD',
                'ANG' => '&fnof;',
                'AOA' => 'Kz',
                'ARS' => '&#36;',
                'AUD' => '&#36;',
                'AWG' => '&fnof;',
                'AZN' => 'AZN',
                'BAM' => 'KM',
                'BBD' => '&#36;',
                'BDT' => '&#2547;&nbsp;',
                'BGN' => '&#1083;&#1074;.',
                'BHD' => '.&#x62f;.&#x628;',
                'BIF' => 'Fr',
                'BMD' => '&#36;',
                'BND' => '&#36;',
                'BOB' => 'Bs.',
                'BRL' => '&#82;&#36;',
                'BSD' => '&#36;',
                'BTC' => '&#3647;',
                'BTN' => 'Nu.',
                'BWP' => 'P',
                'BYR' => 'Br',
                'BZD' => '&#36;',
                'CAD' => '&#36;',
                'CDF' => 'Fr',
                'CHF' => '&#67;&#72;&#70;',
                'CLP' => '&#36;',
                'CNY' => '&yen;',
                'COP' => '&#36;',
                'CRC' => '&#x20a1;',
                'CUC' => '&#36;',
                'CUP' => '&#36;',
                'CVE' => '&#36;',
                'CZK' => '&#75;&#269;',
                'DJF' => 'Fr',
                'DKK' => 'DKK',
                'DOP' => 'RD&#36;',
                'DZD' => '&#x62f;.&#x62c;',
                'EGP' => 'EGP',
                'ERN' => 'Nfk',
                'ETB' => 'Br',
                'EUR' => '&euro;',
                'FJD' => '&#36;',
                'FKP' => '&pound;',
                'GBP' => '&pound;',
                'GEL' => '&#x10da;',
                'GGP' => '&pound;',
                'GHS' => '&#x20b5;',
                'GIP' => '&pound;',
                'GMD' => 'D',
                'GNF' => 'Fr',
                'GTQ' => 'Q',
                'GYD' => '&#36;',
                'HKD' => '&#36;',
                'HNL' => 'L',
                'HRK' => 'Kn',
                'HTG' => 'G',
                'HUF' => '&#70;&#116;',
                'IDR' => 'Rp',
                'ILS' => '&#8362;',
                'IMP' => '&pound;',
                'INR' => '&#8377;',
                'IQD' => '&#x639;.&#x62f;',
                'IRR' => '&#xfdfc;',
                'ISK' => 'Kr.',
                'JEP' => '&pound;',
                'JMD' => '&#36;',
                'JOD' => '&#x62f;.&#x627;',
                'JPY' => '&yen;',
                'KES' => 'KSh',
                'KGS' => '&#x43b;&#x432;',
                'KHR' => '&#x17db;',
                'KMF' => 'Fr',
                'KPW' => '&#x20a9;',
                'KRW' => '&#8361;',
                'KWD' => '&#x62f;.&#x643;',
                'KYD' => '&#36;',
                'KZT' => 'KZT',
                'LAK' => '&#8365;',
                'LBP' => '&#x644;.&#x644;',
                'LKR' => '&#xdbb;&#xdd4;',
                'LRD' => '&#36;',
                'LSL' => 'L',
                'LYD' => '&#x644;.&#x62f;',
                'MAD' => '&#x62f;. &#x645;.',
                'MAD' => '&#x62f;.&#x645;.',
                'MDL' => 'L',
                'MGA' => 'Ar',
                'MKD' => '&#x434;&#x435;&#x43d;',
                'MMK' => 'Ks',
                'MNT' => '&#x20ae;',
                'MOP' => 'P',
                'MRO' => 'UM',
                'MUR' => '&#x20a8;',
                'MVR' => '.&#x783;',
                'MWK' => 'MK',
                'MXN' => '&#36;',
                'MYR' => '&#82;&#77;',
                'MZN' => 'MT',
                'NAD' => '&#36;',
                'NGN' => '&#8358;',
                'NIO' => 'C&#36;',
                'NOK' => '&#107;&#114;',
                'NPR' => '&#8360;',
                'NZD' => '&#36;',
                'OMR' => '&#x631;.&#x639;.',
                'PAB' => 'B/.',
                'PEN' => 'S/.',
                'PGK' => 'K',
                'PHP' => '&#8369;',
                'PKR' => '&#8360;',
                'PLN' => '&#122;&#322;',
                'PRB' => '&#x440;.',
                'PYG' => '&#8370;',
                'QAR' => '&#x631;.&#x642;',
                'RMB' => '&yen;',
                'RON' => 'lei',
                'RSD' => '&#x434;&#x438;&#x43d;.',
                'RUB' => '&#8381;',
                'RWF' => 'Fr',
                'SAR' => '&#x631;.&#x633;',
                'SBD' => '&#36;',
                'SCR' => '&#x20a8;',
                'SDG' => '&#x62c;.&#x633;.',
                'SEK' => '&#107;&#114;',
                'SGD' => '&#36;',
                'SHP' => '&pound;',
                'SLL' => 'Le',
                'SOS' => 'Sh',
                'SRD' => '&#36;',
                'SSP' => '&pound;',
                'STD' => 'Db',
                'SYP' => '&#x644;.&#x633;',
                'SZL' => 'L',
                'THB' => '&#3647;',
                'TJS' => '&#x405;&#x41c;',
                'TMT' => 'm',
                'TND' => '&#x62f;.&#x62a;',
                'TOP' => 'T&#36;',
                'TRY' => '&#8378;',
                'TTD' => '&#36;',
                'TWD' => '&#78;&#84;&#36;',
                'TZS' => 'Sh',
                'UAH' => '&#8372;',
                'UGX' => 'UGX',
                'USD' => '&#36;',
                'UYU' => '&#36;',
                'UZS' => 'UZS',
                'VEF' => 'Bs F',
                'VND' => '&#8363;',
                'VUV' => 'Vt',
                'WST' => 'T',
                'XAF' => 'Fr',
                'XCD' => '&#36;',
                'XOF' => 'Fr',
                'XPF' => 'Fr',
                'YER' => '&#xfdfc;',
                'ZAR' => '&#82;',
                'ZMW' => 'ZK'
            );
            return $symbols[$currency];
        }

        /**
         * Function truncate string by number of word
         * @param string $string
         * @param type $length
         * @param type $etc
         * @return string
         */
        public function truncateString($string, $length, $etc = '...') {
            $string = strip_tags($string);
            if (str_word_count($string) > $length) {
                $words = str_word_count($string, 2);
                $pos = array_keys($words);
                $string = substr($string, 0, $pos[$length]) . $etc;
            }
            return $string;
        }

        public function inMedicalAddImageSize() {
            add_image_size('inmedical-large', 800, 420, 'center');
            add_image_size('inmedical-thumb', 400, 250, 'center');
        }

        public function iweDisplayPagination($query = '') {
            if (!$query) {
                global $wp_query;
                $query = $wp_query;
            }

            $big = 999999999; // need an unlikely integer

            $paginate_links = paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $query->max_num_pages,
                'next_text' => '&raquo;',
                'prev_text' => '&laquo'
            ));
            // Display the pagination if more than one page is found
            if ($paginate_links) :
                ?>

                <div class="iwevent-pagination clearfix">
                    <?php echo esc_html($paginate_links); ?>
                </div>

                <?php
            endif;
        }

        public function initPluginThemes() {
            $files = array('single-inmedical.php', 'single-indoctor.php', 'single-indepartment.php');
            $template_path = get_template_directory();
            foreach ($files as $file) {
                if (!file_exists($template_path . '/' . $file)) {
                    $theme_plugin_path = WP_PLUGIN_DIR . '/inmedical/includes/themes/';
                    copy($theme_plugin_path . $file, $template_path . '/' . $file);
                }
            }
        }

        function getLocalDate($format, $timestamp) {
            $current_offset = get_option('gmt_offset');
            $date = date_i18n($format, $timestamp);
            if ($current_offset) {
                $date = date_i18n($format, $timestamp + $current_offset * 60 * 60);
            }
            echo esc_attr($date);
        }

        public function getMoneyFormated($value, $currency = '') {
            global $inf_settings;
            if (!$currency) {
                $currency = $inf_settings['general']['currency'];
            }
            $currency_sym = $this->getIWCurrencySymbol($currency);
            $currency_pos = $inf_settings['general']['currency_pos'];
            $result = $currency_sym . $value;
            if ($currency_pos == 'left_space') {
                $result = $currency_sym . ' ' . $value;
            }
            if ($currency_pos == 'right') {
                $result = $value . $currency_sym;
            }
            if ($currency_pos == 'right_space') {
                $result = $value . ' ' . $currency_sym;
            }
            echo esc_attr($result);
        }

        function getNoticeMessage() {
            $inwave_Session = new Inwave_Session();
            $message = $inwave_Session->get('inwave_message');
            if ($message) {
                printf($message);
                $inwave_Session->clearSession('inwave_message');
            }
        }

        public function margeArray($array) {
            if (!is_array($array)) {
                return;
            }
            $key_title = $array['key_title'];
            $key_value = $array['key_value'];
            $new_array = array();
            $i = 0;
            foreach ($key_title as $value) {
                if ($value) {
                    $new_array[] = array('key_title' => sanitize_textarea_field($value), 'key_value' => sanitize_textarea_field($key_value[$i]));
                    $i++;
                }
            }
            return $new_array;
        }

        public function imageFieldRender($id, $name, $image_id, $class = '') {
            $html = array();
            $html[] = '<div class="iw-image-field-render">';
            $html[] = '<div class="imd-image-field">';
            if ($image_id) {
                $img = wp_get_attachment_image_src($image_id);
                $html[] = '<div class="image-preview"><div class="close-overlay"><span class="image-delete"><i class="fa fa-times"></i></span></div><img width="100%" src="' . $img[0] . '"/></div>';
            } else {
                $html[] = '<div class="image-preview iw-hidden"></div>';
            }
            $html[] = '<div class="image-add-image"><span><i class="fa fa-plus"></i></span></div>';
            $html[] = '<input id="' . $id . '" type="hidden" value="' . ($image_id ? $image_id : '') . '" name="' . $name . '" class="iw-field iwe-image-field-data ' . $class . '"/>';
            $html[] = '</div>';
            $html[] = '</div>';
            $html[] = '<div style="clear:both;"></div>';
            return implode($html);
        }

        /**
         * Function that will check if value is a valid HEX color.
         */
        public function checkColor($value) {

            if (preg_match('/^#[a-f0-9]{6}$/i', $value)) { // if user insert a HEX color with #     
                return true;
            }

            return false;
        }

        static function getIwMapFieldHtml($fname, $fvalue, $class) {
            global $imd_settings;
            if ($fvalue) {
                $mapoptions = json_decode($fvalue);
            }
            $mapProperties = array(
                'zoom' => isset($mapoptions->zoomlv) ? intval($mapoptions->zoomlv) : (isset($imd_settings['general']['map_zoom_lever']) ? intval($imd_settings['general']['map_zoom_lever']) : 8),
                'center' => array('lat' => (isset($mapoptions->lat) ? $mapoptions->lat : -33.8665433), 'lng' => (isset($mapoptions->lng) ? $mapoptions->lng : 151.1956316)),
                'zoomControl' => true,
                'scrollwheel' => true,
                'disableDoubleClickZoom' => true,
                'draggable' => true,
                'panControl' => true,
                'mapTypeControl' => true,
                'scaleControl' => true,
                'overviewMapControl' => true
            );
            ob_start();
            ?>
            <div class="iw-map-field iw-map-container" data-map-options="<?php echo htmlspecialchars(json_encode($mapProperties)); ?>">
                <div class="iw-map-wrap">
                    <div class="map-preview" style="height:350px;">
                    </div>
                </div>
                <div class="description">
                    <ul>
                        <li><?php _e('- Click on map to set map position.', 'inwavethemes'); ?></li>
                        <li><?php _e('- Drag and Drop marker to set map position.', 'inwavethemes'); ?></li>
                        <li><?php _e('- ZoomIn or ZoomOut to change and set map Zoom Level.', 'inwavethemes'); ?></li>
                    </ul>
                </div>
                <input type="hidden" value="<?php echo $fvalue ? htmlentities($fvalue) : ''; ?>" class="iw-map <?php echo $class; ?>" name="<?php echo $fname; ?>"/>
            </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

    }

}
