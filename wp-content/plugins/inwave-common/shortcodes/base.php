<?php
/*
 * Inwave_Adv_Banner for Visual Composer
 */
if (!class_exists('Inwave_Shortcode')) {

    abstract class Inwave_Shortcode
    {
        static $shortcodes = array();
        static $loadfonts = array();
        protected $name = '';
        protected $params;

        function __construct()
        {
            add_action('init', array($this, 'register_vc'));
            add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
            add_shortcode($this->name, array($this, 'init_shortcode'));
        }

        function register_vc(){
            $this->params = $this->init_params();
            Inwave_Shortcode::$shortcodes[$this->name] = $this->params;
            if (function_exists('vc_map')) {
                vc_map($this->params);
            }
        }

        function register_scripts()
        {
        }

        abstract function init_params();

        abstract function init_shortcode($atts, $content = null);
    }
}

if (!class_exists('Inwave_Shortcode2')) {

    abstract class Inwave_Shortcode2 extends Inwave_Shortcode
    {
        protected $name2 = '';
        protected $params2;

        function __construct()
        {
            parent::__construct();
            add_action('init', array($this, 'register_vc2'));
            add_shortcode($this->name2, array($this, 'init_shortcode2'));
        }

        function register_vc2()
        {
            $this->params2 = $this->init_params2();
            Inwave_Shortcode::$shortcodes[$this->name2] = $this->params2;
            if (function_exists('vc_map')) {
                vc_map($this->params2);
            }
        }

        abstract function init_params2();

        abstract function init_shortcode2($atts, $content = null);
    }
}