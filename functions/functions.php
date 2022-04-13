<?php

function wv_slider_options()
{
    $show_bulltes = @WV_Slider_Settings::$options['wv_slider_bullets'] and WV_Slider_Settings::$options['wv_slider_bullets'] == 1 ? true : false;
    wp_enqueue_script('wv-slider-options-js', WV_SLIDER_URL . 'vendor/flexslider/flexslider.js', ['jquery'], WV_SLIDER_VERSION, true);
    wp_localize_script('wv-slider-options-js', 'SLIDER_OPTIONS', ['controlNav' => $show_bulltes]);
}
