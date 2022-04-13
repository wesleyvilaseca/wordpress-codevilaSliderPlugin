<?php
$meta = get_post_meta($post->ID);
?>

<table class="form-table wv-slider-metabox">
    <input type="hidden" name="wv_slider_nonce" value="<?php echo wp_create_nonce("wv_slider_nonce") ?>">
    <tr>
        <th>
            <label for="wv_slider_link_text">Link Text</label>
        </th>
        <td>
            <input type="text" name="wv_slider_link_text" id="wv_slider_link_text" class="regular-text link-text" value="<?php echo esc_html(@$meta['wv_slider_link_text'][0]) ?>" required>
        </td>
    </tr>
    <tr>
        <th>
            <label for="wv_slider_link_url">Link Url</label>
        </th>
        <td>
            <input type="url" name="wv_slider_link_url" id="wv_slider_link_url" class="regular-text link-url" value="<?php echo esc_url(@$meta['wv_slider_link_url'][0]) ?>" required>
        </td>
    </tr>
</table>