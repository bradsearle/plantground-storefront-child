<?php

/**
 * Custom Header Template for Plantground Child Theme
 *
 * This file overrides the Storefront header.
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>


</head>

<body <?php body_class(is_front_page() ? 'pg-content-hidden' : ''); ?>>
  <script>
    if (sessionStorage.getItem('pgPreloaderSeen')) {
      document.documentElement.classList.add('pg-no-preloader');
    }
  </script>
  <?php if (is_front_page()) : ?>
    <!-- Preloader Overlay -->
    <div id="pg-preloader" class="pg-preloader">
      <div class="pg-preloader__inner">
        <div class="pg-preloader__logo ">Plantground</div>
        <!-- <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-fff.svg"
          alt="Plantground Logo"
          class="pg-preloader__logo" /> -->
        <!-- <div class="pg-preloader__logo pg-preloader__inner">plantground</div> -->
        <div class="pg-preloader__mask"></div>
      </div>
    </div>
  <?php endif; ?>

  <?php wp_body_open(); ?>

  <header class="site-header">
    <?php get_template_part('partials/nav'); ?>
  </header>