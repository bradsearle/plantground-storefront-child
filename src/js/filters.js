import { initCustomSelect } from './custom-select.js';

/**
 * Fades in newly loaded products.
 */
export function fadeInProducts() {
  const products = document.querySelectorAll('.product__container.card');
  products.forEach((product, index) => {
    product.style.opacity = 0;
    product.style.transition = 'opacity 0.5s ease-in-out';
    setTimeout(() => {
      product.style.opacity = 1;
    }, index * 150);
  });
}

/**
 * Synchronizes toggle visual state.
 */
export function syncTogglesWithFilters(activeCategories) {
  const toggles = document.querySelectorAll('.category-toggle');
  toggles.forEach((toggle) => {
    const shouldBeChecked = activeCategories.includes(toggle.value);
    toggle.checked = shouldBeChecked;
    const wrapper = toggle.closest('.toggle-switch');
    if (wrapper) wrapper.classList.toggle('is-active', shouldBeChecked);
  });
}

/**
 * AJAX fetch with forced category support.
 */
export function fetchProducts(categories = []) {
  const productContainer =
    document.querySelector('.product-grid__container') ||
    document.querySelector('.woocommerce ul.products');
  const resultCountContainer = document.querySelector('.woocommerce-result-count');
  const sortSelect = document.querySelector('.woocommerce-ordering .orderby');
  const orderby = sortSelect?.value || 'menu_order';

  // NEW: Read data attributes for category restrictions
  const forceCat = productContainer?.dataset.forceCat || '';
  const excludeCat = productContainer?.dataset.excludeCat || '';

  const data = new FormData();
  data.append('action', 'plantground_filter_products');
  data.append('categories', JSON.stringify(categories));
  data.append('orderby', orderby);
  data.append('force_category', forceCat);
  data.append('exclude_category', excludeCat);

  return fetch(plantground_ajax.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    body: data,
  })
    .then((r) => r.json())
    .then((data) => {
      if (productContainer && data.productsHtml) {
        productContainer.innerHTML = data.productsHtml;
        fadeInProducts();
      }
      if (resultCountContainer && data.resultCountHtml) {
        resultCountContainer.innerHTML = data.resultCountHtml;
      }
      setTimeout(() => {
        initCustomSelect();
        syncTogglesWithFilters(categories);
      }, 50);
    })
    .catch((e) => console.error('Fetch error:', e));
}

export function initFilters() {
  const toggles = document.querySelectorAll('.category-toggle');
  const sortSelect = document.querySelector('.woocommerce-ordering .orderby');

  // Initial Sync
  const initialChecked = Array.from(toggles)
    .filter((t) => t.checked)
    .map((t) => t.value);
  syncTogglesWithFilters(initialChecked);

  // Toggle Listeners
  toggles.forEach((toggle) => {
    toggle.addEventListener('change', () => {
      const active = Array.from(toggles)
        .filter((t) => t.checked)
        .map((t) => t.value);
      fetchProducts(active);
    });
  });

  // Sort Listener
  if (sortSelect) {
    sortSelect.addEventListener('change', () => {
      const active = Array.from(toggles)
        .filter((t) => t.checked)
        .map((t) => t.value);
      fetchProducts(active);
    });
  }
}

// Sidebar/Mobile logic
document.addEventListener('DOMContentLoaded', function () {
  const mobileToggle = document.getElementById('mobile-filter-toggle');
  const filtersContainer = document.getElementById('filters-container');
  const filterText = document.getElementById('filter-text');

  if (!mobileToggle || !filtersContainer) return;

  mobileToggle.addEventListener('click', (e) => {
    e.preventDefault();
    filtersContainer.classList.toggle('mobile-filters-visible');
    filterText.textContent = filtersContainer.classList.contains('mobile-filters-visible')
      ? 'Hide Filters'
      : 'Show Filters';
  });
});
