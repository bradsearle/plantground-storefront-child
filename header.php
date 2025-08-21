<?php
/**
 * Custom Header Template for Plantground Child Theme
 * This file overrides the Storefront header.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <!-- Vite/WordPress will handle your built assets; no GSAP CDN here -->
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- SIMPLE PRELOADER OVERLAY (black screen that fades away) -->
<!-- For testing, render on all pages. Later you can gate with: if ( is_front_page() ) : ... endif; -->
<div id="pg-preloader" class="pg-preloader" aria-hidden="true"></div>

<!-- Your header + nav -->
<header class="site-header">
  <?php get_template_part('partials/nav'); ?>
</header>
