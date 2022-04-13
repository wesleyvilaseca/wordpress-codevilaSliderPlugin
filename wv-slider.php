<?php

/**
 * Plugin Name:       wv-slider
 * Plugin URI:        https://codevila.com.br/wv-slider
 * Description:       Slider Plugin WordPress
 * Version:           1.0
 * Author:            Wesley Vila Seca 
 * Requires at least: 5.8
 * Author URI:        https://github.com/wesleyvilaseca
 * Text Domain:       wv-slider
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: 
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WV_Slider')) {
    class WV_Slider
    {
        function __construct()
        {
            self::define_constants();

            //menu "especial"
            add_action('admin_menu', [$this, 'add_menu']);

            require_once(WV_SLIDER_PATH . 'post-types/class.wv-slider-cpt.php');
            require_once(WV_SLIDER_PATH . 'class.wv-slider-settings.php');
            require_once(WV_SLIDER_PATH . 'shortcodes/class.wv-slider-shortcode.php');
            require_once(WV_SLIDER_PATH . 'functions/functions.php');

            $WV_Slider_Post_Type = new WV_Slider_Post_Type();
            $WV_Slider_Settings = new WV_Slider_Settings();
            $WV_Slider_Shortcode = new WV_Slider_Shortcode();

            add_action('wp_enqueue_scripts', [$this, 'register_scripts'], 999);
            add_action('admin_enqueue_scripts', [$this, 'register_admin_scripts']);
        }

        public static function define_constants()
        {
            define('WV_SLIDER_PATH', plugin_dir_path(__FILE__));
            define('WV_SLIDER_URL', plugin_dir_url(__FILE__));
            define('WV_SLIDER_VERSION', '1.0.0');
        }

        public static function activate(): void
        {
            update_option('rewrite_rules', '');
        }

        public static function deactivate(): void
        {
            flush_rewrite_rules();

            //unregister the plugin when desativated
            unregister_post_type('wv-slider');
        }

        public static function uninstall()
        {
        }

        public function add_menu()
        {
            // add_theme_page(
            // add_plugins_page(
            add_menu_page(
                'WV Slider Options',
                'WV Slider',
                'manage_options',
                'wv_slider_admin',
                [$this, 'wv_slider_settings_page'],
                'dashicons-images-alt2',
                // 10
            );

            add_submenu_page(
                'wv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=wv-slider',
                null,
                null
            );

            add_submenu_page(
                'wv_slider_admin',
                'Add new Slide',
                'Add new Slide',
                'manage_options',
                'post-new.php?post_type=wv-slider',
                null,
                null
            );
        }

        public function wv_slider_settings_page()
        {
            if (!current_user_can('manage_options'))
                return;

            if (@$_GET['settings-updated']) {
                add_settings_error('wv_slider_options', 'wv_slider_message', 'Settings saved', 'success');
            }

            settings_errors('wv_slider_options');

            require(WV_SLIDER_PATH . "views/settings-page.php");
        }

        public function register_scripts()
        {
            wp_register_script('wv-slider-main-jq', WV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js', ['jquery'], WV_SLIDER_VERSION, true);
            wp_register_script('wv-slider-options-js', WV_SLIDER_URL . 'vendor/flexslider/flexslider.js', ['jquery'], WV_SLIDER_VERSION, true);

            wp_register_style('wv-slider-main-css', WV_SLIDER_URL . 'vendor/flexslider/flexslider.css', [], WV_SLIDER_VERSION, 'all');
            wp_register_style('wv-slider-style-css', WV_SLIDER_URL . 'assets/css/frontend.css', [], WV_SLIDER_VERSION, 'all');
        }

        public function register_admin_scripts()
        {
            global $typenow;
            if ($typenow == 'wv-slider')
                wp_enqueue_style('wv-slider-admin', WV_SLIDER_URL . 'assets/css/admin.css');
        }
    }
}

if (class_exists('WV_Slider')) {
    register_activation_hook(__FILE__,      ['WV_Slider', 'activate']);
    register_deactivation_hook(__FILE__,    ['WV_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__,       ['WV_Slider', 'uninstall']);

    $wv_slider = new WV_Slider();
}
