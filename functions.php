<?php
/**
 * Plantground Storefront Child Theme functions and definitions
 */

// Enqueue child theme styles and scripts
function plantground_child_enqueue_assets() {
    $style_path = get_stylesheet_directory() . '/dist/style.css';
    $script_path = get_stylesheet_directory() . '/dist/main.js';

    $style_version = file_exists($style_path) ? filemtime($style_path) : wp_get_theme()->get('Version');
    $script_version = file_exists($script_path) ? filemtime($script_path) : wp_get_theme()->get('Version');

    wp_enqueue_style(
        'plantground-child-style',
        get_stylesheet_directory_uri() . '/dist/style.css',
        array(),
        $style_version
    );

    wp_enqueue_script(
        'plantground-child-main-js',
        get_stylesheet_directory_uri() . '/dist/main.js',
        array('jquery'),
        $script_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'plantground_child_enqueue_assets');



// Remove Storefront header and add custom header
function plantground_override_storefront_header() {
    remove_all_actions('storefront_header'); // Remove original Storefront header
    add_action('storefront_header', 'plantground_custom_header', 10); // Add your custom header
}
add_action('after_setup_theme', 'plantground_override_storefront_header');



function my_enqueue_scripts() {
    if ( is_front_page() ) {
        // Enqueue plugin CSS or JS here
        wp_enqueue_script('woof-js', plugin_dir_url(__FILE__) . 'path/to/woof.js', array('jquery'), null, true);
        wp_enqueue_style('woof-css', plugin_dir_url(__FILE__) . 'path/to/woof.css');
    }
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');



add_action('wp_footer', function() {
    echo '<div style="display:none">cache-test-'.time().'</div>';
});


// Move add to cart + quantity under the price on single product page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 11 );
