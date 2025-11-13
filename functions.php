<?php
/**
 * Plantground Storefront Child Theme functions and definitions
 */

// Enqueue child theme styles and scripts
function plantground_child_enqueue_assets()
{
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

// === AJAX handler for product filtering + sort ===
add_action('wp_ajax_plantground_filter_products', 'plantground_filter_products');
add_action('wp_ajax_nopriv_plantground_filter_products', 'plantground_filter_products');

function plantground_filter_products()
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

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p>No products found.</p>';
    }

    wp_die();
}

// === Cart fragments ===
function plantground_cart_count_fragment($fragments)
{
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    ?>
    <span id="cart-count" class="cart-count <?php echo $count == 0 ? 'hidden' : ''; ?>">
        <?php echo esc_html($count); ?>
    </span>
    <?php
    $fragments['#cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_cart_count_fragment');

function plantground_custom_cart_fragment($fragments)
{
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    ?>
    <span id="cart-count" class="cart-count <?php echo $count == 0 ? 'hidden' : ''; ?>">
        <?php echo $count > 0 ? '(' . $count . ')' : ''; ?>
    </span>
    <?php
    $fragments['#cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_custom_cart_fragment');

/**
 * === Sort dropdown control ===
 * Default WooCommerce sort dropdown is visible again.
 */
add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 20);

/**
 * === Remove default WooCommerce products header (title) ===
 */
add_action('after_setup_theme', function() {
    remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
});


// Enqueue Choices.js for custom select styling
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'choices-css',
        'https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css',
        [],
        '10.2.0'
    );
    wp_enqueue_script(
        'choices-js',
        'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js',
        [],
        '10.2.0',
        true
    );
}, 20);


// Remove "Sort by popularity" from sort dropdown/
add_filter('woocommerce_catalog_orderby', function($sortby) {
    unset($sortby['popularity']); // removes "Sort by popularity"
    return $sortby;
});
