<?php
/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Ma5 12, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of extrafield
 *
 * @developer duongca
 */
wp_enqueue_script('jquery-datetimepicker');
wp_enqueue_style('jquery-datetimepicker');
$date_options = array(
    'format' => get_option('date_format'),
    'timepicker' => false
);
$utility->getNoticeMessage();
?>
<div class="iwe-wrap wrap">
    <h2 class="in-title"><?php echo __('Bookings', 'inwavethemes'); ?>
        <a class="bt-button add-new-h2" href ="javascript:void(0);" onclick="javascript:document.getElementById('speakers-form').submit();
                return false;"><?php echo __("Delete"); ?></a>
    </h2>
    <form class="filter" action="<?php echo admin_url(); ?>admin-post.php" method="post" name="filter">
        <div class="iwe-filter tablenav top">
            <div class="alignleft">
                <label><?php _e('Filter', 'inwavethemes'); ?></label>
                <input type="text" name="keyword" value="<?php echo filter_input(INPUT_GET, 'keyword'); ?>" placeholder="<?php echo __('Input keyword to search', 'inwavethemes'); ?>"/>

            </div>
            <div class="alignleft">
                <div class="field-input">
                    <input data-date-options="<?php echo htmlspecialchars(json_encode($date_options)); ?>" class="input-date" type="text" name="" value="<?php echo filter_input(INPUT_GET, 'date_from') ? date_i18n(get_option('date_format'), filter_input(INPUT_GET, 'date_from')) : ''; ?>" placeholder="<?php echo date_i18n(get_option('date_format')); ?>"/>
                    <input type="hidden" value="<?php echo filter_input(INPUT_GET, 'date_from'); ?>" name="date_from"/>
                </div>
            </div>
            <div class="alignleft">
                <div class="field-input">
                    <input data-date-options="<?php echo htmlspecialchars(json_encode($date_options)); ?>" class="input-date" type="text" name="" value="<?php echo filter_input(INPUT_GET, 'date_to') ? date_i18n(get_option('date_format'), filter_input(INPUT_GET, 'date_to')) : ''; ?>" placeholder="<?php echo date_i18n(get_option('date_format')); ?>"/>
                    <input type="hidden" value="<?php echo filter_input(INPUT_GET, 'date_to'); ?>" name="date_to"/>
                </div>
            </div>
            <div class="alignleft"><?php
                $args = array(
                    'post_type' => 'inmedical',
                    'numberposts' => '-1'
                );
                $events = get_posts($args);
                $eventData = array();
                foreach ($events as $ev) {
                    $eventData[] = array('text' => $ev->post_title, 'value' => $ev->ID);
                }
                echo $utility->selectFieldRender('', 'event', filter_input(INPUT_GET, 'event'), $eventData, __('Any', 'inwavethemes'), '', false);
                ?>
            </div>
            <div class="alignleft">
                <?php
                $status_select_data = array(
                    array('value' => '', 'text' => __('All', 'inwavethemes')),
                    array('value' => '3', 'text' => __('Pending', 'inwavethemes')),
                    array('value' => '1', 'text' => __('Accepted', 'inwavethemes')),
                    array('value' => '2', 'text' => __('Cancel', 'inwavethemes')),
                );
                echo $utility->selectFieldRender('', 'status', filter_input(INPUT_GET, 'status'), $status_select_data, null, '', false);
                ?>
            </div>
            <div class="alignleft">
                <input type="hidden" value="inMedicalFilter" name="action"/>
                <input class="button" type="submit" value="<?php _e('Search', 'inwavethemes'); ?>"/>
            </div>
        </div>
    </form>
    <form id="speakers-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="inMedicalDeleteBookings"/>
        <table class="iwbooking-list-table wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="width: 5%">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'inwavethemes'); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="name" class="manage-column column-author <?php echo $sorted == 'cus_name' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" style="width: 30%"><a href="<?php echo $order_link . '&orderby=cus_name&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Customer name', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th scope="col" id="type" class="manage-column column-categories <?php echo $sorted == 'event_post' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" style="width: 20%"><a href="<?php echo $order_link . '&orderby=event_post&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Event name', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th scope="col" id="type" class="manage-column column-categories <?php echo $sorted == 'date' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" style="width: 20%"><a href="<?php echo $order_link . '&orderby=date&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Event Date', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th scope="col" id="Published" class="manage-column column-categories <?php echo $sorted == 'email' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" style="width: 15%"><a href="<?php echo $order_link . '&orderby=email&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Email', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th scope="col" id="Published" class="manage-column column-categories <?php echo $sorted == 'status' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" style="width: 15%"><a href="<?php echo $order_link . '&orderby=status&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Status', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($bookings)) {
                    foreach ($bookings as $app) {
                        ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input id="cb-select-1" type="checkbox" name="fields[]" value="<?php echo $app->getId(); ?>"/>
                                <div class="locked-indicator"></div>
                            </th>
                            <td>
                                <a href="<?php echo admin_url('edit.php?post_type=indepartment&page=booking/edit&id=' . $app->getId()); ?>" title="<?php echo __('view this item', 'inwavethemes'); ?>">
                                    <strong><?php echo ($app->getFirst_name() . ' ' . $app->getLast_name()); ?></strong>
                                </a>
                                <div class="row-actions">
                                    <a href="<?php echo admin_url('edit.php?post_type=indepartment&page=booking/edit&id=' . $app->getId()); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a> |
                                    <a class="submitdelete" title="<?php echo __('Move this item to the Trash', 'inwavethemes'); ?>" href="<?php echo admin_url("admin-post.php?action=inMedicalDeleteBooking&id=" . $app->getId()); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                                </div>
                            </td>
                            <td><?php
                                $event = get_post($app->getEvent_post());
                                print($event->post_title);
                                ?></td>

                            <td><?php
                                echo date_i18n(get_option('date_format'), $app->getAppointment_date());
                                ?></td>
                            <td><?php echo $app->getEmail(); ?></td>
                            <td><div class="booking-status"><?php echo $app->getStatus() == 1 ? '<span class="accepted">' . __('Accepted', 'inwavethemes') . '</span>' : (($app->getStatus() == 2) ? '<span class="cancel">' . __('Cancel', 'inwavethemes') . '</span>' : '<span data-id="' . $app->getId() . '" title="' . __('Click to quick accept', 'inwavethemes') . '" class="pendding">' . __('Accept now', 'inwavethemes') . '</span>'); ?></div></td>
                        </tr>
                    <?php }
                    ?>
                    <tr>
                        <td class="row-pagination" colspan="6">
                            <?php
                            $page_list = $paging->pageList($_GET['pagenum'], $pages);
                            echo $page_list;
                            ?>
                        </td>
                    </tr> 
                <?php } else { ?>
                    <tr>
                        <td colspan="6"><?php echo __('No result', 'inwavethemes'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </form>
</div>
