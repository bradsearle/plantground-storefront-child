// cart-count.js
export function initCartCount() {
  const countEl = document.getElementById('cart-count-number');
  if (!countEl) return;

  // Update count when WooCommerce updates cart fragments (no page reload!)
  document.body.addEventListener('wc_fragment_refresh', () => {
    // Try to get count from WooCommerce's own updated fragment
    const wcCount = document.querySelector(
      '[data-cart-count], .cart-contents .count, .woocommerce-cart-link .count'
    );
    if (wcCount) {
      const match = wcCount.textContent.trim().match(/\d+/);
      if (match) {
        countEl.textContent = match[0];
        return;
      }
    }

    // Fallback: fetch directly (only if you added the PHP snippet)
    // fetch('/?wc-ajax=get_cart_count')
    //   .then(r => r.json())
    //   .then(data => { if (data.count !== undefined) countEl.textContent = data.count; });
  });
}
