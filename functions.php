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
// === AJAX handler for product filtering + sort
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

    ob_start();
    if ($query->have_posts()) {
        // WRAPPER FIX: Ensures your grid stays as a UL with WooCommerce classes
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
// === Custom sort and result count renderers
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

/**
 * Wrap only Title and Price in product__info
 */
add_action('woocommerce_before_shop_loop_item_title', 'opening_product_info_div', 15);
function opening_product_info_div()
{
    echo '<div class="product__info">';
}

add_action('woocommerce_after_shop_loop_item_title', 'closing_product_info_div', 15);
function closing_product_info_div()
{
    echo '</div>';
}

// Add custom class to product title in shop loops
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'plantground_custom_product_title', 10);

function plantground_custom_product_title()
{
    echo '<h2 class="woocommerce-loop-product__title product__title">' . esc_html(get_the_title()) . '</h2>';
}

/**
 * Add 'single-product__container' class to product pages
 */
add_filter('post_class', 'plantground_add_single_product_container_class', 10, 3);
function plantground_add_single_product_container_class($classes, $class, $post_id)
{
    if (is_product() && get_post_type($post_id) === 'product') {
        $classes[] = 'single-product__container';
    }
    return $classes;
}

// AJAX: Cart Count helper
add_action('wp_ajax_get_cart_count', 'return_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'return_cart_count');
function return_cart_count()
{
    wp_send_json(['count' => WC()->cart->get_cart_contents_count()]);
}

// Add custom class to product summary container
add_filter('woocommerce_product_summary_classes', 'plantground_add_custom_summary_class', 10, 1);
function plantground_add_custom_summary_class($classes)
{
    $classes[] = 'single-product__info';
    return $classes;
}

// ====================================================================================
// === SINGLE PRODUCT PAGE CUSTOMIZATIONS (Step-by-Step Hooks)
// ====================================================================================

add_action('wp', 'plantground_apply_single_product_changes');
function plantground_apply_single_product_changes()
{
    if (!is_product()) return;

    // 1. Mobile-only header (Title/Price at top)
    add_action('woocommerce_before_single_product_summary', 'plantground_mobile_header_html', 5);

    // 2. Custom Gallery (Replace default)
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    add_action('woocommerce_before_single_product_summary', 'plantground_custom_simple_gallery', 20);

    // 3. Remove Tabs, show description directly
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_single_product_summary', 'plantground_show_description_directly', 25);

    // 4. NEW: Filter the default title/price to allow mobile hiding
    add_filter('woocommerce_product_title_class', function ($class) {
        return $class . ' desktop-only-title';
    });
}

// Updated Mobile Header HTML with a helper class
function plantground_mobile_header_html()
{
    global $product;
    // We wrap this in a unique class so we can hide it on Desktop
    echo '<div class="pg-mobile-product-header">';
    echo '<h1 class="product_title">' . get_the_title() . '</h1>';
    echo '<p class="price">' . $product->get_price_html() . '</p>';
    echo '</div>';
}

// Custom Simple Gallery HTML
function plantground_custom_simple_gallery()
{
    global $product;
    $gallery_ids = $product->get_gallery_image_ids();
    if (empty($gallery_ids)) {
        $gallery_ids = [get_post_thumbnail_id()];
    }

    $images = [];
    foreach ($gallery_ids as $id) {
        $url = wp_get_attachment_image_url($id, 'full');
        $alt = get_post_meta($id, '_wp_attachment_image_alt', true) ?: $product->get_name();
        $images[] = ['url' => esc_url($url), 'alt' => esc_attr($alt)];
    }

    echo '<div class="plantground-product-gallery" data-gallery="' . esc_attr(json_encode($images)) . '">';
    foreach ($images as $index => $img) {
        printf('<img src="%s" alt="%s" class="plantground-gallery-image" data-index="%d">', $img['url'], $img['alt'], $index);
    }
    echo '</div>';
}

// Modal in Footer (Only for single product pages)
add_action('wp_footer', function () {
    if (!is_product()) return; ?>
    <div id="plantground-image-modal" class="plantground-modal" style="display:none;">
        <span class="plantground-modal-close">&times;</span>
        <button class="plantground-modal-nav plantground-modal-prev">&#8249;</button>
        <div class="plantground-modal-content">
            <img id="plantground-modal-image" src="" alt="">
        </div>
        <button class="plantground-modal-nav plantground-modal-next">&#8250;</button>
    </div>
<?php });

function plantground_show_description_directly()
{
    global $post;
    if ($post->post_content) {
        echo '<div class="product-long-description">' . apply_filters('the_content', $post->post_content) . '</div>';
    }
}




add_action('woocommerce_single_product_summary', 'pg_inject_custom_partial', 25);

function pg_inject_custom_partial()
{
    // This points to: wp-content/themes/your-theme/partials/product-accordion.php
    get_template_part('partials/product-accordion');
}
// 1. Re-hook Related Products (since tabs were removed)
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

// 2. Set Related Products to 4 columns / 4 total
add_filter('woocommerce_output_related_products_args', 'pg_related_products_args', 20);
function pg_related_products_args($args)
{
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}


// Remove stock status display (e.g., "15 in stock") from single product pages
add_filter('woocommerce_get_stock_html', '__return_empty_string', 10, 2);





// Display ACF Pet Safe field under Short Description (Priority 21)
add_action('woocommerce_single_product_summary', 'plantground_display_pet_safe_info', 21);

function plantground_display_pet_safe_info()
{
    global $product;

    if (! is_a($product, 'WC_Product')) {
        return;
    }

    // Fetch the field using the product ID
    $pet_safe_status = get_field('pet_safe', $product->get_id());

    if ($pet_safe_status) {
        echo '<div class="product-acf">';
        echo '  <span class="product-acf-copy">' . esc_html($pet_safe_status) . '</span>';
        echo '</div>';
    }
}
// Display ACF 
add_action('woocommerce_single_product_summary', 'plantground_display_product_shipping', 22);

function plantground_display_product_shipping()
{
    global $product;

    if (! is_a($product, 'WC_Product')) {
        return;
    }

    $zone = get_field('product_shipping', $product->get_id());

    if ($zone) {
        echo '<div class="product-acf">';
        echo '  <span class="product-acf-copy">' . esc_html($zone) . '</span>';
        echo '</div>';
    }
}

// Display ACF Hardiness Zone under Pet Safe info (Priority 22)
add_action('woocommerce_single_product_summary', 'plantground_display_hardiness_zone', 22);

function plantground_display_hardiness_zone()
{
    global $product;

    if (! is_a($product, 'WC_Product')) {
        return;
    }

    $zone = get_field('hardiness_zone', $product->get_id());

    if ($zone) {
        echo '<div class="product-acf">';
        echo '  <span class="product-acf-copy">' . esc_html($zone) . '</span>';
        echo '</div>';
    }
}
