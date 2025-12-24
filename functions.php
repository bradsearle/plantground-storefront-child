<?php

/**
 * Plantground Storefront Child Theme functions and definitions
 */

// Enqueue child theme styles and scripts
function plantground_child_enqueue_assets()
{
    // --- FIX: Deregister WordPress’s old cached jQuery so dependent scripts never get stuck in mobile cache ---
    wp_deregister_script('jquery');
    wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), array(), null, true);

    // Paths to compiled assets
    $main_style_path = get_stylesheet_directory() . '/dist/main.css';
    $script_path     = get_stylesheet_directory() . '/dist/main.js';

    // Version numbers based on file modification time (mobile cache busting)
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

    // Start output buffering to capture the product list HTML
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p>No products found.</p>';
    }
    $products_html = ob_get_clean();

    // Start output buffering to capture the results count HTML
    ob_start();
    wc_set_loop_prop('total', $query->found_posts);
    wc_set_loop_prop('current_page', 1);
    wc_set_loop_prop('per_page', $query->get('posts_per_page'));
    woocommerce_result_count();
    $result_count_html = ob_get_clean();

    $response = array(
        'productsHtml'     => $products_html,
        'resultCountHtml'  => $result_count_html
    );

    wp_send_json($response);
    wp_die();
}

// ====================================================================================
// === FINAL STRUCTURAL FIX: Custom Render Sort and Result Count (No <form>) ===
// ====================================================================================

function plantground_render_bare_sort_select()
{
    $orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) :
        apply_filters(
            'woocommerce_default_catalog_orderby',
            get_option('woocommerce_default_catalog_orderby')
        );

    $catalog_orderby_options = apply_filters('woocommerce_catalog_orderby', array(
        'menu_order' => __('Default sorting', 'woocommerce'),

        'date'       => __('Sort by latest', 'woocommerce'),
        'price'      => __('Sort by price: low to high', 'woocommerce'),
        'price-desc' => __('Sort by price: high to low', 'woocommerce'),
    ));
?>
    <div class="shop-controls__sort woocommerce-ordering">
        <select name="orderby" class="orderby" aria-label="<?php esc_attr_e('Shop order', 'woocommerce'); ?>">
            <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>>
                    <?php echo esc_html($name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
<?php
}

function plantground_render_bare_result_count()
{
?>
    <div class="woocommerce-result-count">
        <?php woocommerce_result_count(); ?>
    </div>
<?php
}

// === Cart fragments ===
function plantground_cart_count_fragment($fragments)
{
    $fragments['.header__cart-count'] =
        '<span class="header__cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_cart_count_fragment');

function plantground_custom_cart_fragment($fragments)
{
    ob_start();
?>
    <div class="header__cart-count-container">
        <svg class="header__cart-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6ZM6 4H18L19.82 5H4.18L6 4ZM5 20V7H19V20H5ZM12 9C11.4696 9 10.9609 9.21071 10.5858 9.58579C10.2107 9.96086 10 10.4696 10 11V14C10 14.5304 10.2107 15.0391 10.5858 15.4142C10.9609 15.7893 11.4696 16 12 16C12.5304 16 13.0391 15.7893 13.4142 15.4142C13.7893 15.0391 14 14.5304 14 14V11C14 10.4696 13.7893 9.96086 13.4142 9.58579C13.0391 9.21071 12.5304 9 12 9Z" fill="currentColor" />
        </svg>
        <span class="header__cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    </div>
<?php
    $fragments['.header__cart-count-container'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_custom_cart_fragment');
