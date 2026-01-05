<?php
// AJAX handler for product filtering + sort
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

    if (! empty($categories)) {
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
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
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

    wp_send_json(array(
        'productsHtml'    => $products_html,
        'resultCountHtml' => $result_count_html
    ));
    wp_die();
}
