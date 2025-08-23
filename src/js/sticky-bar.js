// sticky-bar.js
export function removeStickyBar() {
  document.addEventListener('DOMContentLoaded', () => {
    const stickyBar = document.querySelector('.storefront-sticky-add-to-cart');
    if (stickyBar) stickyBar.remove();
  });
}
