<?php

/**
 * Template Name: custom shop
 */



get_header(); ?>

<div class="hiring-portal-container archive post-type-archive-product">

    <header class="shop-header" style="padding: 60px 0; text-align: center; background: #fff;">
        <div class="custom-welcome-message" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
            <?php
            while (have_posts()) : the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </header>

    <div class="shop-controls">
        <div class="mobile-filter__container">
            <div class="flex-row">
                <a href="#" id="mobile-filter-toggle" class="mobile-filter-toggle">
                    <span id="filter-text">Show Filters</span>
                </a>
            </div>
        </div>

        <div id="filters-container" class="shop-controls__row shop-controls__container">
            <div class="shop-controls__left">
                <div id="plantground-filters">

                    <label class="toggle-switch">
                        <input type="checkbox" class="category-toggle" value="cactus">
                        <span class="slider"></span>
                        <span class="toggle-label">Cactus</span>
                    </label>

                    <label class="toggle-switch">
                        <input type="checkbox" class="category-toggle" value="succulents">
                        <span class="slider"></span>
                        <span class="toggle-label">Succulents</span>
                    </label>

                </div>
            </div>

            <div class="shop-controls__right">
                <?php
                plantground_render_bare_result_count();
                plantground_render_bare_sort_select();
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
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'gift',
                ),
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'originals',
                    'operator' => 'NOT IN',
                ),
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
            echo '<p style="text-align:center;">No gifts currently available.</p>';
        endif;
        wp_reset_postdata();
        ?>
    </div>
</div>

<?php get_footer(); ?>