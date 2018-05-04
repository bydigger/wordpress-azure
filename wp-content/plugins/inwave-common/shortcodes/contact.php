<?php
/*
 * @package Inwave Athlete
 * @version 1.0.0
 * @created Mar 31, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iw_contact
 *
 * @Developer duongca
 */
if (!class_exists('Inwave_Contact')) {

    class Inwave_Contact extends Inwave_Shortcode
    {
        protected $name = 'inwave_contact';

        function __construct()
        {
            parent::__construct();
            add_action('wp_ajax_nopriv_sendMessageContact', array($this, 'sendMessageContact'));
            add_action('wp_ajax_sendMessageContact', array($this, 'sendMessageContact'));
        }

        function init_params()
        {
            return array(
                'name' => __("Contact Form", 'inwavethemes'),
                'description' => __('Show contact form', 'inwavethemes'),
                'base' => $this->name,
                'icon' => 'iw-default',
                'category' => 'Custom',
                'params' => array(
                    /*array(
                        "type" => "dropdown",
                        "group" => "Style",
                        "heading" => "Style",
                        "param_name" => "style",
                        "value" => array(
                            "Default" => "style1",
                        ),
                    ),*/
                    array(
                        "type" => "iwevent_preview_image",
                        "heading" => __("Preview Style", "inwavethemes"),
                        "param_name" => "preview_default",
                        "value" => get_template_directory_uri() . '/assets/images/shortcodes/contact-form.jpg',
                    ),
                    array(
                        'type' => 'textfield',
                        "holder" => "div",
                        "heading" => __("Receiver Email", "inwavethemes"),
                        "value" => "",
                        "param_name" => "receiver_email",
                        "description" => __('If not specified, Admin E-mail Address in General setting will be used', "inwavethemes")
                    ),
					 array(
                        'type' => 'textfield',
                         "holder" => "div",
                        "heading" => __("Title contact form", "inwavethemes"),
                        "value" => "",
                        "param_name" => "title_contact_form"
                    ),
					 array(
                        'type' => 'textfield',
                        "heading" => __("Head Office", "inwavethemes"),
                        "value" => "",
                        "param_name" => "head_office",
                         "description" => __("You can add '///' to insert line break tag (br)", "inwavethemes"),
                    ),
					 array(
                        'type' => 'textfield',
                        "heading" => __("Phone Numbers", "inwavethemes"),
                        "value" => "",
                        "param_name" => "phone_numbers",
                         "description" => __("You can add '///' to insert line break tag (br)", "inwavethemes"),
                    ),
					 array(
                        'type' => 'textfield',
                        "heading" => __("Email Address", "inwavethemes"),
                        "value" => "",
                        "param_name" => "email_address",
                         "description" => __("You can add '///' to insert line break tag (br)", "inwavethemes"),
                    ),
					 array(
                        'type' => 'textfield',
                        "heading" => __("Get Support Services", "inwavethemes"),
                        "value" => "",
                        "param_name" => "support_services",
                         "description" => __("You can add '///' to insert line break tag (br)", "inwavethemes"),
                    ),
                    array(
                        'type' => 'textfield',
                        "heading" => __("Button text", "inwavethemes"),
                        "value" => "",
                        "param_name" => "button_text"
                    ),
//                    array(
//                        "type" => "dropdown",
//                        "heading" => __("Show email", "inwavethemes"),
//                        "param_name" => "show_email",
//                        "description" => __("Show email field", 'inwavethemes'),
//                        "value" => array(
//                            'Yes' => 'yes',
//                            'No' => 'no',
//                        )
//                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Extra Class", "inwavethemes"),
                        "param_name" => "class",
                        "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', "inwavethemes")
                    )
                )
            );
        }

        // Shortcode handler function for list Icon
        function init_shortcode($atts, $content = null)
        {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $this->name, $atts ) : $atts;

            $output = $receiver_email = $head_office = $phone_numbers = $support_services = $email_address = $title_contact_form = $button_text = $show_name = $show_email = $show_website = $show_mobile = $show_message = $description_text = $style = $class = '';
            extract(shortcode_atts(array(
                'receiver_email' => 'yes',
                'head_office' => '',
                'phone_numbers' => '',
                'email_address' => '',
                'support_services' => '',
                'button_text' => '',
                'description_text' => '',
                'show_name' => 'yes',
                'show_email' => 'yes',
                'show_mobile' => 'yes',
                'show_website' => 'yes',
                'show_message' => 'yes',
                'style' => 'style1',
				'title_contact_form' => '',
                'class' => ''
            ), $atts));
            ob_start();

            $head_office= preg_replace('/\/\/\//i', '<br />', $head_office);
            $phone_numbers= preg_replace('/\/\/\//i', '<br />', $phone_numbers);
            $support_services= preg_replace('/\/\/\//i', '<br />', $support_services);
            $email_address= preg_replace('/\/\/\//i', '<br />', $email_address);
            switch ($style) {
                case 'style1':
                ?>
                <div class="iw-contact iw-contact-us iw-contact-address <?php echo esc_attr($style.' '.$class); ?>">
                    <div class="row">
                        <div class="ajax-overlay">
                            <span class="ajax-loading"><i class="fa fa-spinner fa-spin fa-2x"></i></span>
                        </div>
                        <div class="headding-bottom"></div>
                        <div class="col-md-8 col-md-8 col-xs-12">
							<div class="iw-form-group">
								<form method="post" name="contact-form">
									<?php if ($title_contact_form){ ?>
										<h3 class="title_contact_form"><span><?php echo $title_contact_form; ?></span></h3>
									<?php } ?>
									<div class="row">
										<?php if ($show_name == 'yes'): ?>
											<div class="form-group iw-display col-md-6 col-sm-12 col-xs-12">
												<input type="text" placeholder="<?php echo __('First name', 'inwavethemes'); ?>"
													   required="required" id="name" class="control" name="name">
											</div>
										<?php
										endif;
										if ($show_email == 'yes'):
											?>
											<div class="form-group iw-display col-md-6 col-sm-12 col-xs-12">
												<input type="email" placeholder="<?php echo __('Email address', 'inwavethemes'); ?>"
													   required="required" id="email" class="control" name="email">
											</div>
										<?php
										endif;
										if ($show_website == 'yes'):
											?>
											<div class="form-group iw-display col-md-12 col-sm-12 col-xs-12">
												<input type="text" placeholder="<?php echo __('Your website', 'inwavethemes'); ?>"
													   id="website" class="control" name="website">
											</div>
										<?php
										endif;
										if ($show_message == 'yes'):
											?>
											<div class="form-group iw-display iw-textarea-form iw-float-none col-md-12 col-sm-12 col-xs-12">
												<textarea placeholder="<?php echo __('Write message', 'inwavethemes'); ?>"
														  id="message" class="control" required="required" name="message"></textarea>
											</div>
										<?php endif; ?>
										<div class="form-group form-submit clearfix col-xs-12">
											<input name="action" type="hidden" value="sendMessageContact">
											<input name="mailto" type="hidden" value="<?php echo $receiver_email; ?>">
											<button class="btn-submit theme-bg" name="submit"
													type="submit"><?php echo $button_text? $button_text: __('SEND MESSAGE', 'inwavethemes'); ?></button>
											<span>(*) <?php echo __('is require information', 'inwavethemes'); ?></span>
										</div>
										<div class="form-group col-md-12 form-message"></div>
									</div>
								</form>
							</div>
						</div>
                        <div class="col-md-4 col-md-4 col-xs-12">
							<div class="iw-contact-address-right">
								<div class="iw-address iw-head-office">
									<div class="icon"><i class="fa fa-building-o"></i></div>
									<div class="text-address">
										<h4 class="title"><?php echo __('Head office', 'inwavethemes'); ?></h4>
										<div class="address"><?php echo $head_office ?></div>
									</div>
								</div>
								<div class="iw-address iw-phone-numbers">
									<div class="icon"><i class="fa fa-phone-square"></i></div>
									<div class="text-address">
										<h4 class="title"><?php echo __('Phone numbers', 'inwavethemes'); ?></h4>
										<div class="address"><?php echo $phone_numbers ?></div>
									</div>
								</div>
								<div class="iw-address iw-email-address">
									<div class="icon"><i class="fa fa-envelope-o"></i></div>
									<div class="text-address">
										<h4 class="title"><?php echo __('Email address', 'inwavethemes'); ?></h4>
										<div class="address"><?php echo $email_address ?></div>
									</div>
								</div>
								<div class="iw-address iw-support-services">
									<div class="icon"><i class="fa fa-microphone"></i></div>
									<div class="text-address">
										<h4 class="title"><?php echo __('Get support services', 'inwavethemes'); ?></h4>
										<div class="address"><?php echo $support_services ?></div>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
                    <?php
                    break;
            }
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        //Ajax iwcSendMailTakeCourse
        function sendMessageContact()
        {
            $result = array();
            $result['success'] = false;
            $mailto = isset($_POST['mailto'])? $_POST['mailto'] : '';
            if(!$mailto){
                $mailto = get_option('admin_email');
            }
            $email = isset($_POST['email'])? $_POST['email'] : '';
            $name = isset($_POST['name'])? $_POST['name'] : '';
            $mobile = isset($_POST['mobile'])? $_POST['mobile'] : '';
            $website = isset($_POST['website'])? $_POST['website'] : '';
            $message = isset($_POST['message'])? $_POST['message'] : '';
            $title = __('Email from Contact Form', 'inwavethemes') . ' ['. $email.']';

            $html = '<html><head><title>' . $title . '</title>
                    </head><body><p>' . __('Hi Admin,', 'inwavethemes') . '</p><p>' . __('This email was sent from contact form', 'inwavethemes') . '</p><table>';

            if ($name) {
                $html .= '<tr><td>' . __('Name', 'inwavethemes') . '</td><td>' . $name . '</td></tr>';
            }
            if ($email) {
                $html .= '<tr><td>' . __('Email', 'inwavethemes') . '</td><td>' . $email . '</td></tr>';
            }
            if ($mobile) {
                $html .= '<tr><td>' . __('Mobile', 'inwavethemes') . '</td><td>' . $mobile . '</td></tr>';
            }
            if ($website) {
                $html .= '<tr><td>' . __('Website', 'inwavethemes') . '</td><td>' . $website . '</td></tr>';
            }
            if ($message) {
                $html .= '<tr><td>' . __('Message', 'inwavethemes') . '</td><td>' . $message . '</td></tr>';
            }
            $html .= '</tr></table></body></html>';

            // To send HTML mail, the Content-type header must be set
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            if (wp_mail($mailto, $title, $html, $headers)) {
                $result['success'] = true;
                $result['message'] = __('Your message was sent, we will contact you soon', 'inwavethemes');
            } else {
                $result['message'] = __('Can\'t send message, please try again', 'inwavethemes');
            }
            echo json_encode($result);
            exit();
        }
    }
}

new Inwave_Contact();

