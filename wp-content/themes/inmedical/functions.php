<?php
/**
 * inevent functions and definitions
 *
 * @package inmedical
 */

/* Theme option famework */
require_once get_template_directory().'/framework/theme-option.php';

require_once get_template_directory() . '/framework/option-framework/inof.php';

/* Require helper function */
require_once get_template_directory() . '/framework/inc/helper.php';

/* Require importer function */
require_once get_template_directory() . '/framework/importer/importer.php';

/* Custom nav */
require_once get_template_directory().'/framework/inc/custom-nav.php';

/* Customizer theme */
require_once get_template_directory() . '/framework/inc/customizer.php';

/* Template tags */
require_once get_template_directory() . '/framework/inc/template-tags.php';

/* Require Custom functions that act independently of the theme templates */
require_once get_template_directory() . '/framework/inc/extras.php';

/* Require custom widgets */
require_once get_template_directory() . '/framework/widgets/info-footer.php';
require_once get_template_directory() . '/framework/widgets/subscribe.php';
require_once get_template_directory() . '/framework/widgets/recent-comment.php';
require_once get_template_directory() . '/framework/widgets/recent-post.php';

/* Implement the woocommerce template. */
require_once get_template_directory() . '/framework/inc/woocommerce.php';

/* TGM plugin activation. */
require_once get_template_directory() . '/framework/inc/class-tgm-plugin-activation.php';

//framework
require_once get_template_directory().'/framework/theme-plugin-load.php';

require_once get_template_directory().'/framework/theme-function.php';

require_once get_template_directory().'/framework/theme-register.php';

require_once get_template_directory().'/framework/theme-support.php';

require_once get_template_directory().'/framework/theme-style-script.php';

require_once get_template_directory() . '/framework/theme-metabox.php';
