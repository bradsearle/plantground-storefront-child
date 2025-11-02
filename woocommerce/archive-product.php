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

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<style>
  .flex-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }
</style>

<?php if (is_shop() || is_product_category()) : ?>
  <div class="shop-controls" id="shop-controls">
    <!-- Mobile toggle button (for later use) -->
    <button class="shop-controls__mobile-toggle" aria-expanded="false" aria-controls="shop-controls-panel" id="shop-controls-toggle" type="button">
      <span class="sr-only">Open filters and sort</span>
      <span class="icon">☰</span>
    </button>

    <!-- Flex row: filters on left, sort on right -->
    <div class="flex-row">
      <div class="left-item">
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
        </div>
      </div>

      <div class="right-item">
        <div class="shop-controls__sort">
          <?php woocommerce_catalog_ordering(); ?>
        </div>
      </div>
    </div> <!-- /.flex-row -->
  </div><!-- /.shop-controls -->
<?php endif; ?>

<?php
// Show notices, result count, and start the product loop.
do_action('woocommerce_output_all_notices');
woocommerce_result_count();

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

</div><!-- /.site-main -->

<?php get_footer('shop'); ?>