/* 
 * @package Inwave Event
 * @version 1.0.0
 * @created Mar 11, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwevent_admin
 *
 * @developer duongca
 */
(function ($) {
    "use strict";
    $(document).ready(function () {
        //tab
        var tabItems = $('.inmedical-admin-menu .sub-menu-item'),
                content_list = $('.imd-setting-tab .tab-item'),
                list = tabItems.find('.tab-item');
        $('.tab-item', tabItems).click(function () {
            if ($(this).hasClass('active')) {
                return;
            }
            $(this).addClass('active');
            var itemclick = this;
            list.each(function () {
                if (list.index(this) !== list.index(itemclick) && $(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
            loadTabContent();
        });

        function loadTabContent() {
            var item_active = tabItems.find('.tab-item.active');
            content_list.addClass('iw-hidden');
            $(content_list.get(list.index(item_active))).removeClass('iw-hidden');
        }
        loadTabContent();
        //end tab

        $('.iw-metabox-fields .add-row').click(function () {
            var type = '';
            if ($(this).hasClass('education-profile')) {
                type = 'education-profile';
            }
            if ($(this).hasClass('social')) {
                type = 'social';
            }
            var html = addMetaBoxRow(type);
            $(html).insertBefore($(this).parents('tr'));
        });

        $('.iw-metabox-fields').on('click', '.remove-button', function () {
            $(this).parents('tr').remove();
        });

        $('.iw-metabox-fields select.event-repeat').change(function () {
            var val = $(this).val();
            if (val === 'none') {
                $('.iw-metabox-fields .repeat-type').not('.none').fadeOut();
            }
            if (val === 'daily') {
                $('.iw-metabox-fields .repeat-type').not('.daily').fadeOut();
                $('.iw-metabox-fields .repeat-type.daily').fadeIn();
            }
            if (val === 'weekly') {
                $('.iw-metabox-fields .repeat-type').not('.weekly').fadeOut();
                $('.iw-metabox-fields .repeat-type.weekly').fadeIn();
            }
            if (val === 'monthly') {
                $('.iw-metabox-fields .repeat-type').not('.monthly').fadeOut();
                $('.iw-metabox-fields .repeat-type.monthly').fadeIn();
            }
            if (val === 'yearly') {
                $('.iw-metabox-fields .repeat-type').not('.yearly').fadeOut();
                $('.iw-metabox-fields .repeat-type.yearly').fadeIn();
            }
            if (val === 'custom') {
                $('.iw-metabox-fields .repeat-type').not('.custom').fadeOut();
                $('.iw-metabox-fields .repeat-type.custom').fadeIn();
            }
        }).trigger('change');

        $('.iw-metabox-fields select.repeat-mode').change(function () {
            var val = $(this).val();
            if (val === 'single') {
                $('.iw-metabox-fields .repeat-mode-by-dow').fadeOut();
            } else {
                $('.iw-metabox-fields .repeat-mode-by-dow').fadeIn();
            }
        }).trigger('change');

        var frame;
        $('.iw-image-field-render').on('click', '.imd-image-field div.image-add-image', function (event) {
            var e_target = $(this);

            event.preventDefault();

            // Create a new media frame
            frame = wp.media({
                state: 'insert',
                frame: 'post',
                library: {
                    type: 'image'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            }).open();

            frame.menu.get('view').unset('featured-image');

            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: 'Insert',
                    click: function () {
                        // Get media attachment details from the frame state
                        var attachment = frame.state().get('selection').first().toJSON();

                        // Send the attachment URL to our custom image input field.
                        e_target.parent().find('div.image-preview').html('<div class="close-overlay"><span class="image-delete"><i class="fa fa-times"></i></span></div><img src="' + attachment.url + '" alt=""/>').removeClass('iw-hidden');
                        var imgElement = e_target.parent().find('div.image-preview img');
                        if (imgElement.height() > imgElement.width()) {
                            imgElement.css('width', '100%');
                        } else {
                            imgElement.css('height', '100%');
                        }

                        // Send the attachment id to our hidden input
                        e_target.parent().parent().find('.iw-field.iwe-image-field-data').val(attachment.id);
                        frame.close();
                    }
                } // end insert
            });
        });

        // DELETE IMAGE LINK
        $('.iw-image-field-render').on('click', '.imd-image-field .image-delete', function (event) {
            var e_target = $(this);

            event.preventDefault();
            // Delete the image id from the hidden input
            e_target.parents('.imd-image-field').find('.iw-field.iwe-image-field-data').val('');

            // Clear out the preview image
            e_target.parents('.imd-image-field').find('div.image-preview').addClass('iw-hidden').html('');
        });

        $('.iw-metabox-fields .field-input .input-date, form.filter .field-input .input-date').each(function () {
            var input = $(this), options = input.data('date-options');
            options.onClose = onCloseDatePicker;
            input.datetimepicker(options);
        });

        function onCloseDatePicker(ct, input) {
            if (ct !== null && input.val() !== '') {
                input.parent().find('input[type="hidden"]').val(parseInt((ct.getTime() / 1000) - (ct.getTimezoneOffset() * 60)));
            }else{
                input.parent().find('input[type="hidden"]').val('');
            }
        }

        if($('.event-wp-color-picker').length){
            $('.event-wp-color-picker').wpColorPicker();
        }

        $('.iw-metabox-fields').on('click', '.add-custom-repeat span', function () {
            var c_start = $('.iw-metabox-fields .custom-time-start'),
                    c_v_start = $('.iw-metabox-fields .custom-time-start-value'),
                    c_end = $('.iw-metabox-fields .custom-time-end'),
                    c_v_end = $('.iw-metabox-fields .custom-time-end-value');
            if (!c_start.val() || !c_v_start.val() || !c_end.val() || !c_v_end.val()) {
                $('.iw-metabox-fields .error-message').show();
                return;
            }
            $('.iw-metabox-fields .error-message').hide();
            var html = '<div class="repeat-item">From: ' + c_start.val() + ' - To: ' + c_end.val() + ' <span>remove</span><input type="hidden" value="' + c_v_start.val() + '|' + c_v_end.val() + '" name="event-settings[custom-repeat][]"/></div>';
            $('.list-custom-repeat .list-custom-repeat-items').append(html);
            c_start.val('');
            c_v_start.val('');
            c_end.val('');
            c_v_end.val('');
        });

        $('.iw-metabox-fields .list-custom-repeat-items').on('click', '.repeat-item span', function () {
            $(this).parent().remove();
        });

        $('.iw-metabox-fields select.department').change(function () {
            var val = $(this).val();
            var doctor = $('.iw-metabox-fields select.doctor').data('current-value');
            $.ajax({
                type: "POST",
                url: inMedicalCfg.ajaxUrl,
                data: {action: 'loadDoctorOptions', ajax_nonce: inMedicalCfg.security, department: val, doctor: doctor},
                success: function (result) {
                    $('.iw-metabox-fields select.doctor').html(result);
                }
            });
        }).trigger('change');

        $('.booking-status .pendding').click(function () {
            var item_target = $(this);
            var itemId = item_target.data('id');
            $.ajax({
                type: "POST",
                url: inMedicalCfg.ajaxUrl,
                data: {action: 'bookingEventAccept', ajax_nonce: inMedicalCfg.security, id: itemId},
                success: function (result) {
                    item_target.parent().html(result);
                }
            });
        }).trigger('change');
        
        $('.booking-update select[name="status"]').change(function(){
            var val = $(this).val();
            if(val === '2'){
                $('.booking-update tr.ev-reason').show();
            }else{
                $('.booking-update tr.ev-reason').hide();
            }
        }).trigger('change');


        //CUSTOM BY MR HOA.
        if ($('.appointment-list').length) {
            $('.appointment-list').sortable({
                handle: ".handle",
                connectWith: ".appointment-list",
                stop: function (event, ui) {
                    var parent = ui.item.closest('.appointment-list');
                    var ids = [];
                    parent.find('.appointment-item').each(
                            function () {
                                ids.push($(this).data('id'));
                            }
                    );

                    $.ajax({
                        type: "POST",
                        url: inMedicalCfg.ajaxUrl,
                        data: {action: 'sortAppointment', ajax_nonce: inMedicalCfg.security, ids: ids, id: ui.item.data('id'), day: parent.data('day')},
                        dataType: 'json',
                        beforeSend: function () {

                        },
                        success: function (result) {
                            console.log(result);
                            if (result.success == true) {

                            }
                        }
                    });
                },
            })/*.disableSelection()*/;
        }

        //cancel all
        $(document).on('click', '.appointment-cancel', function () {
            var parent = $(this).closest('.popover');
            $('[aria-describedby="' + parent.attr('id') + '"]').popover('hide');
        });

        //disable time_end <= time_start
        $(document).on('change', 'select[name="time_start"]', function () {
            var value = $(this).val();
            $(this).closest('.appointment-form').find('select[name="time_end"] option').each(function () {
                if (parseInt($(this).val()) <= parseInt(value)) {
                    $(this).attr("disabled", "disabled");
                }
            })
        });

        //begin delete
        $(document).on('click', '.appointment-delete', function () {
            if ($(this).data('bs.popover')) {
                if ($(this).data('bs.popover').$tip.hasClass('in')) {
                    $(this).popover('hide');
                } else {
                    $(this).popover('show');
                }
                return false;
            } else {
                $(this).popover({
                    html: true,
                    content: function () {
                        return $('#confirm-appointment-delete').html();
                    },
                    template: '<div class="popover" role="tooltip"><div class="p-arrow"></div><div class="popover-content"></div></div>',
                    placement: 'bottom',
                    trigger: 'manual'
                }).popover('show');
            }
        });

        $(document).on('click', '.delete-appointment', function () {
            var parent = $(this).closest('.appointment-item');
            var self = $(this);
            var original_text = self.text();
            $.ajax({
                type: "POST",
                url: inMedicalCfg.ajaxUrl,
                data: {action: 'deleteAppointment', ajax_nonce: inMedicalCfg.security, id: parent.data('id')},
                dataType: 'json',
                beforeSend: function () {
                    self.addClass('processing');
                    self.text(self.data('process-text')).prop('disabled', true);
                },
                success: function (result) {
                    self.text(original_text).prop('disabled', false);
                    if (result.success == true) {
                        parent.remove();
                    }
                }
            });
        });
        //end delete

        //duplicate
        $(document).on('click', '.duplicate-appointment', function () {
            var parent = $(this).closest('.appointment-item');
            var self = $(this);
            var original_text = self.html();
            $.ajax({
                type: "POST",
                url: inMedicalCfg.ajaxUrl,
                data: {action: 'duplicateAppointment', ajax_nonce: inMedicalCfg.security, id: parent.data('id')},
                dataType: 'json',
                beforeSend: function () {
                    self.addClass('processing');
                    self.html('<i class="fa fa-spinner fa-spin"></i>').addClass('disabled');
                },
                success: function (result) {
                    self.html(original_text).removeClass('disabled');
                    if (result.success == true) {
                        parent.closest('.appointment-list').append(result.html);
                    }
                }
            });
        });

        //Begin add new appointment
        $('.appointment-add').click(function (e) {
            e.preventDefault();
            //$('.appointment-edit').popover('destroy');
            $('.appointment-add').not(this).popover('destroy');
            if ($(this).data('bs.popover')) {
                if ($(this).data('bs.popover').$tip.hasClass('in')) {
                    $(this).popover('hide');
                } else {
                    $(this).popover('show');
                }
                return false;
            } else {
                $(this).popover({
                    html: true,
                    content: function () {
                        return $('#appointment-form').html();
                    },
                    template: '<div class="popover" role="tooltip"><div class="p-arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
                    placement: 'bottom',
                    trigger: 'manual'
                }).on('inserted.bs.popover', function (self) {
                    var parent = $(self.target).next('.popover');
                    var day = $(self.target).data('appointment-day');
                    parent.find('input[name="day"]').val(day);
                    parent.find('input[name="date_start"]').datetimepicker({
                        timepicker: false,
                        format: 'Y/m/d',
                        onSelectDate: function (ct, $i) {
                            $i.closest('.popover').find('input[name="date_end"]').datetimepicker('setOptions', {minDate: $i.val()});
                        }
                    });
                    parent.find('input[name="date_end"]').datetimepicker({
                        timepicker: false,
                        format: 'Y/m/d',
                    });
                }).popover('show');
            }
        });

        $(document).on('click', '.add-appointment, .add-all-appointment', function () {
            var self = $(this);
            $('#imd-respon').hide();
            var appointment_form = $(this).closest('.appointment-form');
            var title = appointment_form.find('input[name="title"]').val();
            var day = appointment_form.find('input[name="day"]').val();
            var slot = appointment_form.find('input[name="slot"]').val();
            var time_start = appointment_form.find('select[name="time_start"]').val();
            var time_end = appointment_form.find('select[name="time_end"]').val();
            var doctor = appointment_form.find('select[name="doctor"]').val();
            var date_start = appointment_form.find('input[name="date_start"]').val();
            var date_end = appointment_form.find('input[name="date_end"]').val();
            if (!title || !time_start || !time_end || !doctor || !slot) {
                $('#imd-respon').html('Please input all required field.').show();
                return false;
            }

            var add_all = self.hasClass('add-all-appointment') ? 1 : 0;
            var original_text = self.html();
            $.ajax({
                type: "POST",
                url: inMedicalCfg.ajaxUrl,
                data: {action: 'addAppointment', ajax_nonce: inMedicalCfg.security, add_all: add_all, title: title, doctor: doctor, day: day, slot: slot, time_start: time_start, time_end: time_end, date_start: date_start, date_end: date_end},
                dataType: 'json',
                beforeSend: function () {
                    self.addClass('processing');
                    self.html('<i class="fa fa-spinner fa-spin"></i>').addClass('disabled');
                },
                success: function (result) {
                    if (result.success == true) {
                        for (var key in result.appointments) {
                            if (!result.appointments.hasOwnProperty(key))
                                continue;
                            $('td.appointment-' + key + ' .appointment-list').append(result.appointments[key]);

                        }
                        $('td.appointment-' + day + ' .appointment-add').popover('hide');
                    }
                    self.html(original_text).removeClass('disabled');
                }
            });
        });
        //End add new appointment

        /*Begin edit appointment*/
        $(document).on('click', '.edit-appointment', function () {
            var self = $(this);
            $('#imd-respon').hide();
            var appointment_form = $(this).closest('.appointment-form');
            var id = appointment_form.find('input[name="id"]').val();
            var title = appointment_form.find('input[name="title"]').val();
            var day = appointment_form.find('input[name="day"]').val();
            var slot = appointment_form.find('input[name="slot"]').val();
            var time_start = appointment_form.find('select[name="time_start"]').val();
            var time_end = appointment_form.find('select[name="time_end"]').val();
            var doctor = appointment_form.find('select[name="doctor"]').val();
            var date_start = appointment_form.find('input[name="date_start"]').val();
            var date_end = appointment_form.find('input[name="date_end"]').val();
            if (!title || !time_start || !time_end || !doctor) {
                $('#imd-respon').html('Please input all required field.').show();
                return false;
            }

            var original_text = self.html();
            $.ajax({
                type: "POST",
                url: inMedicalCfg.ajaxUrl,
                data: {action: 'editAppointment', ajax_nonce: inMedicalCfg.security, id: id, title: title, doctor: doctor, day: day, slot: slot, time_start: time_start, time_end: time_end, date_start: date_start, date_end: date_end},
                dataType: 'json',
                beforeSend: function () {
                    self.addClass('processing');
                    self.html('<i class="fa fa-spinner fa-spin"></i>').addClass('disabled');
                },
                success: function (result) {
                    self.html(original_text).removeClass('disabled');
                    $('div[data-id="' + id + '"] .appointment-edit').popover('destroy');
                    if (result.success == true) {
                        $('div[data-id="' + id + '"]').replaceWith(result.html);
                    }
                }
            });
        });

        $(document).on('click', '.appointment-edit', function () {
            var self = $(this);
            $('.appointment-add').popover('destroy');
            $('.appointment-edit').not(this).popover('destroy');
            if ($(this).data('bs.popover')) {
                if ($(this).next('.popover').hasClass('in')) {
                    $(this).popover('hide');
                } else {
                    $(this).popover('show');
                }
                return false;
            } else {
                $(this).popover({
                    html: true,
                    content: function () {
                        var content = $('#appointment-form').html();
                        content = $(content);
                        var item = $(this).closest('.appointment-item');
                        content.find('input[name="title"]').val(item.data('title'));
                        content.find('select[name="time_start"] option[value="' + item.data('time-start') + '"]').prop('selected', true);
                        content.find('select[name="time_end"] option[value="' + item.data('time-end') + '"]').prop('selected', true);
                        content.find('select[name="doctor"] option[value="' + item.data('doctor') + '"]').prop('selected', true);
                        content.find('input[name="date_start"]').val(item.data('date-start'));
                        content.find('input[name="date_end"]').val(item.data('date-end'));
                        content.find('input[name="slot"]').val(item.data('slot'));
                        content.find('input[name="id"]').val(self.closest('.appointment-item').data('id'));

                        return content;
                    },
                    template: '<div class="popover" role="tooltip"><div class="p-arrow"></div><div class="popover-content"></div></div>',
                    placement: 'bottom',
                    //animation: false,
                    trigger: 'manual'
                }).on('inserted.bs.popover', function (self) {
                    var parent = $(self.target).next('.popover');
                    parent.find('.add-appointment').hide();
                    parent.find('.add-all-appointment').hide();
                    parent.find('.edit-appointment').removeClass('hide');
                    parent.find('select[name="time_start"]').trigger('change');
                    parent.find('input[name="date_start"]').datetimepicker({
                        timepicker: false,
                        format: 'Y/m/d',
                        onSelectDate: function (ct, $i) {
                            $i.closest('.popover').find('input[name="date_end"]').datetimepicker('setOptions', {minDate: $i.val()});
                        }
                    });
                    parent.find('input[name="date_end"]').datetimepicker({
                        timepicker: false,
                        format: 'Y/m/d',
                    });
                });

                $(this).popover('show');
            }

        });
        //End edit appointment

        //changed appoinment status
        $('.appointment-accept').change(function () {
            var value = $(this).val();
            if (value == '-1') {
                $('.appointment-reason').fadeIn();
            } else {
                $('.appointment-reason').hide();
            }
        });

        //admin tab
//        if($('#imd-setting-tab').length){
//            $('#imd-setting-tab').tabs();
//        }

        //admin search appointment
        function imd_change_department_field(prop_empty) {
            var value = $('#book-department-search-field').val();
            if (value) {
                $('#book-doctor-search-field option[data-department!="' + value + '"]').hide();
                $('#book-doctor-search-field option[value=""]').show();
                $('#book-doctor-search-field option[data-department="' + value + '"]').show();
            } else {
                $('#book-doctor-search-field option').show();
            }

            if (prop_empty) {
                $('#book-doctor-search-field option[value=""]').prop('selected', true);
            }

        }

        $('#book-department-search-field').change(function () {
            imd_change_department_field(true);
        });

        if ($('#book-department-search-field').length && $('#book-department-search-field').val() != '') {
            if ($('#book-doctor-search-field').val()) {
                imd_change_department_field(false);
            } else {
                imd_change_department_field(true);
            }
        }

        if ($('#book-from-date-search-field').length) {
            $('#book-from-date-search-field').datetimepicker({
                timepicker: false,
                format: 'Y/m/d',
                onSelectDate: function (ct, $i) {
                    $('#book-to-date-search-field').datetimepicker('setOptions', {minDate: $i.val()});
                }
            });
            $('#book-to-date-search-field').datetimepicker({
                timepicker: false,
                format: 'Y/m/d',
            });
        }

        //select appointment
		$('select#imd_bak_appointment').change(function () {
			var val = $(this).val();
			if (val){
				$.ajax({
					type: "POST",
					url: inMedicalCfg.ajaxUrl,
					data: {action: 'newBookingAppointment', ajax_nonce: inMedicalCfg.security, appointment: val},
					dataType: 'json',
					success: function (result) {
						if (result.success == true){
							$('.imd_doctor_apo td.value').html(result.doctor);
							$('.imd_date_start td.value').html(result.date_start);
							$('.imd_date_end td.value').html(result.date_end);
							$('.imd_time_apo td.value').html(result.time);
						}
						//$('.iw-metabox-fields select.doctor').html(result);

					}
				});
			}

		}).trigger('change');

        $('.imd_book_date_apo input[name="event_date"]').each(function () {
			var input = $(this), options = input.data('date-options');
			options.onClose = onCloseDatePicker;
			input.datetimepicker(options);
		});
		$('.imd_book_date_apo input[name="event_date"]').change(function () {
			var dt_time = $(this).parent().find('input[type="hidden"]').val();
			if (dt_time){
				$.ajax({
					type: "POST",
					url: inMedicalCfg.ajaxUrl,
					data: {action: 'chooseDateBookingAppointment', ajax_nonce: inMedicalCfg.security, date_canbook: dt_time},
					dataType: 'json',
					success: function (result) {
						if (result.success == true){
							if (result.data1){
								var myJSON = result.data1;
								$.each($.parseJSON(myJSON), function(key,value){

								});
							}
							//$('#imd_bak_appointment').append();
						}
					}
				});
			}
		}).trigger('change');

    });
})(jQuery);


function addMetaBoxRow(type) {
    var html = '';
    if (type === 'education-profile') {
        html += '<tr class="alternate">';
        html += '<td>';
        html += '<input placeholder="Education Profile Title" type="text" size="20" name="iw_information[doctor_education_profile][key_title][]"/>';
        html += '</td>';
        html += '<td>';
        html += '<textarea placeholder="Education Profile Value" name="iw_information[doctor_education_profile][key_value][]"></textarea>';
        html += '</td>';
        html += '<td>';
        html += '<span class="button remove-button">Remove</span>';
        html += '</td>';
        html += '</tr>';
    }
    if (type === 'social') {
        html += '<tr class="alternate">';
        html += '<td>';
        html += '<select id="" name="iw_information[doctor_social_link][key_title][]">' + jQuery('.social-link-types select:last').html() + '</select>';
        html += '</td>';
        html += '<td>';
        html += '<input type="url" value="" name="iw_information[doctor_social_link][key_value][]" placeholder="Social Link Value">';
        html += '</td>';
        html += '<td>';
        html += '<span class="button remove-button">Remove</span>';
        html += '</td>';
        html += '</tr>';
    }
    return html;
}


