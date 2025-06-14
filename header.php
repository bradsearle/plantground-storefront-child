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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>






<header id="main-header" class="fixed top-0 left-0 w-full z-50 bg-white/70 backdrop-blur-md shadow-sm transition-transform duration-300 translate-y-0">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-center justify-between h-16">
      
      <!-- Left: Hamburger + Logo -->
      <div class="flex items-center space-x-4">
        <!-- Hamburger -->
        <button id="menu-toggle" class="md:hidden text-gray-800 focus:outline-none">
          <svg id="hamburger-icon" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg id="close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- Desktop Logo -->
        <div class="text-xl font-bold text-gray-900 hidden md:block">plantground</div>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex space-x-6">
          <a href="#" class="text-gray-700 hover:text-black">About</a>
          <a href="#" class="text-gray-700 hover:text-black">Contact</a>
        </nav>
      </div>

      <!-- Desktop Cart -->
      <div class="hidden md:block text-gray-700">🛒</div>

      <!-- Mobile Centered Logo -->
      <div class="absolute inset-0 flex justify-center items-center md:hidden pointer-events-none">
        <div class="text-xl font-bold text-gray-900">plantground</div>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu"
       class="fixed top-0 left-0 w-screen h-screen z-40 bg-white/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 md:hidden flex items-center justify-center">
    <div class="flex flex-col items-center justify-center space-y-10 text-xl font-medium text-gray-800">
      <div class="absolute top-5 left-0 right-0 flex items-center justify-between px-6">
        <div class="text-xl font-bold text-gray-900">plantground</div>
        <div>🛒 cart</div>
      </div>
      <a href="#" class="hover:text-black">About</a>
      <a href="#" class="hover:text-black">Contact</a>
      <a href="#" class="hover:text-black">Shop</a>
    </div>
  </div>
</header>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');
    const header = document.getElementById('main-header');
    const handheldFooter = document.querySelector('.storefront-handheld-footer-bar');

    let lastScrollY = window.scrollY;
    const scrollThreshold = 200;

    // Toggle mobile menu
    menuToggle.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.contains('opacity-100');

      if (isOpen) {
        mobileMenu.classList.remove('opacity-100', 'pointer-events-auto');
        mobileMenu.classList.add('opacity-0', 'pointer-events-none');
        hamburgerIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        if (handheldFooter) handheldFooter.style.display = '';
      } else {
        mobileMenu.classList.add('opacity-100', 'pointer-events-auto');
        mobileMenu.classList.remove('opacity-0', 'pointer-events-none');
        hamburgerIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        if (handheldFooter) handheldFooter.style.display = 'none';
      }
    });

    // Auto-hide header on scroll
    window.addEventListener('scroll', () => {
      const currentY = window.scrollY;

      if (currentY > scrollThreshold) {
        if (currentY > lastScrollY) {
          header.classList.add('translate-y-[-100%]');
          header.classList.remove('translate-y-0');
        } else {
          header.classList.add('translate-y-0');
          header.classList.remove('translate-y-[-100%]');
        }
      } else {
        header.classList.add('translate-y-0');
        header.classList.remove('translate-y-[-100%]');
      }

      lastScrollY = currentY;
    });
  });
</script>

<?php
// Your main template continues...
?>
