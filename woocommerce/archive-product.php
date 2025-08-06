<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');
?>

<div class="site-main">  <!-- Added wrapper here -->

<style>
 
  .hero {
    height: auto;
    display: block;
    align-items: flex-start;
    justify-content: flex-start;
    margin-top: 100px;
  }
  .hero__mask {
    overflow: hidden;
    height: 5.5rem; /* tightly matches font size and line height */
   
  }
  .hero__title {
    font-size: 3.5rem;
    font-weight: 500;
    line-height: 1.1;
    display: flex;
    color: #111;
  }
  .hero__word {
    display: inline-block;
    transform: translateY(100%);
    will-change: transform;
    white-space: nowrap;
  }
  .hero__word--second{
    display: flex;
  }
</style>
</head>
<body>

<!-- <section class="hero">
  <div class="hero__mask">
    <h1 class="hero__title">
      <span class="hero__word hero__word--first">Slinging uique cactus, succulents, and originals, mostly bare root, </span>
     </h1>
  </div>
</section> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>


window.addEventListener("DOMContentLoaded", () => {
  const tl = gsap.timeline();

  const customEase = "cubic-bezier(0.215, 0.61, 0.355, 1)"; // stylish and smooth

  tl.to(".hero__word--first", {
    y: 0,
    opacity: 1,
    duration: 1.1, // match the website's transition timing
    ease: customEase,
  }, 0); // no delay

  tl.to(".hero__word--second", {
    y: 0,
    opacity: 1,
    duration: 1.1,
    ease: "back.inOut(1)",
  }, 0.1); // subtle stagger for polish
});

</script>
  <?php if (is_shop() || is_product_category()) : ?>
    <div id="plantground-filters">
      <label class="toggle-switch">
        <input type="checkbox" value="cactus" class="category-toggle">
        <span class="slider"></span>
        <span class="toggle-label">Cactus</span>
      </label>

      <label class="toggle-switch">
        <input type="checkbox" value="succulents" class="category-toggle">
        <span class="slider"></span>
        <span class="toggle-label">Succulents</span>
      </label>

      <label class="toggle-switch">
        <input type="checkbox" value="originals" class="category-toggle">
        <span class="slider"></span>
        <span class="toggle-label">Originals</span>
      </label>
    </div>
  <?php endif; ?>

<?php
/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
do_action('woocommerce_shop_loop_header');

if (woocommerce_product_loop()) {

    /**
     * Hook: woocommerce_before_shop_loop.
     *
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    do_action('woocommerce_before_shop_loop');

    woocommerce_product_loop_start();

    if (wc_get_loop_prop('total')) {
        while (have_posts()) {
            the_post();

            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action('woocommerce_shop_loop');

            wc_get_template_part('content', 'product');
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action('woocommerce_after_shop_loop');
} else {
    /**
     * Hook: woocommerce_no_products_found.
     *
     * @hooked wc_no_products_found - 10
     */
    do_action('woocommerce_no_products_found');
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');
?>

</div> <!-- /.site-main -->

<?php get_footer('shop'); ?>
