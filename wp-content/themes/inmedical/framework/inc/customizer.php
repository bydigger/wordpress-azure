<?php
/**
 * Created by PhpStorm.
 * User: HUNGTX
 * Date: 4/1/2015
 * Time: 4:44 PM
 */

if(!function_exists('WP_Filesystem')){
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}
if(!defined('INWAVE_MAIN_COLOR')){
    define('INWAVE_MAIN_COLOR', '#39c5de');
}
class Inwave_Customizer
{
    function __construct()
    {
        global $inwave_theme_option;

        /** Dynamic custom css*/

        add_action('inwave_of_save_options_after', array($this, 'store_color_file'));
        add_action('save_post', array($this, 'save_post'));
        add_action('wp_enqueue_scripts', array($this, 'checkCreatedCustomCSS'), 9);

        if ( !is_admin() ) {
            /* Append panel setting to footer*/
            if($inwave_theme_option['show_setting_panel']) {
                add_action('wp_footer', array($this, 'append_options'));
            }
        }
    }

    function checkCreatedCustomCSS(){
        WP_Filesystem();
        global $inwave_theme_option, $wp_filesystem;
        if($inwave_theme_option['develop_mode'] || !$wp_filesystem->exists(self::getColorFilePath())){
            $this->store_color_file();
        }
    }

    public static function getColorFolderPath(){
        $uploads = wp_upload_dir();
        return $uploads['basedir'] . '/'.get_template();
    }

    public static function getColorFilePath(){
        $mainColor = Inwave_Helper::getPanelSetting('mainColor');
        return self::getColorFolderPath().'/color-'.str_replace('#','', $mainColor).'.css';
    }

    public static function getColorFileUrl(){
        $directory_url = get_template_directory_uri();
        $uploads_url = explode( 'themes/', $directory_url );
        $mainColor = Inwave_Helper::getPanelSetting('mainColor');
        if ( count( $uploads_url ) > 0 ) {
            $uploads_url = $uploads_url[0] . 'uploads/' . $uploads_url[1];
        }
        return $uploads_url.'/color-'.str_replace('#','', $mainColor).'.css';
    }

    /* Append panel setting to footer*/
    function append_options()
    {
        include_once(get_template_directory() . '/blocks/panel-settings.php');
    }

    function save_post($post_id ){
        if(is_admin() && get_post_type($post_id) == 'page'){
            $this->store_color_file();
        }
    }

    /** return/echo css custom and css configuration */
    static function store_color_file()
    {
        WP_Filesystem();

        global $wp_filesystem;

        $custom_file_path = self::getColorFilePath();
        $custom_folder_path = self::getColorFolderPath();

        // Color & background for content
        $colorText = trim($wp_filesystem->get_contents(get_template_directory() . '/assets/css/color.css'));
        $mainColor = Inwave_Helper::getPanelSetting('mainColor');
        if ($mainColor) {
            $colorText = str_replace(INWAVE_MAIN_COLOR, $mainColor, $colorText);
        }

        if ( ! $wp_filesystem->is_dir($custom_folder_path ) ) {
            if ( ! wp_mkdir_p($custom_folder_path ) ) {
                return false;
            }
        }

        $wp_filesystem->put_contents($custom_file_path, $colorText);
    }
}
new Inwave_Customizer();