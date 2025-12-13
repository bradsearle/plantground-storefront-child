// main.js – Mobile Menu Toggle with Icon Swap + Scroll Lock

const menuToggle = document.getElementById('menu-toggle');
const overlay = document.getElementById('mobile-menu-overlay');

if (menuToggle && overlay) {
  // Store original menu icon (☰) as HTML string
  const menuIconHtml = menuToggle.innerHTML.trim();

  // Define close icon (✕) as HTML string
  const closeIconHtml = `
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
  `.trim();

  // Helper to update button state
  const updateMenuButton = (isOpen) => {
    menuToggle.innerHTML = isOpen ? closeIconHtml : menuIconHtml;
    menuToggle.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
  };

  // Lock scroll: prevent background scroll, preserve scroll position
  const lockScroll = () => {
    const scrollY = window.scrollY;
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollY}px`;
    document.body.style.width = '100%'; // prevents layout shift on mobile
  };

  // Unlock scroll: restore scroll position
  const unlockScroll = () => {
    const scrollY = parseInt(document.body.style.top || '0', 10) * -1;
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    window.scrollTo(0, scrollY);
  };

  // Open menu
  const openMenu = () => {
    overlay.classList.add('is-open');
    updateMenuButton(true);
    lockScroll();
  };

  // Close menu
  const closeMenu = () => {
    overlay.classList.remove('is-open');
    updateMenuButton(false);
    unlockScroll();
  };

  // Toggle on button click
  menuToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    if (overlay.classList.contains('is-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // Close when clicking overlay background
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      closeMenu();
    }
  });

  // Optional: Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
      closeMenu();
    }
  });
}
