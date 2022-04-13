<?php

if (!class_exists('WV_Slider_Shortcode')) {
    class WV_Slider_Shortcode
    {
        public function __construct()
        {
            add_shortcode('wv_slider', [$this, 'add_shortcode']);
        }

        public function add_shortcode($atts = [], $content = null, $tag = '')
        {
            $atts = array_change_key_case((array) $atts, CASE_LOWER);
            extract(
                shortcode_atts(
                    [
                        'id' => '',
                        'orderby' => 'date',
                    ],
                    $atts,
                    $tag
                )
            );

            if (!empty($id))
                $id = array_map('absint', $explode(',', $id));


            ob_start();
            require(WV_SLIDER_PATH . 'views/wv-slider_shortcode.php');
            wp_enqueue_script('wv-slider-main-jq');
            // wp_enqueue_script('wv-slider-options-js');
            wp_enqueue_style('wv-slider-main-css');
            wp_enqueue_style('wv-slider-style-css');
            wv_slider_options();

            return ob_get_clean();
        }
    }
}
