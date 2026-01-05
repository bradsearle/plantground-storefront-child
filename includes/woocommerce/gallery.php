<?php
// Replace product gallery on single product pages
add_action('wp', 'plantground_replace_product_gallery');
function plantground_replace_product_gallery()
{
    if (is_product()) {
        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
        add_action('woocommerce_before_single_product_summary', 'plantground_custom_product_gallery', 20);
    }
}

function plantground_custom_product_gallery()
{
    global $product;
    $gallery_ids = $product->get_gallery_image_ids();

    if (empty($gallery_ids)) {
        woocommerce_show_product_images();
        return;
    }

    $display_width = 600;
    echo '<div class="woocommerce-product-gallery">';

    // Main image = first gallery image
    $main_id = $gallery_ids[0];
    echo wp_get_attachment_image($main_id, 'woocommerce_single', false, array(
        'class'  => 'wp-post-image',
        'sizes'  => '(max-width: ' . $display_width . 'px) 100vw, ' . $display_width . 'px',
        'srcset' => wp_get_attachment_image_srcset($main_id, 'woocommerce_single'),
        'alt'    => wp_get_attachment_caption($main_id) ?: $product->get_name(),
    ));

    // Thumbnails (skip first)
    if (count($gallery_ids) > 1) {
        echo '<div class="flex-control-thumbs">';
        for ($i = 1; $i < count($gallery_ids); $i++) {
            echo wp_get_attachment_image($gallery_ids[$i], 'shop_thumbnail', false, array('class' => 'thumb'));
        }
        echo '</div>';
    }

    echo '</div>';
}

// Add custom body class
add_filter('post_class', 'plantground_add_single_product_container_class', 10, 3);
function plantground_add_single_product_container_class($classes, $class, $post_id)
{
    if (is_product() && get_post_type($post_id) === 'product') {
        $classes[] = 'single-product__container';
    }
    return $classes;
}
