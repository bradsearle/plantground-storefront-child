<?php
defined('ABSPATH') || exit;

// Just load setup (which only removes Storefront header)
require_once get_stylesheet_directory() . '/includes/setup.php';

// And define the missing header function
if (! function_exists('plantground_custom_header')) {
    function plantground_custom_header()
    {
        // empty
    }


    // Asset loading (CSS/JS)
    require_once get_stylesheet_directory() . '/includes/assets.php';

    // Template helper functions
    require_once get_stylesheet_directory() . '/template-tags.php';

    // WooCommerce components
    if (class_exists('WooCommerce')) {
        require_once get_stylesheet_directory() . '/includes/woocommerce/hooks.php';
        require_once get_stylesheet_directory() . '/includes/woocommerce/gallery.php';
        require_once get_stylesheet_directory() . '/includes/woocommerce/product-loop.php';
        require_once get_stylesheet_directory() . '/includes/woocommerce/cart.php';

        // AJAX handlers
        require_once get_stylesheet_directory() . '/includes/ajax/filters.php';
    }
}
