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
.shop-controls {
  position: relative;
  margin-bottom: 20px;
}

.mobile-filter-toggle {
  display: none;
  position: absolute;
  top: 0;
  right: 0;
  background: none;
  border: none;
  cursor: pointer;
  padding: 10px;
}

@media (max-width: 800px) {
  .mobile-filter-toggle {
    display: block;
  }
  
  .shop-controls__row {
    display: none;
  }
  
  .shop-controls__row.mobile-filters-visible {
    display: flex;
  }
  
  .shop-controls__row.mobile-filters-visible {
    flex-direction: column;
    gap: 15px;
    margin-bottom: 15px;
  }
  
  .shop-controls__left,
  .shop-controls__right {
    width: 100%;
  }
}

/* Responsive adjustments for filter controls */
@media (max-width: 800px) {
  .shop-controls__row {
    flex-direction: column;
    gap: 15px;
  }
  
  .shop-controls__left,
  .shop-controls__right {
    width: 100%;
  }
}
</style>

<?php if (is_shop() || is_product_category()) : ?>
  <div class="shop-controls" id="shop-controls">
    <!-- Mobile toggle button -->
    <button class="mobile-filter-toggle" id="mobile-filter-toggle">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/filter.svg" alt="Filter" id="filter-icon" />
    </button>

    <!-- Flex row: filters on left, sort on right -->
    <div class="shop-controls__row" id="filters-container">
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

          <!-- <label class="toggle-switch">
            <input type="checkbox" value="originals" class="category-toggle" />
            <span class="slider"></span>
            <span class="toggle-label">Originals</span>
          </label> -->
        </div>
      </div>

      <div class="shop-controls__right">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileToggle = document.getElementById('mobile-filter-toggle');
  const filtersContainer = document.getElementById('filters-container');
  const filterIcon = document.getElementById('filter-icon');
  
  // Check if all required elements exist
  if (!mobileToggle || !filtersContainer || !filterIcon) {
    console.error('Required elements not found');
    return;
  }

  mobileToggle.addEventListener('click', function(e) {
    e.preventDefault();
    
    // Toggle visibility of filters
    filtersContainer.classList.toggle('mobile-filters-visible');
    
    // Update icon based on visibility state
    if (filtersContainer.classList.contains('mobile-filters-visible')) {
      // Change to X icon (you'll need to replace with your X icon path)
      filterIcon.src = '<?php echo get_stylesheet_directory_uri(); ?>/assets/images/x-icon.svg'; // Update this path to your X icon
      filterIcon.alt = 'Close Filters';
    } else {
      // Revert to filter icon
      filterIcon.src = '<?php echo get_stylesheet_directory_uri(); ?>/assets/images/filter.svg';
      filterIcon.alt = 'Filter';
    }
  });

  // Optional: Close filters when clicking outside
  document.addEventListener('click', function(e) {
    const isClickInsideFilters = filtersContainer.contains(e.target);
    const isClickOnToggle = mobileToggle.contains(e.target);
    
    if (!isClickInsideFilters && !isClickOnToggle && 
        filtersContainer.classList.contains('mobile-filters-visible') &&
        window.innerWidth <= 800) {
      filtersContainer.classList.remove('mobile-filters-visible');
      filterIcon.src = '<?php echo get_stylesheet_directory_uri(); ?>/assets/images/filter.svg';
      filterIcon.alt = 'Filter';
    }
  });

  // Handle window resize to reset state if needed
  window.addEventListener('resize', function() {
    if (window.innerWidth > 800) {
      // On desktop, ensure filters are visible and icon shows filter
      filtersContainer.classList.remove('mobile-filters-visible');
      filterIcon.src = '<?php echo get_stylesheet_directory_uri(); ?>/assets/images/filter.svg';
      filterIcon.alt = 'Filter';
    }
  });
});
</script>

<?php get_footer('shop'); ?>