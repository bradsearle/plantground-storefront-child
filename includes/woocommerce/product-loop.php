<?php
// Wrap title + price in .product__info
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

// Custom product title with .product__title
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'plantground_custom_product_title', 10);

function plantground_custom_product_title()
{
    echo '<h2 class="woocommerce-loop-product__title product__title">' . esc_html(get_the_title()) . '</h2>';
}
