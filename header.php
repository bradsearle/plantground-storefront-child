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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body <?php body_class(); ?>>
  
<?php wp_body_open(); ?>
<?php if ( is_front_page() ) : ?>
  <div id="pg-preloader" class="pg-preloader" aria-hidden="true">
    <div class="pg-preloader__inner">
      <!-- Big white logo (SVG) -->
      <?php
        // If you have a template part for the logo, include it. Otherwise inline the SVG.
        // Example: get_template_part('template-parts/logo', 'white');
      ?>
      <div class="pg-preloader__logo" aria-label="Plantground">
        <?php echo file_get_contents( get_stylesheet_directory() . '/assets/logo-white.svg' ); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<header class="site-header">
  <div class="site-branding">
    <!-- Small black logo in the nav that will animate in -->
    <a class="pg-nav-logo" href="<?php echo esc_url( home_url('/') ); ?>" aria-label="Plantground">
      <?php echo file_get_contents( get_stylesheet_directory() . '/assets/logo-black.svg' ); ?>
    </a>
  </div>
  <!-- rest of your header -->
</header>

<header class="site-header">
  <?php get_template_part('partials/nav'); ?>
</header>
