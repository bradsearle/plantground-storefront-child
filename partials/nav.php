<div class="nav__info-bar">
  <span class="nav__info-bar__message visible" id="message-1">Free shipping over $80</span>
  <span class="nav__info-bar__message hidden" id="message-2">
    Orders in by Sunday ship <span class="ship-date">July 1</span>
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
  const infoBar = document.querySelector('.nav__info-bar');
  const msg1 = document.getElementById('message-1');
  const msg2 = document.getElementById('message-2');
  const shipDateSpan = document.querySelector('.ship-date');

  // Month names array for display
  const monthNames = [
    "January", "February", "March",
    "April", "May", "June",
    "July", "August", "September",
    "October", "November", "December"
  ];

  // Calculate next Tuesday after today (or next week if today is Tuesday)
  const now = new Date();
  const dayOfWeek = now.getDay(); // Sunday=0, Monday=1, ..., Saturday=6
  const daysUntilTuesday = ((9 - dayOfWeek) % 7) || 7;
  const nextTuesday = new Date(now);
  nextTuesday.setDate(now.getDate() + daysUntilTuesday);

  // Set ship date content with month and day wrapped in ship-date span
  shipDateSpan.textContent = `${monthNames[nextTuesday.getMonth()]} ${nextTuesday.getDate()}`;

  // Initially hide both messages (opacity 0)
  msg1.classList.add('hidden');
  msg1.classList.remove('visible');
  msg2.classList.add('hidden');
  msg2.classList.remove('visible');

  const fadeDuration = 700; // must match CSS opacity transition duration

  // Fade in first message with 2 seconds delay and fade transition
  setTimeout(() => {
    msg1.classList.remove('hidden');
    setTimeout(() => {
      msg1.classList.add('visible');
    }, 50);
  }, 2000);

  let showingFirst = true;

  function fadeMessages() {
    const current = showingFirst ? msg1 : msg2;
    const next = showingFirst ? msg2 : msg1;

    // Fade out current message
    current.classList.remove('visible');

    // After fadeDuration, hide current and fade in next
    setTimeout(() => {
      current.classList.add('hidden');
      next.classList.remove('hidden');

      // Slight delay before fade-in for smooth transition
      setTimeout(() => {
        next.classList.add('visible');
      }, 100);

      showingFirst = !showingFirst;
    }, fadeDuration);
  }

  // Start cycling messages after initial fade-in completes
  setTimeout(() => {
    setInterval(fadeMessages, 4000);
  }, 2000 + fadeDuration);

  // Scroll detection for showing/hiding info bar
  let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;

  window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop === 0) {
      infoBar.classList.add('nav--visible');
      infoBar.classList.remove('nav--hidden');
    } else if (scrollTop > lastScrollTop) {
      infoBar.classList.add('nav--hidden');
      infoBar.classList.remove('nav--visible');
    } else {
      infoBar.classList.add('nav--hidden');
      infoBar.classList.remove('nav--visible');
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });
});

</script>
