<?php
/**
 * Añadir página de opciones al menú de administración
 */
function tmdbtheme_add_theme_menu() {
    add_menu_page(
        'TMDBTheme Options',
        'TMDBTheme Options',
        'manage_options',
        'tmdbtheme-options',
        'tmdbtheme_render_options_page',
        'dashicons-admin-generic',
        61
    );
}
add_action('admin_menu', 'tmdbtheme_add_theme_menu');

/**
 * Renderizar página de opciones
 */
function tmdbtheme_render_options_page() {
    ?>
    <div class="wrap">
        <h1>TMDBTheme Options</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tmdbtheme_settings_group');
            do_settings_sections('tmdbtheme-options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Registrar ajustes y campos
 */
function tmdbtheme_register_settings() {
    register_setting('tmdbtheme_settings_group', 'tmdbtheme_header_logo');
    register_setting('tmdbtheme_settings_group', 'tmdbtheme_footer_logo');

    add_settings_section('tmdbtheme_section', 'Logos', null, 'tmdbtheme-options');

    // Campo para Header Logo
    add_settings_field(
        'tmdbtheme_header_logo',
        'Header Logo',
        'tmdbtheme_logo_field_html',
        'tmdbtheme-options',
        'tmdbtheme_section',
        ['option_name' => 'tmdbtheme_header_logo']
    );

    // Campo para Footer Logo
    add_settings_field(
        'tmdbtheme_footer_logo',
        'Footer Logo',
        'tmdbtheme_logo_field_html',
        'tmdbtheme-options',
        'tmdbtheme_section',
        ['option_name' => 'tmdbtheme_footer_logo']
    );
}
add_action('admin_init', 'tmdbtheme_register_settings');

/**
 * HTML del campo de carga de logo
 */
function tmdbtheme_logo_field_html($args) {
    $option = esc_url(get_option($args['option_name']));
    ?>
    <input type="text" name="<?php echo esc_attr($args['option_name']); ?>" id="<?php echo esc_attr($args['option_name']); ?>" value="<?php echo $option; ?>" style="width: 60%;" />
    <button class="button tmdbtheme-upload-button" data-target="<?php echo esc_attr($args['option_name']); ?>">Upload</button>
    <?php if ($option): ?>
        <div style="margin-top:10px;"><img src="<?php echo $option; ?>" style="max-height: 100px;" /></div>
    <?php endif;
}

/**
 * Encolar JS personalizado para el uploader
 */
function tmdbtheme_enqueue_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_tmdbtheme-options') return;

    wp_enqueue_media();
    wp_add_inline_script('jquery-core', <<<JS
        jQuery(document).ready(function ($) {
            $('.tmdbtheme-upload-button').on('click', function (e) {
                e.preventDefault();

                const button = $(this);
                const targetInput = $('#' + button.data('target'));

                const customUploader = wp.media({
                    title: 'Select or Upload Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });

                customUploader.on('select', function () {
                    const attachment = customUploader.state().get('selection').first().toJSON();
                    targetInput.val(attachment.url);
                    targetInput.next('div').html('<img src="' + attachment.url + '" style="max-height:100px;" />');
                });

                customUploader.open();
            });
        });
    JS);
}
add_action('admin_enqueue_scripts', 'tmdbtheme_enqueue_admin_scripts');
?>
