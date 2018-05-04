<?php

/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created Dec 1, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://www.inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * Description of single-infunding
 *
 * @developer duongca
 */
?>
<?php
new Inwave_Session();
get_header();
if (have_posts()) : while (have_posts()) : the_post();
        if (inMedicalGetTemplatePath('inmedical/department')) {
            include inMedicalGetTemplatePath('inmedical/department');
        } else {
            $imd_theme = INMEDICAL_THEME_PATH . 'department.php';
            if (file_exists($imd_theme)) {
                include $imd_theme;
            } else {
                echo esc_html__('No theme was found', 'inmedical');
            }
        }
    endwhile;
endif;
get_footer();
