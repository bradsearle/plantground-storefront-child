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

  <br><br><br><br>

  <?php
  $theme_path = get_stylesheet_directory_uri();
  $mobile_img  = $theme_path . '/assets/images/hero-mobile.webp';
  $desktop_img = $theme_path . '/assets/images/hero-desktop.webp';
  ?>

  <div class="custom-container">
    <div class="hero-banner">
      <img src="<?php echo $mobile_img; ?>" class="parallax-img mobile-img" alt="">
      <img src="<?php echo $desktop_img; ?>" class="parallax-img desktop-img" alt="">
    </div>
  </div>

  <br><br>

  <?php if (is_shop() || is_product_category()) : ?>
    <div class="shop-controls" id="shop-controls">
      <button class="mobile-filter-toggle" id="mobile-filter-toggle">
        <span id="filter-text">Show Filters</span>
        <svg id="filter-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
          <path d="M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z" />
        </svg>
      </button>

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