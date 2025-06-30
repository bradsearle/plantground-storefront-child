<div class="nav__info-bar">testing</div>

<nav class="nav__flex">
  <div class="menu__btn">
    <button
      class="menu"
      onclick="this.classList.toggle('opened');this.setAttribute('aria-expanded', this.classList.contains('opened'))"
      aria-label="Main Menu"
    >
      <svg
        width="100"
        height="100"
        viewBox="0 0 100 100"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          class="line line1"
          d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058"
        />
        <path class="line line2" d="M 20,50 H 80" />
        <path
          class="line line3"
          d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942"
        />
      </svg>
    </button>
  </div>

  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav__logo">
    plantground
  </a>


</div>

<a href="#"
   class="cart-link cfw-side-cart-open-trigger"
   title="View your shopping cart"
   role="button"
   aria-label="Open shopping cart"
   onclick="event.preventDefault();"
>
  <div class="nav__cart">
    <svg class="cart-icon" width="24" height="24" ...>...</svg>
    <span id="cart-count" class="cart-count <?php echo WC()->cart->get_cart_contents_count() == 0 ? 'hidden' : ''; ?>">
      <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bar-code.svg" class="header__img" />
  </div>
</a>

</div>
</nav>

