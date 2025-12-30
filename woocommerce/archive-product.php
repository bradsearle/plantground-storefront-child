<?php

/**
 * Template for displaying product archives (main shop page + categories)
 *
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

get_header('shop');

do_action('woocommerce_before_main_content');
?>





<div class="site-main">
  <div class="custom-container">
    <div class="hero__copy">
      <h1 class=" hero__title">Unique botanicals.</h1>
    </div>
    <div class="hero__copy-second">
      <h2 class="hero__title">Find your type.</h2>
    </div>
  </div>

  <?php if (is_shop() || is_product_category()) : ?>

    <div class="mobile-filter__container"> <button class="mobile-filter-toggle" id="mobile-filter-toggle">
        <span id="filter-text">Show Filters</span>
        <svg id="filter-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
          <path d="M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z" />
        </svg>
      </button></div>
    <div class="shop-controls" id="shop-controls">


      <!-- <button class="shop__btn"> Shop Originals </button> -->

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

            <label class="toggle-switch">
              <input type="checkbox" value="originals" class="category-toggle" />
              <span class="slider"></span>
              <span class="toggle-label">Originals</span>
            </label>

            <!-- <label class="toggle-switch">
              <input type="checkbox" value="seeds" class="category-toggle" />
              <span class="slider"></span>
              <span class="toggle-label">Seeds</span>
            </label> -->
          </div>
        </div>

        <div class="shop-controls__right">
          <?php
          // FINAL FIX: Calling custom functions to render bare select/count,
          // removing the native woocommerce_catalog_ordering() that contained the conflicting <form>.
          plantground_render_bare_sort_select();
          plantground_render_bare_result_count();
          ?>
        </div>
      </div>
    </div><?php endif; ?>
  <div class="product-grid__container">
    <?php
    do_action('woocommerce_output_all_notices');

    if (woocommerce_product_loop()) {
      woocommerce_product_loop_start();

      if (wc_get_loop_prop('total')) {
        while (have_posts()) {
          the_post();
          do_action('woocommerce_shop_loop');
          wc_get_template_part('content', 'product');
        }
      }

      woocommerce_product_loop_end();
      do_action('woocommerce_after_shop_loop');
    } else {
      do_action('woocommerce_no_products_found');
    }

    do_action('woocommerce_after_main_content');
    do_action('woocommerce_sidebar');
    ?>
  </div>


</div><?php get_footer('shop'); ?>