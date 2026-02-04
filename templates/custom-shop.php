<?php

/**
 * Template Name: custom shop
 */

get_header(); ?>

<div class="site-main hiring-portal-container archive post-type-archive-product">

    <header class="shop-header" style="padding: 80px 0; text-align: center;">
        <div class="custom-container" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
            <?php while (have_posts()) : the_post();
                the_content();
            endwhile; ?>
        </div>
    </header>

    <div class="mobile-filter__container">
        <button class="mobile-filter-toggle" id="mobile-filter-toggle">
            <span id="filter-text">Show Filters</span>
            <svg id="filter-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                <path d="M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z" />
            </svg>
        </button>
    </div>

    <div class="shop-controls" id="shop-controls">
        <div class="shop-controls__row shop-controls__container" id="filters-container">
            <div class="shop-controls__left">
                <div class="shop-controls__filters" id="plantground-filters">
                    <label class="toggle-switch">
                        <input type="checkbox" value="cactus" class="category-toggle" />
                        <span class="slider"></span>
                        <span class="toggle-label">Cactus</span>
                    </label>

                    <label class="toggle-switch">
                        <input type="checkbox" value="succulents" class="category-toggle" />
                        <span class="slider"></span>
                        <span class="toggle-label">Succulents</span>
                    </label>
                </div>
            </div>

            <div class="shop-controls__right">
                <?php
                plantground_render_bare_sort_select();
                plantground_render_bare_result_count();
                ?>
            </div>
        </div>
    </div>

    <div class="product-grid__container" data-force-cat="gift" data-exclude-cat="originals">
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => array(
                'relation' => 'AND',
                array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'gift'),
                array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'originals', 'operator' => 'NOT IN'),
            ),
        );
        $loop = new WP_Query($args);
        if ($loop->have_posts()) :
            echo '<ul class="products columns-3">';
            while ($loop->have_posts()) : $loop->the_post();
                wc_get_template_part('content', 'product');
            endwhile;
            echo '</ul>';
        else :
            echo '<p style="text-align:center; margin-top: 50px;">No gifts currently available.</p>';
        endif;
        wp_reset_postdata();
        ?>
    </div>
</div>

<?php get_footer(); ?>