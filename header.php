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
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Site Title -->
        <div class="text-xl font-bold">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:opacity-80">
                Plantground
            </a>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-6 text-sm font-medium">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'items_wrap' => '%3$s',
                'depth' => 1,
                'fallback_cb' => false,
                'walker' => new Walker_Nav_Menu(),
            ]);
            ?>
        </nav>

        <!-- Cart Icon -->
        <?php if ( class_exists('WooCommerce') ) : ?>
            <div class="relative">
                <a href="<?php echo wc_get_cart_url(); ?>" class="flex items-center gap-1">
                    🛒
                    <span class="text-sm">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</header>
