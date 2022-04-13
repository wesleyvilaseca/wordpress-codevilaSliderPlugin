<?php
//registro de posts customisados
if (!class_exists('WV_Slider_Post_Type')) {
    class WV_Slider_Post_Type
    {
        function __construct()
        {
            add_action('init', [$this, 'create_post_type']);
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_post'], 10, 2);
            add_filter('manage_wv-slider_posts_columns', [$this, 'mv_slider_cpt_columns']);
            add_action('manage_wv-slider_posts_custom_column', [$this, 'wv_slider_custom_column'], 10, 2);
            add_filter('manage_edit-wv-slider_sortable_columns', [$this, 'wv_slider_sortable_coluns']);
        }

        public function create_post_type(): void
        {
            register_post_type(
                'wv-slider',
                [
                    'label'         => 'Slider',
                    'description'   => 'Sliders',
                    'labels'        => [
                        'name'          => 'Sliders',
                        'singular_name' => 'Slider'
                    ],
                    'public'            => true,
                    //page-attributes serve para adicionar hierarquia dos posts  
                    'supports'              => ['title', 'editor', 'thumbnail', /*'page-attributes'*/],
                    'hierarchical'          => false,
                    'show_ui'               => true,
                    'show_in_menu'          => false,
                    'menu_position'         => 5,
                    'show_in_admin_bar'     => true,
                    'show_in_nav_menus'     => true,
                    'can_export'            => true,
                    'has_archive'           => false,
                    'exclude_from_search'   => false,
                    'publicly_queryable'    => true,
                    'show_in_rest'          => true,
                    'menu_icon'             => 'dashicons-images-alt2'
                ]
            );
        }

        public function add_meta_boxes(): void
        {

            //meta boxes dos postos do plugin
            add_meta_box(
                'wv_slider_meta_box',
                'Link Options',
                [$this, 'add_inner_meta_boxes'],
                'wv-slider',
                'normal',
                'high'
            );
        }

        //quais posts vão receber o metabox
        public function add_inner_meta_boxes($post)
        {
            require_once(WV_SLIDER_PATH . 'views/wv-slider_metabox.php');
        }

        public function save_post($post_id)
        {
            if (!isset($_POST['wv_slider_nonce']))
                return;

            if (!wp_verify_nonce($_POST['wv_slider_nonce'], 'wv_slider_nonce'))
                return;

            if (defined('DOING_AUTOSAVE') and DOING_AUTOSAVE)
                return;

            if (isset($_POST['post_type']) and $_POST['post_type'] === 'wv-slider') {
                if (!current_user_can('edit_page', $post_id))
                    return;

                if (!current_user_can('edit_post', $post_id))
                    return;
            }

            if (isset($_POST['action']) and $_POST['action'] == 'editpost') {
                $old_link_text = get_post_meta($post_id, 'wv_slider_link_text', true);
                $new_link_text = $_POST['wv_slider_link_text'];
                $old_link_url = get_post_meta($post_id, 'wv_slider_link_url', true);
                $new_link_url = $_POST['wv_slider_link_url'];

                if (empty($new_link_text))
                    update_post_meta($post_id, 'wv_slider_link_text', 'add some text');
                else
                    update_post_meta($post_id, 'wv_slider_link_text', sanitize_text_field($new_link_text), $old_link_text);


                if (empty($new_link_url))
                    update_post_meta($post_id, 'wv_slider_link_url', '#');
                else
                    update_post_meta($post_id, 'wv_slider_link_url', esc_url_raw($new_link_url), $old_link_url);
            }
        }

        //aqui são as novas colunas na listagem dos slides
        public function mv_slider_cpt_columns($columns)
        {
            //id é a chave do campo de metadados
            $columns['wv_slider_link_text'] = esc_html__('Link text', 'wv-slider');
            $columns['wv_slider_link_url'] = esc_html__('Link url', 'wv-slider');

            return $columns;
        }

        //aqui é o conteudo das novas colunas adicionadas
        public function wv_slider_custom_column($column, $post_id)
        {
            switch ($column) {
                case 'wv_slider_link_text':
                    echo esc_html(get_post_meta($post_id, 'wv_slider_link_text', true));
                    break;

                case 'wv_slider_link_url':
                    echo esc_url(get_post_meta($post_id, 'wv_slider_link_url', true));
                    break;
            }
        }

        //como não foi definido o nº de parâmetros na chamada do filtro, ele recebe 1 param
        public function wv_slider_sortable_coluns($columns)
        {
            $columns['wv_slider_link_text'] = 'wv_slider_link_text';
            return $columns;
        }
    }
}
