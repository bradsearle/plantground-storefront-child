<?php

/**
 * Custom template tags for Plantground
 */

function plantground_render_bare_sort_select()
{
    $orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) :
        apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

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

// Add this if you haven't defined it elsewhere
if (! function_exists('plantground_custom_header')) {
    function plantground_custom_header()
    {
        // Your custom header markup here
        // Or leave empty if handled by JS/theme
    }
}
