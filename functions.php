<?php

/**
 * Plantground Storefront Child Theme functions and definitions
 */

// Enqueue child theme styles and scripts
function plantground_child_enqueue_assets()
{
    // ... (This section remains unchanged) ...
    $main_style_path = get_stylesheet_directory() . '/dist/main.css';
    $script_path     = get_stylesheet_directory() . '/dist/main.js';

    $main_style_version = file_exists($main_style_path) ? filemtime($main_style_path) : wp_get_theme()->get('Version');
    $script_version     = file_exists($script_path) ? filemtime($script_path) : wp_get_theme()->get('Version');

    // Enqueue compiled main Sass CSS
    wp_enqueue_style(
        'plantground-child-style',
        get_stylesheet_directory_uri() . '/dist/main.css',
        array(),
        $main_style_version
    );

    // Enqueue compiled main JS (includes preloader, filters, nav, etc.)
    wp_enqueue_script(
        'plantground-child-main-js',
        get_stylesheet_directory_uri() . '/dist/main.js',
        array('jquery'),
        $script_version,
        true
    );

    // Localize script for AJAX endpoint
    wp_localize_script('plantground-child-main-js', 'plantground_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'plantground_child_enqueue_assets');

// Remove Storefront default header and add custom one
function plantground_override_storefront_header()
{
    remove_all_actions('storefront_header');
    add_action('storefront_header', 'plantground_custom_header', 10);
}
add_action('after_setup_theme', 'plantground_override_storefront_header');


add_action('wp_enqueue_scripts', 'my_enqueue_scripts');

// Move "Add to cart" under the price on the single product page
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 11);


// ====================================================================================
// === AJAX handler for product filtering + sort: Keep only this handler and hooks! ===
// ====================================================================================
add_action('wp_ajax_plantground_filter_products', 'plantground_filter_products_handler');
add_action('wp_ajax_nopriv_plantground_filter_products', 'plantground_filter_products_handler');

function plantground_filter_products_handler()
{
    $categories = json_decode(stripslashes($_POST['categories'] ?? ''), true);
    $orderby    = sanitize_text_field($_POST['orderby'] ?? 'menu_order');

    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => array(),
    );

    // Handle categories
    if (!empty($categories)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $categories,
            'operator' => 'IN',
        );
    }

    // Handle WooCommerce sort options
    switch ($orderby) {
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            $args['orderby']  = 'meta_value_num';
            break;
        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'date':
            $args['orderby']  = 'date';
            $args['order']    = 'DESC';
            break;
        case 'price':
            $args['meta_key'] = '_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        case 'price-desc':
            $args['meta_key'] = '_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        default:
            $args['orderby'] = 'menu_order title';
            $args['order']   = 'ASC';
            break;
    }

    // Use $query instead of $product_query to match variable names below
    $query = new WP_Query($args);

    // Start output buffering to capture the product list HTML
    ob_start();
    if ($query->have_posts()) { // Use $query here
        while ($query->have_posts()) { // Use $query here
            $query->the_post(); // Use $query here
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p>No products found.</p>';
        // do_action('woocommerce_no_products_found'); // If you want to use the standard WC template
    }
    $products_html = ob_get_clean();

    // Start output buffering to capture the results count HTML
    ob_start();
    // Set loop properties based on the results of our custom query
    wc_set_loop_prop('total', $query->found_posts);
    wc_set_loop_prop('current_page', 1);
    wc_set_loop_prop('per_page', $query->get('posts_per_page'));

    woocommerce_result_count(); // Generate the HTML for "Showing X results"
    $result_count_html = ob_get_clean();

    // Prepare the JSON response
    $response = array(
        'productsHtml' => $products_html,
        'resultCountHtml' => $result_count_html
    );

    wp_send_json($response);
    wp_die();
}


// === Cart fragments ===
function plantground_cart_count_fragment($fragments)
{
    // ... (This section remains unchanged) ...
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_cart_count_fragment');

function plantground_custom_cart_fragment($fragments)
{
    // ... (This section remains unchanged) ...
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_custom_cart_fragment');

/**
 * === Sort dropdown control ===
 * Default WooCommerce sort dropdown is visible again.
 */
// NOTE: We probably removed this hook in the first advice session, 
// if so, this line should stay commented out/removed.
// add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 20); 


// ... (The rest of the file remains unchanged) ...
