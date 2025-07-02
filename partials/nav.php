<div class="nav__info-bar">
  <span class="info-bar__message visible" id="message-1">Free shipping over $80</span>
  <span class="info-bar__message hidden" id="message-2">
    Orders in Sunday ship Tuesday the <span id="ship-date"></span>
  </span>
</div>

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

  <a href="<?php echo esc_url(home_url('/')); ?>" class="nav__logo">
    plantground
  </a>
  
  <?php
  $count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
  ?>

  <a href="#"
     class="cart-link cfw-side-cart-open-trigger"
     title="View your shopping cart"
     role="button"
     aria-label="Open shopping cart"
     onclick="event.preventDefault();"
  >
  
    <div class="nav__cart">
      

      <span id="cart-count" class="cart-count <?php echo $count == 0 ? 'hidden' : ''; ?>">
  (<?php echo $count; ?>)
</span>


      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/shopping_bag.svg" class="header__img" />
    </div>
  </a>
</nav>



  <script>
  document.addEventListener('DOMContentLoaded', () => {
  const msg1 = document.getElementById('message-1');
  const msg2 = document.getElementById('message-2');
  const shipDateSpan = document.getElementById('ship-date');

  // Calculate Tuesday after the most recent Sunday
  const now = new Date();
  const day = now.getDay(); // Sunday = 0
  const sunday = new Date(now);
  sunday.setDate(now.getDate() - day);
  const nextTuesday = new Date(sunday);
  nextTuesday.setDate(sunday.getDate() + 2);
  shipDateSpan.textContent = nextTuesday.getDate();

  // Start the fade-in/out message loop
  let showingFirst = true;
  setInterval(() => {
    if (showingFirst) {
      msg1.classList.replace('visible', 'hidden');
      msg2.classList.replace('hidden', 'visible');
    } else {
      msg2.classList.replace('visible', 'hidden');
      msg1.classList.replace('hidden', 'visible');
    }
    showingFirst = !showingFirst;
  }, 10000); // switch every 10 seconds
});

</script>

