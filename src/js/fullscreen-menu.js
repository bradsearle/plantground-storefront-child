// main.js – Mobile Menu Toggle with Icon Swap

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

  // Open menu
  const openMenu = () => {
    overlay.classList.add('is-open');
    updateMenuButton(true);
  };

  // Close menu
  const closeMenu = () => {
    overlay.classList.remove('is-open');
    updateMenuButton(false);
  };

  // Toggle on button click
  menuToggle.addEventListener('click', (e) => {
    e.stopPropagation(); // prevent triggering overlay click
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
}
