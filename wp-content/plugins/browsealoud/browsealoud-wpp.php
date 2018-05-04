<?php

/*
Plugin Name: Browsealoud
Plugin URI:  https://www.texthelp.com
Description: Websites made more accessible with easy speech, reading and translation tools. This plugin takes care of the Browsealoud installation process for all Wordpress blogs and websites. If you hold a licence for Browsealoud simply activate this plugin and you will see that Browsealoud automatically appears. If you don't have a licence you might be interested in our <a href="https://trybrowsealoud.texthelp.com/">30 day free trial</a>.
Version:     1.5.2
Author:      Texthelp
Author URI:  https://www.texthelp.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

define('BROWSEALOUD_PLUGIN_VERSION', '2.5.1');
define('BROWSEALOUD_PLUGIN_URL', 'https://www.browsealoud.com/plus/scripts');
define('BROWSEALOUD_PLUGIN_INTEGRITY_STRING', 'sha256-qka8jxVlCAjRf8tBo9ZQei0yDsegmgvvXase+tCHpug= sha384-umEpIALnZPt9YfwcmokVqcRZu6YIRaIquftXfoowjAAa6cKrKpAMuUeA0wYJACWz sha512-GUQVId1GjjNF2EVUfdeH/ct8fRypFsjU0uXgYQnUVGkXqP7YWIaqJY2y+2RzzaEPG/pnO+nBE+69Ec7L6PKDjw==');

function browsealoudScript() {

    $script_str = sprintf('<script type="text/javascript" src="%s/%s/ba.js" integrity="%s" crossorigin="anonymous"></script>', BROWSEALOUD_PLUGIN_URL, BROWSEALOUD_PLUGIN_VERSION, BROWSEALOUD_PLUGIN_INTEGRITY_STRING);

    echo($script_str);
}

add_action('wp_footer', 'browsealoudScript');

