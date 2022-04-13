<?php

if (!class_exists('WV_Slider_Settings')) {
    class WV_Slider_Settings
    {
        public static $options;

        public function __construct()
        {
            self::$options = get_option('wv_slider_options');
            add_action('admin_init', [$this, 'admin_init']);
        }

        public function admin_init()
        {
            register_setting('wv_slider_group', 'wv_slider_options', [$this, 'wv_slider_validate']);

            add_settings_section(
                'wv_slider_main_section',
                'How does it work?',
                null,
                'wv_slider_page1'
            );

            add_settings_section(
                'wv_slider_second_section',
                'Other plugin options',
                null,
                'wv_slider_page2'
            );

            add_settings_field(
                'wv_slider_shortcode',
                'Short code',
                [$this, 'shortCodeCallBack'],
                'wv_slider_page1',
                'wv_slider_main_section',
                []
            );

            add_settings_field(
                'wv_slider_title',
                'Slider title',
                [$this, 'slideTitle'],
                'wv_slider_page2',
                'wv_slider_second_section',
                [
                    'label_for' => 'wv_slider_title'
                ]
            );

            add_settings_field(
                'wv_slider_bullets',
                'Diplay bullets',
                [$this, 'wv_slider_bullets'],
                'wv_slider_page2',
                'wv_slider_second_section',
                [
                    'label_for' => 'wv_slider_bullets'
                ]
            );

            add_settings_field(
                'wv_slider_style',
                'Diplay bullets',
                [$this, 'wv_slider_style'],
                'wv_slider_page2',
                'wv_slider_second_section',
                [
                    'items' => [
                        'style-1',
                        'style-2'
                    ],
                    'label_for' => 'wv_slider_style'
                ]
            );
        }

        public function shortCodeCallBack($args)
        { ?>
            <span>use the short code [wv_slider] to display the slider in any page/pos/widget</span>
        <?php
        }

        public function slideTitle($args)
        {
        ?>
            <input type="text" name="wv_slider_options[wv_slider_title]" id="wv_slider_title" value="<?php echo @self::$options['wv_slider_title'] ? esc_attr(self::$options['wv_slider_title']) : '' ?>">
        <?php
        }

        public function wv_slider_bullets($args)
        {
        ?>
            <input type="checkbox" name="wv_slider_options[wv_slider_bullets]" id="wv_slider_bullets" value="1" <?php checked("1", @self::$options['wv_slider_bullets'], true) ?>>
            <label for="mv_slider_bullets">Whether to display bullets or not</label>
        <?php
        }

        public function wv_slider_style($args)
        {
        ?>
            <select name="wv_slider_options[wv_slider_style]" id="wv_slider_style">
                <?php foreach ($args['items'] as $item) : ?>
                    <option value="<?php echo esc_attr($item) ?>" <?php @self::$options['wv_slider_style'] ? selected($item, self::$options['wv_slider_style'], true) : '' ?>> <?php echo esc_attr(ucfirst($item)) ?></option>
                <?php endforeach; ?>
            </select>
<?php
        }

        public function wv_slider_validate($input)
        {
            $new_input = [];

            foreach ($input as $key => $value) :
                switch ($key) {
                    case 'wv_slider_title':
                        if (empty($value))
                            $value = 'Please, type some text';

                        $new_input[$key] = sanitize_text_field($value);
                        break;

                    case 'wv_slider_url':
                        if (empty($value))
                            $value = 'Please, type some url';

                        $new_input[$key] = esc_url_raw($value);
                        break;

                    case 'wv_slider_int':
                        if (empty($value))
                            $value = 'Please, type some number';

                        $new_input[$key] = absint($value);
                        break;
                    default:
                        if (empty($value))
                            $value = 'Please, type some text';
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                }
            endforeach;

            return $new_input;
        }
    }
}
