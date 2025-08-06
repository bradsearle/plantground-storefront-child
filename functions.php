<?php
/**
 * Plantground Storefront Child Theme functions and definitions
 */

// Enqueue child theme styles and scripts including Tailwind CSS
function plantground_child_enqueue_assets()
{
    $main_style_path = get_stylesheet_directory() . '/dist/main.css';
    $tailwind_style_path = get_stylesheet_directory() . '/dist/tailwind.css';
    $script_path = get_stylesheet_directory() . '/dist/main.js';

    $main_style_version = file_exists($main_style_path) ? filemtime($main_style_path) : wp_get_theme()->get('Version');
    $tailwind_style_version = file_exists($tailwind_style_path) ? filemtime($tailwind_style_path) : wp_get_theme()->get('Version');
    $script_version = file_exists($script_path) ? filemtime($script_path) : wp_get_theme()->get('Version');

    // Enqueue compiled main Sass CSS
    wp_enqueue_style(
        'plantground-child-style',
        get_stylesheet_directory_uri() . '/dist/main.css',
        array(),
        $main_style_version
    );

    // Enqueue compiled Tailwind CSS
    wp_enqueue_style(
        'plantground-tailwind-style',
        get_stylesheet_directory_uri() . '/dist/tailwind.css',
        array(),
        $tailwind_style_version
    );

    // Enqueue compiled main JS
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

// Optionally load plugin assets only on homepage
function my_enqueue_scripts()
{
    if (is_front_page()) {
        wp_enqueue_script('woof-js', plugin_dir_url(__FILE__) . 'path/to/woof.js', array('jquery'), null, true);
        wp_enqueue_style('woof-css', plugin_dir_url(__FILE__) . 'path/to/woof.css');
    }
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');

// Move "Add to cart" under the price on the single product page
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 11);

// AJAX handler for product filtering
add_action('wp_ajax_plantground_filter_products', 'plantground_filter_products');
add_action('wp_ajax_nopriv_plantground_filter_products', 'plantground_filter_products');

function plantground_filter_products()
{
    $categories = json_decode(stripslashes($_POST['categories'] ?? ''), true);

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
remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);


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


add_filter('woocommerce_add_to_cart_fragments', 'plantground_custom_cart_fragment');

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

add_action( 'init', function() {
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
  });
  

  function plantground_enqueue_preloader_script() {
  if ( is_front_page() ) {
    // GSAP CDN
    wp_enqueue_script(
      'gsap',
      'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
      array(),
      '3.12.5',
      true
    );

    // Preloader animation
    wp_enqueue_script(
      'plant-preloader',
      get_stylesheet_directory_uri() . '/js/preloader.js',
      array('gsap'),
      null,
      true
    );
  }
}
add_action('wp_enqueue_scripts', 'plantground_enqueue_preloader_script');
