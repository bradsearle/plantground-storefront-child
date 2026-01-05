<?php
function plantground_child_enqueue_assets()
{
    // Fix jQuery caching
    wp_deregister_script('jquery');
    wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), array(), null, true);

    // Paths
    $main_style_path = get_stylesheet_directory() . '/dist/main.css';
    $script_path     = get_stylesheet_directory() . '/dist/main.js';

    $main_style_version = file_exists($main_style_path) ? filemtime($main_style_path) : wp_get_theme()->get('Version');
    $script_version     = file_exists($script_path) ? filemtime($script_path) : wp_get_theme()->get('Version');

    // Enqueue CSS
    wp_enqueue_style(
        'plantground-child-style',
        get_stylesheet_directory_uri() . '/dist/main.css',
        array(),
        $main_style_version
    );

    // Enqueue JS
    wp_enqueue_script(
        'plantground-child-main-js',
        get_stylesheet_directory_uri() . '/dist/main.js',
        array('jquery'),
        $script_version,
        true
    );

    // Localize AJAX
    wp_localize_script('plantground-child-main-js', 'plantground_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'plantground_child_enqueue_assets');
