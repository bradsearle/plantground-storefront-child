<?php

/**
 * Plantground Storefront Child Theme functions and definitions
 */

// Enqueue child theme styles and scripts
function plantground_child_enqueue_assets()
{
    wp_deregister_script('jquery');
    wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), array(), null, true);

    $main_style_path = get_stylesheet_directory() . '/dist/main.css';
    $script_path     = get_stylesheet_directory() . '/dist/main.js';

    $main_style_version = file_exists($main_style_path) ? filemtime($main_style_path) : wp_get_theme()->get('Version');
    $script_version     = file_exists($script_path) ? filemtime($script_path) : wp_get_theme()->get('Version');

    wp_enqueue_style('plantground-child-style', get_stylesheet_directory_uri() . '/dist/main.css', array(), $main_style_version);
    wp_enqueue_script('plantground-child-main-js', get_stylesheet_directory_uri() . '/dist/main.js', array('jquery'), $script_version, true);

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

// Move "Add to cart" under the price
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 11);

// ====================================================================================
// === AJAX handler for product filtering + sort
// ====================================================================================
add_action('wp_ajax_plantground_filter_products', 'plantground_filter_products_handler');
add_action('wp_ajax_nopriv_plantground_filter_products', 'plantground_filter_products_handler');

function plantground_filter_products_handler()
{
    $categories  = json_decode(stripslashes($_POST['categories'] ?? ''), true);
    $orderby     = sanitize_text_field($_POST['orderby'] ?? 'menu_order');
    $force_cat   = sanitize_text_field($_POST['force_category'] ?? '');
    $exclude_cat = sanitize_text_field($_POST['exclude_category'] ?? '');

    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => array('relation' => 'AND'),
    );

    // 1. Force a specific category (e.g. 'gift')
    if (!empty($force_cat)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $force_cat,
        );
    }

    // 2. Exclude a specific category (e.g. 'originals')
    if (!empty($exclude_cat)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $exclude_cat,
            'operator' => 'NOT IN',
        );
    }

    // 3. User Filter Toggles (Cactus/Succulent)
    if (!empty($categories)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $categories,
            'operator' => 'IN',
        );
    }

    switch ($orderby) {
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            break;
        case 'date':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'price':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        case 'price-desc':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'menu_order title';
            $args['order'] = 'ASC';
            break;
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        echo '<ul class="products columns-3">';
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        echo '</ul>';
    } else {
        echo '<p>No products found.</p>';
    }
    $products_html = ob_get_clean();

    ob_start();
    wc_set_loop_prop('total', $query->found_posts);
    woocommerce_result_count();
    $result_count_html = ob_get_clean();

    wp_send_json(array('productsHtml' => $products_html, 'resultCountHtml' => $result_count_html));
    wp_die();
}

// === Sort/Result Count Renderers ===
function plantground_render_bare_sort_select()
{
    $orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));
    $options = apply_filters('woocommerce_catalog_orderby', array(
        'menu_order' => __('Default sorting', 'woocommerce'),
        'date'       => __('Sort by latest', 'woocommerce'),
        'price'      => __('Sort by price: low to high', 'woocommerce'),
        'price-desc' => __('Sort by price: high to low', 'woocommerce'),
    ));
?>
    <div class="shop-controls__sort woocommerce-ordering">
        <select name="orderby" class="orderby">
            <?php foreach ($options as $id => $name) : ?>
                <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php
}

function plantground_render_bare_result_count()
{
    echo '<div class="woocommerce-result-count">';
    woocommerce_result_count();
    echo '</div>';
}

// === Cart Fragments ===
add_filter('woocommerce_add_to_cart_fragments', 'plantground_custom_cart_fragment');
function plantground_custom_cart_fragment($fragments)
{
    ob_start(); ?>
    <div class="header__cart-count-container">
        <svg class="header__cart-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6ZM6 4H18L19.82 5H4.18L6 4ZM5 20V7H19V20H5ZM12 9C11.4696 9 10.9609 9.21071 10.5858 9.58579C10.2107 9.96086 10 10.4696 10 11V14C10 14.5304 10.2107 15.0391 10.5858 15.4142C10.9609 15.7893 11.4696 16 12 16C12.5304 16 13.0391 15.7893 13.4142 15.4142C13.7893 15.0391 14 14.5304 14 14V11C14 10.4696 13.7893 9.96086 13.4142 9.58579C13.0391 9.21071 12.5304 9 12 9Z" fill="currentColor" />
        </svg>
        <span class="header__cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    </div>
<?php $fragments['.header__cart-count-container'] = ob_get_clean();
    return $fragments;
}

// === Product Layout Hooks ===
add_action('woocommerce_before_shop_loop_item_title', function () {
    echo '<div class="product__info">';
}, 15);
add_action('woocommerce_after_shop_loop_item_title', function () {
    echo '</div>';
}, 15);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', function () {
    echo '<h2 class="woocommerce-loop-product__title product__title">' . esc_html(get_the_title()) . '</h2>';
}, 10);

// === Single Product changes ===
add_action('wp', 'plantground_apply_single_product_changes');
function plantground_apply_single_product_changes()
{
    if (!is_product()) return;
    add_action('woocommerce_before_single_product_summary', 'plantground_mobile_header_html', 5);
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    add_action('woocommerce_before_single_product_summary', 'plantground_custom_simple_gallery', 20);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_single_product_summary', function () {
        global $post;
        if ($post->post_content) echo '<div class="product-long-description">' . apply_filters('the_content', $post->post_content) . '</div>';
    }, 25);
}

function plantground_mobile_header_html()
{
    global $product;
    echo '<div class="pg-mobile-product-header"><h1 class="product_title">' . get_the_title() . '</h1><p class="price">' . $product->get_price_html() . '</p></div>';
}

function plantground_custom_simple_gallery()
{
    global $product;
    $ids = $product->get_gallery_image_ids() ?: [get_post_thumbnail_id()];
    echo '<div class="plantground-product-gallery">';
    foreach ($ids as $index => $id) {
        printf('<img src="%s" alt="%s" class="plantground-gallery-image" data-index="%d">', esc_url(wp_get_attachment_image_url($id, 'full')), 'plant', $index);
    }
    echo '</div>';
}

add_action('woocommerce_single_product_summary', function () {
    get_template_part('partials/product-accordion');
}, 25);
add_filter('woocommerce_get_stock_html', '__return_empty_string');

// ACF Display Helper
function pg_display_acf($field_name, $priority)
{
    add_action('woocommerce_single_product_summary', function () use ($field_name) {
        $val = get_field($field_name, get_the_ID());
        if ($val) echo '<div class="product-acf"><span class="product-acf-copy">' . esc_html($val) . '</span></div>';
    }, $priority);
}
pg_display_acf('pet_safe', 21);
pg_display_acf('product_shipping', 22);
pg_display_acf('hardiness_zone', 23);
