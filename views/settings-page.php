<div class="wrap">
    <h1>
        <?php echo esc_html(get_admin_page_title()) ?>
    </h1>

    <?php

    $active_tab = @$_GET['tab'] ? $_GET['tab'] : 'main_options';

    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=wv_slider_admin&tab=main_options" class="nav-tab <?php echo $active_tab == 'main_options' ? 'nav-tab-active' : '' ?>">Main options</a>
        <a href="?page=wv_slider_admin&tab=additional_options" class="nav-tab <?php echo $active_tab == 'additional_options' ? 'nav-tab-active' : '' ?>">Additional options</a>
    </h2>

    <form action="options.php" method="POST">
        <?php

        if ($active_tab == 'main_options') {
            settings_fields('wv_slider_group');
            do_settings_sections('wv_slider_page1');
        }

        if ($active_tab == 'additional_options') {
            settings_fields('wv_slider_group');
            do_settings_sections('wv_slider_page2');
        }

        submit_button('Save Settings');
        ?>
    </form>
</div>