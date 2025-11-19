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
  <div class="nav__wrap">
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

    <a href="#"
      class="cart-link cfw-side-cart-open-trigger"
      title="View your shopping cart"
      role="button"
      aria-label="Open shopping cart"
      onclick="event.preventDefault();">
      <div class="nav__cart">
        <span
          id="cart-count"
          class="cart-count <?php echo $count == 0 ? 'hidden' : ''; ?>">
          (<?php echo $count; ?>)
        </span>
        <img
          src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/shopping_bag.svg"
          class="header__img" />
      </div>
    </a>
  </div>
</nav>

<!-- Main nav -->