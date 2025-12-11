<!-- Info bar (scrolls away naturally for now') -->

<nav class="nav__flex">
  <div class="nav__info-bar">
    <span class="nav__info-bar__message visible" id="message-1">
      Free shipping over $80
    </span>
    <span class="nav__info-bar__message hidden" id="message-2">
      Orders in by Sunday ship <span class="ship-date">July 1</span>
    </span>
  </div>
  <div class="nav__wrap  nav__flex-container">



    <div class="nav__menu">
      <!-- Menu trigger (your SVG inside a button for accessibility) -->
      <button id="menu-toggle" aria-label="Open menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>

      <!-- Fullscreen overlay (initially hidden) -->
      <div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>

    </div>

    <a href="<?php echo esc_url(home_url('/')); ?>" class="nav__logo">
      <div class="nav-logo__inner">
        <img
          src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-000.svg"
          alt="Plantground Logo"
          class="nav-logo__img" />
      </div>
    </a>

    <?php
    $count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>

    <div class="nav__actions">
      <?php
      // Logic to determine which links to show based on the page
      if (is_shop() || is_front_page()) {
        // On Shop/Home, show link to Originals, labeled "Shop Originals"
        echo '<a href="/originals" class="nav__link">Shop Originals</a>';
      } elseif (is_page('originals')) {
        // On Originals page, only show link to Shop
        echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="nav__link">Shop</a>';
      } else {
        // On all other pages (Cart, Account, etc.), show both
        echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="nav__link nav__link--shop">Shop</a>';
        echo '<a href="/originals" class="nav__link nav__link--originals">Originals</a>';
      }
      ?>
      <a href="#"
        class="cart-link cfw-side-cart-open-trigger"
        title="View your shopping cart"
        role="button"
        aria-label="Open shopping cart"
        onclick="event.preventDefault();">
        <div class="nav__cart">

          <span class="nav__cart-title">Cart</span>

          <img
            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/shopping_bag.svg"
            class="cart__img" />
          <span
            id="cart-count"
            class="cart-count <?php echo $count == 0 ? 'hidden' : ''; ?>">
            (<?php echo $count; ?>)
          </span>
        </div>
      </a>
    </div>
  </div>
</nav>







<!-- Main nav -->